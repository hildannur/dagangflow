@extends('layouts.dashboard', [
    'title' => 'Produk & Stok - DagangFlow',
    'pageTitle' => 'Produk & Stok',
    'subtitle' => 'Kelola produk, harga, modal, dan stok usaha kamu'
])

@section('actions')
    <button class="hidden sm:block px-4 py-2 rounded-xl border border-slate-200 text-sm font-medium hover:bg-slate-50">
        Export Produk
    </button>

    <button onclick="document.getElementById('quick-add-product').scrollIntoView({ behavior: 'smooth' })"
        class="px-4 py-2 rounded-xl bg-emerald-500 text-white text-sm font-semibold hover:bg-emerald-600">
        + Tambah Produk
    </button>
@endsection

@section('content')
    <div class="space-y-8">

        @if (session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-5 py-4 rounded-2xl text-sm font-semibold">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-5 py-4 rounded-2xl text-sm font-semibold">
                {{ $errors->first() }}
            </div>
        @endif

        <!-- Stats -->
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-5">
            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
                <p class="text-sm text-slate-500">Total Produk</p>
                <h3 class="text-3xl font-bold mt-3">{{ $totalProducts }}</h3>
                <p class="text-sm text-slate-500 mt-2">Produk tercatat</p>
            </div>

            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
                <p class="text-sm text-slate-500">Produk Aktif</p>
                <h3 class="text-3xl font-bold mt-3 text-emerald-600">{{ $activeProducts }}</h3>
                <p class="text-sm text-slate-500 mt-2">Stok tersedia</p>
            </div>

            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
                <p class="text-sm text-slate-500">Stok Rendah</p>
                <h3 class="text-3xl font-bold mt-3 text-amber-600">{{ $lowStockProducts }}</h3>
                <p class="text-sm text-slate-500 mt-2">Perlu restock</p>
            </div>

            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
                <p class="text-sm text-slate-500">Stok Habis</p>
                <h3 class="text-3xl font-bold mt-3 text-red-600">{{ $outOfStockProducts }}</h3>
                <p class="text-sm text-slate-500 mt-2">Tidak bisa dijual</p>
            </div>
        </div>

        <!-- Main Grid -->
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

            <!-- Product Table -->
            <div class="xl:col-span-2 bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-slate-200 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                    <div>
                        <h3 class="text-lg font-bold">Daftar Produk</h3>
                        <p class="text-sm text-slate-500">Data produk tersimpan di database</p>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-slate-50 text-slate-500">
                            <tr>
                                <th class="text-left px-6 py-4 font-medium">Produk</th>
                                <th class="text-left px-6 py-4 font-medium">Kategori</th>
                                <th class="text-left px-6 py-4 font-medium">Harga Jual</th>
                                <th class="text-left px-6 py-4 font-medium">Modal</th>
                                <th class="text-left px-6 py-4 font-medium">Stok</th>
                                <th class="text-left px-6 py-4 font-medium">Status</th>
                                <th class="text-right px-6 py-4 font-medium">Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100">
                            @forelse($products as $product)
                                <tr class="hover:bg-slate-50/70">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center font-semibold text-sm border border-slate-200">
                                                {{ $loop->iteration }}
                                            </div>

                                            <div>
                                                <p class="font-semibold text-slate-900">{{ $product->name }}</p>
                                                <p class="text-xs text-slate-500">
                                                    Limit stok rendah: {{ $product->low_stock_limit }} pcs
                                                </p>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="px-6 py-4 text-slate-600">
                                        {{ $product->category ?? '-' }}
                                    </td>

                                    <td class="px-6 py-4 font-semibold">
                                        Rp{{ number_format($product->selling_price, 0, ',', '.') }}
                                    </td>

                                    <td class="px-6 py-4 text-slate-600">
                                        Rp{{ number_format($product->cost_price, 0, ',', '.') }}
                                    </td>

                                    <td class="px-6 py-4">
                                        <span class="font-semibold">{{ $product->stock }}</span>
                                        <span class="text-slate-500">pcs</span>
                                    </td>

                                    <td class="px-6 py-4">
                                        @if($product->stock_status === 'Aman')
                                            <span class="px-3 py-1 rounded-full bg-emerald-100 text-emerald-700 text-xs font-semibold">
                                                Aman
                                            </span>
                                        @elseif($product->stock_status === 'Rendah')
                                            <span class="px-3 py-1 rounded-full bg-amber-100 text-amber-700 text-xs font-semibold">
                                                Stok rendah
                                            </span>
                                        @else
                                            <span class="px-3 py-1 rounded-full bg-red-100 text-red-700 text-xs font-semibold">
                                                Habis
                                            </span>
                                        @endif
                                    </td>

                                    <td class="px-6 py-4 text-right">
                                        <div class="flex justify-end gap-3">
                                            <button
                                                onclick="openEditProductModal(
                                                    '{{ $product->id }}',
                                                    '{{ addslashes($product->name) }}',
                                                    '{{ addslashes($product->category ?? '') }}',
                                                    '{{ $product->selling_price }}',
                                                    '{{ $product->cost_price }}',
                                                    '{{ $product->stock }}',
                                                    '{{ $product->low_stock_limit }}'
                                                )"
                                                class="text-sm font-semibold text-emerald-600 hover:text-emerald-700"
                                            >
                                                Edit
                                            </button>

                                            <form action="/products/{{ $product->id }}" method="POST"
                                                onsubmit="return confirm('Yakin ingin menghapus produk ini?')">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit" class="text-sm font-semibold text-red-600 hover:text-red-700">
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-14 text-center">
                                        <div class="max-w-sm mx-auto">
                                            <div class="w-14 h-14 rounded-2xl bg-slate-100 flex items-center justify-center mx-auto text-2xl">
                                                📦
                                            </div>

                                            <h3 class="font-bold text-slate-900 mt-4">Belum ada produk</h3>

                                            <p class="text-sm text-slate-500 mt-2">
                                                Tambahkan produk pertama kamu melalui form di sebelah kanan.
                                            </p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Right Panel -->
            <div class="space-y-6">

                <!-- Add Product Form -->
                <div id="quick-add-product" class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
                    <h3 class="text-lg font-bold">Tambah Produk</h3>
                    <p class="text-sm text-slate-500 mt-1">Produk akan tersimpan ke database</p>

                    <form action="/products" method="POST" class="mt-6 space-y-4">
                        @csrf

                        <div>
                            <label class="text-sm font-medium text-slate-700">Nama produk</label>
                            <input
                                type="text"
                                name="name"
                                value="{{ old('name') }}"
                                placeholder="Contoh: Kopi Susu 250ml"
                                class="mt-2 w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500"
                                required
                            >
                        </div>

                        <div>
                            <label class="text-sm font-medium text-slate-700">Kategori</label>
                            <input
                                type="text"
                                name="category"
                                value="{{ old('category') }}"
                                placeholder="Contoh: Minuman"
                                class="mt-2 w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500"
                            >
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="text-sm font-medium text-slate-700">Harga jual</label>
                                <input
                                    type="text"
                                    inputmode="numeric"
                                    name="selling_price"
                                    value="{{ old('selling_price') }}"
                                    placeholder="15.000"
                                    class="mt-2 w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 rupiah-input"
                                    required
                                >
                            </div>

                            <div>
                                <label class="text-sm font-medium text-slate-700">Modal</label>
                                <input
                                    type="text"
                                    inputmode="numeric"
                                    name="cost_price"
                                    value="{{ old('cost_price') }}"
                                    placeholder="8.000"
                                    class="mt-2 w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 rupiah-input"
                                >
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="text-sm font-medium text-slate-700">Stok</label>
                                <input
                                    type="number"
                                    name="stock"
                                    value="{{ old('stock') }}"
                                    placeholder="20"
                                    class="mt-2 w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500"
                                    required
                                >
                            </div>

                            <div>
                                <label class="text-sm font-medium text-slate-700">Limit rendah</label>
                                <input
                                    type="number"
                                    name="low_stock_limit"
                                    value="{{ old('low_stock_limit', 5) }}"
                                    placeholder="5"
                                    class="mt-2 w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500"
                                >
                            </div>
                        </div>

                        <button class="w-full py-3 rounded-xl bg-emerald-500 text-white text-sm font-semibold hover:bg-emerald-600">
                            Simpan Produk
                        </button>
                    </form>
                </div>

                <!-- Low Stock Alert -->
                <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
                    <h3 class="text-lg font-bold">Perlu Restock</h3>
                    <p class="text-sm text-slate-500 mb-6">Produk dengan stok rendah atau habis</p>

                    <div class="space-y-4">
                        @php
                            $restockProducts = $products->filter(fn($product) => $product->stock <= $product->low_stock_limit);
                        @endphp

                        @forelse($restockProducts as $product)
                            <div class="flex items-center justify-between p-4 rounded-xl {{ $product->stock <= 0 ? 'bg-red-50 border-red-100' : 'bg-amber-50 border-amber-100' }} border">
                                <div>
                                    <p class="font-semibold">{{ $product->name }}</p>
                                    <p class="text-sm text-slate-500">Sisa {{ $product->stock }} pcs</p>
                                </div>

                                @if($product->stock <= 0)
                                    <span class="text-xs font-bold text-red-600">Habis</span>
                                @else
                                    <span class="text-xs font-bold text-amber-600">Rendah</span>
                                @endif
                            </div>
                        @empty
                            <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-100">
                                <p class="font-semibold text-emerald-700">Stok masih aman</p>
                                <p class="text-sm text-slate-500 mt-1">Belum ada produk yang perlu restock.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

            </div>
        </div>

        <!-- Edit Product Modal -->
        <div id="editProductModal" class="fixed inset-0 bg-slate-900/50 hidden items-center justify-center z-50 px-4">
            <div class="bg-white rounded-2xl p-6 w-full max-w-lg shadow-xl">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-lg font-bold">Edit Produk</h3>
                        <p class="text-sm text-slate-500">Perbarui data produk</p>
                    </div>

                    <button onclick="closeEditProductModal()" class="w-9 h-9 rounded-xl bg-slate-100 hover:bg-slate-200">
                        ✕
                    </button>
                </div>

                <form id="editProductForm" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="text-sm font-medium text-slate-700">Nama produk</label>
                        <input
                            id="edit_name"
                            type="text"
                            name="name"
                            class="mt-2 w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500"
                            required
                        >
                    </div>

                    <div>
                        <label class="text-sm font-medium text-slate-700">Kategori</label>
                        <input
                            id="edit_category"
                            type="text"
                            name="category"
                            class="mt-2 w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500"
                        >
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="text-sm font-medium text-slate-700">Harga jual</label>
                            <input
                                id="edit_selling_price"
                                type="text"
                                inputmode="numeric"
                                name="selling_price"
                                class="mt-2 w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 rupiah-input"
                                required
                            >
                        </div>

                        <div>
                            <label class="text-sm font-medium text-slate-700">Modal</label>
                            <input
                                id="edit_cost_price"
                                type="text"
                                inputmode="numeric"
                                name="cost_price"
                                class="mt-2 w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 rupiah-input"
                            >
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="text-sm font-medium text-slate-700">Stok</label>
                            <input
                                id="edit_stock"
                                type="number"
                                name="stock"
                                class="mt-2 w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500"
                                required
                            >
                        </div>

                        <div>
                            <label class="text-sm font-medium text-slate-700">Limit rendah</label>
                            <input
                                id="edit_low_stock_limit"
                                type="number"
                                name="low_stock_limit"
                                class="mt-2 w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500"
                            >
                        </div>
                    </div>

                    <button class="w-full py-3 rounded-xl bg-emerald-500 text-white text-sm font-semibold hover:bg-emerald-600">
                        Simpan Perubahan
                    </button>
                </form>
            </div>
        </div>

        <script>
            function formatRibuanProduct(value) {
                const number = String(value || '').replace(/\D/g, '');

                if (!number) {
                    return '';
                }

                return number.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            }

            function openEditProductModal(id, name, category, sellingPrice, costPrice, stock, lowStockLimit) {
                const modal = document.getElementById('editProductModal');
                const form = document.getElementById('editProductForm');

                form.action = `/products/${id}`;

                document.getElementById('edit_name').value = name;
                document.getElementById('edit_category').value = category || '';
                document.getElementById('edit_selling_price').value = formatRibuanProduct(sellingPrice);
                document.getElementById('edit_cost_price').value = formatRibuanProduct(costPrice);
                document.getElementById('edit_stock').value = stock;
                document.getElementById('edit_low_stock_limit').value = lowStockLimit;

                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }

            function closeEditProductModal() {
                const modal = document.getElementById('editProductModal');

                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }
        </script>

    </div>
@endsection