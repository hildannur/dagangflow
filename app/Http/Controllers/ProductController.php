<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil diperbarui.',
            ]);
        }

        return redirect('/products')->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Request $request, Product $product)
    {
        abort_if($product->user_id !== auth()->id(), 403);

        try {
            DB::transaction(function () use ($product) {
                Sale::where('user_id', auth()->id())
                    ->where('product_id', $product->id)
                    ->update([
                        'product_id' => null,
                    ]);

                $product->delete();
            });
        } catch (QueryException $exception) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Produk belum bisa dihapus karena masih terhubung dengan data penjualan. Jalankan migration agar sales.product_id boleh null.',
                    'error' => $exception->getMessage(),
                ], 422);
            }

            return redirect('/products')
                ->withErrors('Produk belum bisa dihapus karena masih terhubung dengan data penjualan.');
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil dihapus.',
            ]);
        }

        return redirect('/products')->with('success', 'Produk berhasil dihapus.');
    }

    public function data(Request $request)
    {
        $search = $request->query('search');
        $category = $request->query('category');
        $stockStatus = $request->query('stock_status');

        $query = Product::where('user_id', auth()->id())
            ->when($search, function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('name', 'like', "%{$search}%")
                        ->orWhere('category', 'like', "%{$search}%");
                });
            })
            ->when($category, function ($query) use ($category) {
                $query->where('category', $category);
            });

        if ($stockStatus === 'out') {
            $query->where('stock', '<=', 0);
        } elseif ($stockStatus === 'low') {
            $query->where('stock', '>', 0)
                ->whereColumn('stock', '<=', 'low_stock_limit');
        } elseif ($stockStatus === 'safe') {
            $query->whereColumn('stock', '>', 'low_stock_limit');
        }

        $products = $query
            ->latest()
            ->paginate(10);

        $categories = Product::where('user_id', auth()->id())
            ->whereNotNull('category')
            ->where('category', '!=', '')
            ->select('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category')
            ->values();

        return response()
            ->json([
                'data' => $products->through(function ($product) {
                    $stockStatus = 'safe';

                    if ((int) $product->stock <= 0) {
                        $stockStatus = 'out';
                    } elseif ((int) $product->stock <= (int) $product->low_stock_limit) {
                        $stockStatus = 'low';
                    }

                    return [
                        'id' => $product->id,
                        'name' => $product->name,
                        'category' => $product->category,

                        'stock' => number_format((int) $product->stock, 0, ',', '.'),
                        'minimum_stock' => number_format((int) $product->low_stock_limit, 0, ',', '.'),
                        'low_stock_limit' => number_format((int) $product->low_stock_limit, 0, ',', '.'),

                        'selling_price' => 'Rp' . number_format((int) $product->selling_price, 0, ',', '.'),
                        'cost_price' => 'Rp' . number_format((int) $product->cost_price, 0, ',', '.'),

                        'selling_price_raw' => (int) $product->selling_price,
                        'cost_price_raw' => (int) $product->cost_price,
                        'stock_raw' => (int) $product->stock,
                        'low_stock_limit_raw' => (int) $product->low_stock_limit,

                        'stock_status' => $stockStatus,

                        'created_at' => $product->created_at
                            ? $product->created_at->format('d M Y')
                            : null,
                    ];
                })->items(),

                'categories' => $categories,

                'meta' => [
                    'current_page' => $products->currentPage(),
                    'last_page' => $products->lastPage(),
                    'from' => $products->firstItem(),
                    'to' => $products->lastItem(),
                    'total' => $products->total(),
                ],
            ])
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
    }
}