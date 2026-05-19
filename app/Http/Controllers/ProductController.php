<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $userId = auth()->id();

        $allProducts = Product::where('user_id', $userId)
            ->latest()
            ->get();

        $productQuery = Product::where('user_id', $userId);

        if ($request->filled('search')) {
            $search = $request->query('search');

            $productQuery->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('category', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $productQuery->where('category', $request->query('category'));
        }

        if ($request->filled('stock_status')) {
            if ($request->query('stock_status') === 'safe') {
                $productQuery->whereColumn('stock', '>', 'low_stock_limit');
            }

            if ($request->query('stock_status') === 'low') {
                $productQuery->where('stock', '>', 0)
                    ->whereColumn('stock', '<=', 'low_stock_limit');
            }

            if ($request->query('stock_status') === 'empty') {
                $productQuery->where('stock', '<=', 0);
            }
        }

        if ($request->query('sort') === 'stock_asc') {
            $productQuery->orderBy('stock');
        } elseif ($request->query('sort') === 'price_desc') {
            $productQuery->orderByDesc('selling_price');
        } elseif ($request->query('sort') === 'name_asc') {
            $productQuery->orderBy('name');
        } else {
            $productQuery->latest();
        }

        $products = $productQuery->get();

        $totalProducts = $allProducts->count();

        $activeProducts = $allProducts
            ->where('stock', '>', 0)
            ->count();

        $lowStockProducts = $allProducts
            ->filter(function ($product) {
                return $product->stock > 0 && $product->stock <= $product->low_stock_limit;
            })
            ->count();

        $outOfStockProducts = $allProducts
            ->where('stock', '<=', 0)
            ->count();

        $availableCategories = $allProducts
            ->pluck('category')
            ->filter()
            ->unique()
            ->sort()
            ->values();

        $restockProducts = $allProducts
            ->filter(fn ($product) => $product->stock <= $product->low_stock_limit)
            ->sortBy('stock');

        $activeFilters = [
            'search' => $request->query('search'),
            'category' => $request->query('category'),
            'stock_status' => $request->query('stock_status'),
            'sort' => $request->query('sort', 'latest'),
        ];

        return view('products', compact(
            'products',
            'totalProducts',
            'activeProducts',
            'lowStockProducts',
            'outOfStockProducts',
            'availableCategories',
            'restockProducts',
            'activeFilters'
        ));
    }

    public function store(Request $request)
    {
        $request->merge([
            'selling_price' => preg_replace('/\D/', '', $request->selling_price),
            'cost_price' => preg_replace('/\D/', '', $request->cost_price),
        ]);

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

        $request->merge([
            'selling_price' => preg_replace('/\D/', '', $request->selling_price),
            'cost_price' => preg_replace('/\D/', '', $request->cost_price),
        ]);

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