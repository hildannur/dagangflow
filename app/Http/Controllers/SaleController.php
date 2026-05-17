<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    public function index()
    {
        $sales = Sale::with('product')
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        $products = Product::where('user_id', auth()->id())
            ->where('stock', '>', 0)
            ->orderBy('name')
            ->get();

        $todayRevenue = $sales
            ->where('sale_date', now()->toDateString())
            ->sum('gross_total');

        $monthRevenue = $sales
            ->filter(fn ($sale) => $sale->created_at->isSameMonth(now()))
            ->sum('gross_total');

        $platformFees = $sales
            ->filter(fn ($sale) => $sale->created_at->isSameMonth(now()))
            ->sum('platform_fee');

        $netRevenue = $sales
            ->filter(fn ($sale) => $sale->created_at->isSameMonth(now()))
            ->sum('net_total');

        return view('sales', compact(
            'sales',
            'products',
            'todayRevenue',
            'monthRevenue',
            'platformFees',
            'netRevenue'
        ));
    }

    public function store(Request $request)
    {
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
}