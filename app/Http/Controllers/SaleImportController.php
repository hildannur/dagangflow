<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SaleImportController extends Controller
{
    public function downloadTemplate()
    {
        $fileName = 'template-import-penjualan-dagangflow.csv';

        return response()->streamDownload(function () {
            $handle = fopen('php://output', 'w');

            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($handle, [
                'tanggal',
                'produk',
                'jumlah',
                'channel',
                'biaya_platform',
                'status',
                'catatan',
            ]);

            fputcsv($handle, [
                now()->toDateString(),
                'Contoh Produk',
                '2',
                'Shopee',
                '2500',
                'Selesai',
                'Import dari marketplace',
            ]);

            fclose($handle);
        }, $fileName, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    public function import(Request $request)
    {
        $request->validate([
            'csv_file' => ['required', 'file', 'mimes:csv,txt', 'max:5120'],
        ], [
            'csv_file.required' => 'File CSV wajib dipilih.',
            'csv_file.file' => 'File tidak valid.',
            'csv_file.mimes' => 'File harus berformat CSV.',
            'csv_file.max' => 'Ukuran file maksimal 5MB.',
        ]);

        $path = $request->file('csv_file')->getRealPath();

        if (! $path || ! file_exists($path)) {
            return back()->withErrors([
                'csv_file' => 'File CSV tidak bisa dibaca.',
            ]);
        }

        $rows = $this->readCsv($path);

        if (count($rows) < 2) {
            return back()->withErrors([
                'csv_file' => 'CSV kosong atau belum memiliki data transaksi.',
            ]);
        }

        $headers = array_map(fn ($header) => $this->normalizeHeader($header), $rows[0]);

        $requiredHeaders = [
            'tanggal',
            'produk',
            'jumlah',
            'channel',
            'biaya_platform',
            'status',
        ];

        foreach ($requiredHeaders as $requiredHeader) {
            if (! in_array($requiredHeader, $headers, true)) {
                return back()->withErrors([
                    'csv_file' => 'Format CSV tidak sesuai. Kolom wajib: tanggal, produk, jumlah, channel, biaya_platform, status, catatan.',
                ]);
            }
        }

        $userId = auth()->id();

        $products = Product::where('user_id', $userId)
            ->get()
            ->keyBy(fn ($product) => $this->normalizeProductName($product->name));

        $successCount = 0;
        $failedRows = [];

        foreach (array_slice($rows, 1) as $index => $row) {
            $rowNumber = $index + 2;

            if ($this->isEmptyRow($row)) {
                continue;
            }

            $data = $this->combineRow($headers, $row);

            $productName = trim($data['produk'] ?? '');
            $productKey = $this->normalizeProductName($productName);
            $quantity = (int) $this->cleanNumber($data['jumlah'] ?? 0);
            $channel = trim($data['channel'] ?? '');
            $platformFee = (int) $this->cleanNumber($data['biaya_platform'] ?? 0);
            $status = trim($data['status'] ?? 'Selesai');
            $note = trim($data['catatan'] ?? '');
            $saleDate = trim($data['tanggal'] ?? '');

            if (! $productName) {
                $failedRows[] = "Baris {$rowNumber}: nama produk kosong.";
                continue;
            }

            if (! $products->has($productKey)) {
                $failedRows[] = "Baris {$rowNumber}: produk \"{$productName}\" tidak ditemukan di DagangFlow.";
                continue;
            }

            if ($quantity < 1) {
                $failedRows[] = "Baris {$rowNumber}: jumlah harus lebih dari 0.";
                continue;
            }

            if (! $channel) {
                $failedRows[] = "Baris {$rowNumber}: channel kosong.";
                continue;
            }

            $parsedSaleDate = $this->parseDate($saleDate);

            if (! $parsedSaleDate) {
                $failedRows[] = "Baris {$rowNumber}: format tanggal tidak valid. Gunakan format YYYY-MM-DD.";
                continue;
            }

            $product = $products->get($productKey)->fresh();

            if (! $product) {
                $failedRows[] = "Baris {$rowNumber}: produk \"{$productName}\" tidak ditemukan.";
                continue;
            }

            if ($product->stock < $quantity) {
                $failedRows[] = "Baris {$rowNumber}: stok \"{$product->name}\" tidak cukup. Stok tersedia {$product->stock}, diminta {$quantity}.";
                continue;
            }

            DB::transaction(function () use (
                $userId,
                $product,
                $quantity,
                $channel,
                $platformFee,
                $status,
                $note,
                $parsedSaleDate
            ) {
                $sellingPrice = $product->selling_price;
                $grossTotal = $sellingPrice * $quantity;
                $netTotal = $grossTotal - $platformFee;

                Sale::create([
                    'user_id' => $userId,
                    'product_id' => $product->id,
                    'channel' => $channel,
                    'quantity' => $quantity,
                    'selling_price' => $sellingPrice,
                    'gross_total' => $grossTotal,
                    'platform_fee' => $platformFee,
                    'net_total' => $netTotal,
                    'status' => $status ?: 'Selesai',
                    'note' => $note ?: 'Import CSV',
                    'sale_date' => $parsedSaleDate,
                ]);

                $product->decrement('stock', $quantity);
            });

            $successCount++;
        }

        $message = "Import selesai. {$successCount} transaksi berhasil masuk.";

        if (count($failedRows) > 0) {
            $message .= ' ' . count($failedRows) . ' baris gagal.';
        }

        return redirect('/sales')
            ->with('success', $message)
            ->with('importErrors', array_slice($failedRows, 0, 10));
    }

    private function readCsv(string $path): array
    {
        $content = file_get_contents($path);
        $firstLine = Str::of($content)->explode("\n")->first() ?? '';

        $commaCount = substr_count($firstLine, ',');
        $semicolonCount = substr_count($firstLine, ';');
        $delimiter = $semicolonCount > $commaCount ? ';' : ',';

        $handle = fopen($path, 'r');
        $rows = [];

        while (($row = fgetcsv($handle, 0, $delimiter)) !== false) {
            $rows[] = $row;
        }

        fclose($handle);

        return $rows;
    }

    private function combineRow(array $headers, array $row): array
    {
        $data = [];

        foreach ($headers as $index => $header) {
            $data[$header] = $row[$index] ?? null;
        }

        return $data;
    }

    private function normalizeHeader(?string $header): string
    {
        $header = trim((string) $header);
        $header = preg_replace('/^\xEF\xBB\xBF/', '', $header);
        $header = Str::lower($header);
        $header = str_replace([' ', '-'], '_', $header);

        return match ($header) {
            'tanggal_transaksi', 'tgl', 'date', 'sale_date' => 'tanggal',
            'nama_produk', 'product', 'product_name' => 'produk',
            'qty', 'quantity', 'jumlah_terjual' => 'jumlah',
            'platform', 'marketplace', 'sumber' => 'channel',
            'fee_platform', 'admin_fee', 'potongan', 'biaya_admin' => 'biaya_platform',
            'transaction_status' => 'status',
            'note', 'notes', 'keterangan' => 'catatan',
            default => $header,
        };
    }

    private function normalizeProductName(?string $name): string
    {
        return Str::of((string) $name)
            ->lower()
            ->squish()
            ->toString();
    }

    private function cleanNumber($value): string
    {
        return preg_replace('/\D/', '', (string) $value) ?: '0';
    }

    private function parseDate(?string $date): ?string
    {
        try {
            if (! $date) {
                return now()->toDateString();
            }

            return Carbon::parse($date)->toDateString();
        } catch (\Throwable $e) {
            return null;
        }
    }

    private function isEmptyRow(array $row): bool
    {
        return collect($row)
            ->filter(fn ($value) => trim((string) $value) !== '')
            ->isEmpty();
    }
}