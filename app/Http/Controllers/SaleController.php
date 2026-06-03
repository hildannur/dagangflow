<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

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

        $todayRevenue = Sale::where('user_id', $userId)
            ->whereDate('sale_date', now()->toDateString())
            ->sum('gross_total');

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

    public function data(Request $request)
    {
        $search = $request->query('search');
        $channel = $request->query('channel');
        $sort = $request->query('sort', 'latest');

        $query = Sale::with('product')
            ->where('user_id', auth()->id())
            ->when($search, function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('channel', 'like', "%{$search}%")
                        ->orWhere('status', 'like', "%{$search}%")
                        ->orWhere('note', 'like', "%{$search}%")
                        ->orWhereHas('product', function ($productQuery) use ($search) {
                            $productQuery->where('name', 'like', "%{$search}%")
                                ->orWhere('category', 'like', "%{$search}%");
                        });
                });
            })
            ->when($channel, function ($query) use ($channel) {
                $query->where('channel', $channel);
            });

        if ($sort === 'gross_desc') {
            $query->orderByDesc('gross_total');
        } elseif ($sort === 'net_desc') {
            $query->orderByDesc('net_total');
        } elseif ($sort === 'qty_desc') {
            $query->orderByDesc('quantity');
        } else {
            $query->latest();
        }

        $sales = $query->paginate(10);

        $products = Product::where('user_id', auth()->id())
            ->where('stock', '>', 0)
            ->orderBy('name')
            ->get()
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'stock' => (int) $product->stock,
                    'selling_price' => (int) $product->selling_price,
                ];
            })
            ->values();

        $channels = Sale::where('user_id', auth()->id())
            ->whereNotNull('channel')
            ->where('channel', '!=', '')
            ->select('channel')
            ->distinct()
            ->orderBy('channel')
            ->pluck('channel')
            ->values();

        return response()
            ->json([
                'data' => $sales->through(function ($sale) {
                    return [
                        'id' => $sale->id,
                        'product_id' => $sale->product_id,
                        'product_name' => $sale->product->name ?? 'Produk terhapus',
                        'channel' => $sale->channel,
                        'quantity' => number_format((int) $sale->quantity, 0, ',', '.'),
                        'quantity_raw' => (int) $sale->quantity,

                        'selling_price' => 'Rp' . number_format((int) $sale->selling_price, 0, ',', '.'),
                        'selling_price_raw' => (int) $sale->selling_price,

                        'gross_total' => 'Rp' . number_format((int) $sale->gross_total, 0, ',', '.'),
                        'gross_total_raw' => (int) $sale->gross_total,

                        'platform_fee' => 'Rp' . number_format((int) $sale->platform_fee, 0, ',', '.'),
                        'platform_fee_raw' => (int) $sale->platform_fee,

                        'net_total' => 'Rp' . number_format((int) $sale->net_total, 0, ',', '.'),
                        'net_total_raw' => (int) $sale->net_total,

                        'status' => $sale->status,
                        'note' => $sale->note,
                        'sale_date' => $sale->sale_date
                            ? Carbon::parse($sale->sale_date)->format('d M Y')
                            : null,
                        'sale_date_raw' => $sale->sale_date
                            ? Carbon::parse($sale->sale_date)->format('Y-m-d')
                            : null,
                    ];
                })->items(),

                'products' => $products,
                'channels' => $channels,

                'meta' => [
                    'current_page' => $sales->currentPage(),
                    'last_page' => $sales->lastPage(),
                    'from' => $sales->firstItem(),
                    'to' => $sales->lastItem(),
                    'total' => $sales->total(),
                ],
            ])
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
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
            'status' => ['nullable', 'string', 'max:255'],
            'note' => ['nullable', 'string'],
            'sale_date' => ['nullable', 'date'],
        ]);

        $product = Product::where('user_id', auth()->id())
            ->where('id', $data['product_id'])
            ->firstOrFail();

        if ($product->stock < $data['quantity']) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Stok produk tidak cukup.',
                ], 422);
            }

            return back()->withErrors([
                'stock' => 'Stok produk tidak cukup.',
            ])->withInput();
        }

        DB::transaction(function () use ($data, $product) {
            $sellingPrice = (int) $product->selling_price;
            $quantity = (int) $data['quantity'];
            $grossTotal = $sellingPrice * $quantity;
            $platformFee = (int) ($data['platform_fee'] ?? 0);
            $netTotal = $grossTotal - $platformFee;

            Sale::create([
                'user_id' => auth()->id(),
                'product_id' => $product->id,
                'channel' => $data['channel'],
                'quantity' => $quantity,
                'selling_price' => $sellingPrice,
                'gross_total' => $grossTotal,
                'platform_fee' => $platformFee,
                'net_total' => $netTotal,
                'status' => $data['status'] ?? 'Selesai',
                'note' => $data['note'] ?? null,
                'sale_date' => $data['sale_date'] ?? now()->toDateString(),
            ]);

            $product->decrement('stock', $quantity);
        });

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Penjualan berhasil dicatat.',
            ]);
        }

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
            'status' => ['nullable', 'string', 'max:255'],
            'note' => ['nullable', 'string'],
            'sale_date' => ['nullable', 'date'],
        ]);

        $oldProduct = $sale->product;

        $newProduct = Product::where('user_id', auth()->id())
            ->where('id', $data['product_id'])
            ->firstOrFail();

        DB::beginTransaction();

        try {
            if ($oldProduct && $oldProduct->user_id === auth()->id()) {
                $oldProduct->increment('stock', $sale->quantity);
            }

            $newProduct->refresh();

            if ($newProduct->stock < $data['quantity']) {
                if ($oldProduct && $oldProduct->user_id === auth()->id()) {
                    $oldProduct->decrement('stock', $sale->quantity);
                }

                DB::rollBack();

                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Stok produk tidak cukup untuk perubahan penjualan.',
                    ], 422);
                }

                return back()->withErrors([
                    'stock' => 'Stok produk tidak cukup untuk perubahan penjualan.',
                ])->withInput();
            }

            $sellingPrice = (int) $newProduct->selling_price;
            $quantity = (int) $data['quantity'];
            $grossTotal = $sellingPrice * $quantity;
            $platformFee = (int) ($data['platform_fee'] ?? 0);
            $netTotal = $grossTotal - $platformFee;

            $sale->update([
                'product_id' => $newProduct->id,
                'channel' => $data['channel'],
                'quantity' => $quantity,
                'selling_price' => $sellingPrice,
                'gross_total' => $grossTotal,
                'platform_fee' => $platformFee,
                'net_total' => $netTotal,
                'status' => $data['status'] ?? 'Selesai',
                'note' => $data['note'] ?? null,
                'sale_date' => $data['sale_date'] ?? now()->toDateString(),
            ]);

            $newProduct->decrement('stock', $quantity);

            DB::commit();
        } catch (\Throwable $exception) {
            DB::rollBack();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal memperbarui penjualan.',
                    'error' => $exception->getMessage(),
                ], 500);
            }

            return back()->withErrors('Gagal memperbarui penjualan.')->withInput();
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Penjualan berhasil diperbarui.',
            ]);
        }

        return redirect('/sales')->with('success', 'Penjualan berhasil diperbarui.');
    }

    public function destroy(Request $request, Sale $sale)
    {
        abort_if($sale->user_id !== auth()->id(), 403);

        DB::transaction(function () use ($sale) {
            $product = $sale->product;

            if ($product && $product->user_id === auth()->id()) {
                $product->increment('stock', $sale->quantity);
            }

            $sale->delete();
        });

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Penjualan berhasil dihapus dan stok dikembalikan.',
            ]);
        }

        return redirect('/sales')->with('success', 'Penjualan berhasil dihapus dan stok dikembalikan.');
    }

    public function export(Request $request)
    {
        $userId = auth()->id();

        $startDate = $request->query('start_date')
            ? Carbon::parse($request->query('start_date'))->startOfDay()
            : now()->startOfMonth();

        $endDate = $request->query('end_date')
            ? Carbon::parse($request->query('end_date'))->endOfDay()
            : now()->endOfMonth();

        $sales = Sale::with('product')
            ->where('user_id', $userId)
            ->latest()
            ->get()
            ->filter(function ($sale) use ($startDate, $endDate) {
                $saleDate = $sale->sale_date
                    ? Carbon::parse($sale->sale_date)
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

            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));

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
                    ? Carbon::parse($sale->sale_date)
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