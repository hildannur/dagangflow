<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class SaleController extends Controller
{
    public function index(Request $request)
    {
        $userId = auth()->id();
    
        $startDate = $request->query('start_date')
            ? Carbon::parse($request->query('start_date'))->startOfDay()
            : now()->startOfMonth();
    
        $endDate = $request->query('end_date')
            ? Carbon::parse($request->query('end_date'))->endOfDay()
            : now()->endOfMonth();
    
        $selectedPeriod = [
            'start_date' => $startDate->toDateString(),
            'end_date' => $endDate->toDateString(),
        ];
    
        $allSales = Sale::with('product')
            ->where('user_id', $userId)
            ->latest()
            ->get();
    
        $sales = $allSales->filter(function ($sale) use ($startDate, $endDate) {
            $saleDate = $sale->sale_date
                ? Carbon::parse($sale->sale_date)
                : $sale->created_at;
    
            return $saleDate->between($startDate, $endDate);
        });
    
        $products = Product::where('user_id', $userId)
            ->where('stock', '>', 0)
            ->latest()
            ->get();
    
        $todayRevenue = $sales->sum('gross_total');
        $monthRevenue = $sales->sum('gross_total');
        $platformFees = $sales->sum('platform_fee');
        $netRevenue = $sales->sum('net_total');
    
        return view('sales', compact(
            'sales',
            'products',
            'todayRevenue',
            'monthRevenue',
            'platformFees',
            'netRevenue',
            'selectedPeriod'
        ));
    }

    public function store(Request $request)
    {
        $request->merge([
            'quantity' => preg_replace('/\D/', '', $request->quantity),
            'platform_fee' => preg_replace('/\D/', '', $request->platform_fee),
        ]);
        $data = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'channel' => ['required', 'string', 'max:255'],
            'quantity' => ['required', 'integer', 'min:1'],
            'platform_fee' => ['nullable', 'integer', 'min:0'],
            'status' => ['required', 'string', 'max:255'],
            'note' => ['nullable', 'string'],
            'sale_date' => ['nullable', 'date'],
        ]);

        $product = Product::where('user_id', auth()->id())
            ->where('id', $data['product_id'])
            ->firstOrFail();

        if ($product->stock < $data['quantity']) {
            return back()->withErrors([
                'stock' => 'Stok produk tidak cukup.',
            ]);
        }

        $sellingPrice = $product->selling_price;
        $grossTotal = $sellingPrice * $data['quantity'];
        $platformFee = $data['platform_fee'] ?? 0;
        $netTotal = $grossTotal - $platformFee;

        Sale::create([
            'user_id' => auth()->id(),
            'product_id' => $product->id,
            'channel' => $data['channel'],
            'quantity' => $data['quantity'],
            'selling_price' => $sellingPrice,
            'gross_total' => $grossTotal,
            'platform_fee' => $platformFee,
            'net_total' => $netTotal,
            'status' => $data['status'],
            'note' => $data['note'] ?? null,
            'sale_date' => $data['sale_date'] ?? now()->toDateString(),
        ]);

        $product->decrement('stock', $data['quantity']);

        return redirect('/sales')->with('success', 'Penjualan berhasil dicatat.');
    }

    public function update(Request $request, Sale $sale)
    {
    abort_if($sale->user_id !== auth()->id(), 403);

    $request->merge([
        'quantity' => preg_replace('/\D/', '', $request->quantity),
        'platform_fee' => preg_replace('/\D/', '', $request->platform_fee),
    ]);

    $data = $request->validate([
        'product_id' => ['required', 'exists:products,id'],
        'channel' => ['required', 'string', 'max:255'],
        'quantity' => ['required', 'integer', 'min:1'],
        'platform_fee' => ['nullable', 'integer', 'min:0'],
        'status' => ['required', 'string', 'max:255'],
        'note' => ['nullable', 'string'],
        'sale_date' => ['nullable', 'date'],
    ]);

    $oldProduct = $sale->product;

    if ($oldProduct && $oldProduct->user_id === auth()->id()) {
        $oldProduct->increment('stock', $sale->quantity);
    }

    $newProduct = Product::where('user_id', auth()->id())
        ->where('id', $data['product_id'])
        ->firstOrFail();

    if ($newProduct->stock < $data['quantity']) {
        if ($oldProduct && $oldProduct->user_id === auth()->id()) {
            $oldProduct->decrement('stock', $sale->quantity);
        }

        return back()->withErrors([
            'stock' => 'Stok produk tidak cukup untuk perubahan penjualan.',
        ]);
        }

        $sellingPrice = $newProduct->selling_price;
        $grossTotal = $sellingPrice * $data['quantity'];
        $platformFee = $data['platform_fee'] ?? 0;
        $netTotal = $grossTotal - $platformFee;

        $sale->update([
            'product_id' => $newProduct->id,
            'channel' => $data['channel'],
            'quantity' => $data['quantity'],
            'selling_price' => $sellingPrice,
            'gross_total' => $grossTotal,
            'platform_fee' => $platformFee,
            'net_total' => $netTotal,
            'status' => $data['status'],
            'note' => $data['note'] ?? null,
            'sale_date' => $data['sale_date'] ?? now()->toDateString(),
        ]);

        $newProduct->decrement('stock', $data['quantity']);

        return redirect('/sales')->with('success', 'Penjualan berhasil diperbarui.');
    }

    public function destroy(Sale $sale)
    {
        abort_if($sale->user_id !== auth()->id(), 403);

        $product = $sale->product;

        if ($product && $product->user_id === auth()->id()) {
            $product->increment('stock', $sale->quantity);
        }

        $sale->delete();

        return redirect('/sales')->with('success', 'Penjualan berhasil dihapus dan stok dikembalikan.');
    }
    
    public function export(\Illuminate\Http\Request $request)
    {
        $userId = auth()->id();
    
        $startDate = $request->query('start_date')
            ? \Illuminate\Support\Carbon::parse($request->query('start_date'))->startOfDay()
            : now()->startOfMonth();
    
        $endDate = $request->query('end_date')
            ? \Illuminate\Support\Carbon::parse($request->query('end_date'))->endOfDay()
            : now()->endOfMonth();
    
        $sales = \App\Models\Sale::with('product')
            ->where('user_id', $userId)
            ->latest()
            ->get()
            ->filter(function ($sale) use ($startDate, $endDate) {
                $saleDate = $sale->sale_date
                    ? \Illuminate\Support\Carbon::parse($sale->sale_date)
                    : $sale->created_at;
    
                return $saleDate->between($startDate, $endDate);
            });
    
        $grossRevenue = $sales->sum('gross_total');
        $platformFees = $sales->sum('platform_fee');
        $netRevenue = $sales->sum('net_total');
        $totalTransactions = $sales->count();
    
        $fileName = 'transaksi-dagangflow-' . $startDate->format('Y-m-d') . '-to-' . $endDate->format('Y-m-d') . '.csv';
    
        return response()->streamDownload(function () use (
            $sales,
            $startDate,
            $endDate,
            $grossRevenue,
            $platformFees,
            $netRevenue,
            $totalTransactions
        ) {
            $handle = fopen('php://output', 'w');
    
            // UTF-8 BOM agar aman dibuka di Excel
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));
    
            fputcsv($handle, ['EXPORT TRANSAKSI DAGANGFLOW']);
            fputcsv($handle, ['Periode', $startDate->format('d M Y') . ' - ' . $endDate->format('d M Y')]);
            fputcsv($handle, []);
    
            fputcsv($handle, ['RINGKASAN TRANSAKSI']);
            fputcsv($handle, ['Total Transaksi', $totalTransactions]);
            fputcsv($handle, ['Total Omzet', $grossRevenue]);
            fputcsv($handle, ['Total Biaya Platform', $platformFees]);
            fputcsv($handle, ['Total Uang Bersih', $netRevenue]);
            fputcsv($handle, []);
    
            fputcsv($handle, ['DETAIL TRANSAKSI']);
            fputcsv($handle, [
                'Tanggal',
                'Kode Transaksi',
                'Produk',
                'Channel',
                'Jumlah Terjual',
                'Harga Jual',
                'Omzet Kotor',
                'Biaya Platform',
                'Uang Bersih',
                'Status',
                'Catatan',
            ]);
    
            foreach ($sales as $sale) {
                $saleDate = $sale->sale_date
                    ? \Illuminate\Support\Carbon::parse($sale->sale_date)
                    : $sale->created_at;
    
                fputcsv($handle, [
                    $saleDate->format('d M Y'),
                    'TRX-' . str_pad($sale->id, 4, '0', STR_PAD_LEFT),
                    $sale->product->name ?? 'Produk terhapus',
                    $sale->channel,
                    $sale->quantity,
                    $sale->selling_price,
                    $sale->gross_total,
                    $sale->platform_fee,
                    $sale->net_total,
                    $sale->status,
                    $sale->note ?? '-',
                ]);
            }
    
            fclose($handle);
        }, $fileName, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}