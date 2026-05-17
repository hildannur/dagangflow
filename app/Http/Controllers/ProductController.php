<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::where('user_id', auth()->id())
            ->latest()
            ->get();

        $totalProducts = $products->count();
        $activeProducts = $products->where('stock', '>', 0)->count();
        $lowStockProducts = $products->filter(function ($product) {
            return $product->stock > 0 && $product->stock <= $product->low_stock_limit;
        })->count();
        $outOfStockProducts = $products->where('stock', '<=', 0)->count();

        return view('products', compact(
            'products',
            'totalProducts',
            'activeProducts',
            'lowStockProducts',
            'outOfStockProducts'
        ));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:255'],
            'selling_price' => ['required', 'integer', 'min:0'],
            'cost_price' => ['nullable', 'integer', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'low_stock_limit' => ['nullable', 'integer', 'min:0'],
        ]);

        Product::create([
            'user_id' => auth()->id(),
            'name' => $data['name'],
            'category' => $data['category'] ?? null,
            'selling_price' => $data['selling_price'],
            'cost_price' => $data['cost_price'] ?? 0,
            'stock' => $data['stock'],
            'low_stock_limit' => $data['low_stock_limit'] ?? 5,
        ]);

        return redirect('/products')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function update(Request $request, Product $product)
    {
        abort_if($product->user_id !== auth()->id(), 403);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:255'],
            'selling_price' => ['required', 'integer', 'min:0'],
            'cost_price' => ['nullable', 'integer', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'low_stock_limit' => ['nullable', 'integer', 'min:0'],
        ]);

        $product->update([
            'name' => $data['name'],
            'category' => $data['category'] ?? null,
            'selling_price' => $data['selling_price'],
            'cost_price' => $data['cost_price'] ?? 0,
            'stock' => $data['stock'],
            'low_stock_limit' => $data['low_stock_limit'] ?? 5,
        ]);

        return redirect('/products')->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product)
    {
        abort_if($product->user_id !== auth()->id(), 403);

        $product->delete();

        return redirect('/products')->with('success', 'Produk berhasil dihapus.');
    }
}