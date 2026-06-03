@extends('layouts.dashboard', [
    'title' => 'Produk & Stok - DagangFlow',
    'pageTitle' => 'Produk & Stok',
    'subtitle' => 'Kelola produk, harga, modal, dan stok usaha kamu'
])

@section('actions')
    <button class="hidden sm:block px-4 py-2 rounded-xl border border-slate-200 text-sm font-medium hover:bg-slate-50">
        Export Produk
    </button>

    <button
        type="button"
        onclick="window.dispatchEvent(new CustomEvent('open-create-product-modal'))"
        class="px-4 py-2 rounded-xl bg-emerald-500 text-white text-sm font-semibold hover:bg-emerald-600"
    >
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
                <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-700 flex items-center justify-center mb-5">
                    <x-lucide-package class="w-6 h-6" />
                </div>

                <p class="text-sm text-slate-500">Total Produk</p>
                <h3 class="text-3xl font-bold mt-3">
                    {{ number_format($totalProducts ?? 0, 0, ',', '.') }}
                </h3>
                <p class="text-sm text-slate-500 mt-2">Produk tercatat</p>
            </div>

            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center mb-5">
                    <x-lucide-check-circle class="w-6 h-6" />
                </div>

                <p class="text-sm text-slate-500">Produk Aktif</p>
                <h3 class="text-3xl font-bold mt-3 text-emerald-600">
                    {{ number_format($activeProducts ?? 0, 0, ',', '.') }}
                </h3>
                <p class="text-sm text-slate-500 mt-2">Stok tersedia</p>
            </div>

            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
                <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center mb-5">
                    <x-lucide-triangle-alert class="w-6 h-6" />
                </div>

                <p class="text-sm text-slate-500">Stok Rendah</p>
                <h3 class="text-3xl font-bold mt-3 text-amber-600">
                    {{ number_format($lowStockProducts ?? 0, 0, ',', '.') }}
                </h3>
                <p class="text-sm text-slate-500 mt-2">Perlu restock</p>
            </div>

            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
                <div class="w-12 h-12 rounded-2xl bg-red-50 text-red-600 flex items-center justify-center mb-5">
                    <x-lucide-circle-x class="w-6 h-6" />
                </div>

                <p class="text-sm text-slate-500">Stok Habis</p>
                <h3 class="text-3xl font-bold mt-3 text-red-600">
                    {{ number_format($outOfStockProducts ?? 0, 0, ',', '.') }}
                </h3>
                <p class="text-sm text-slate-500 mt-2">Tidak bisa dijual</p>
            </div>
        </div>

        <!-- Vue Product Table Full Width -->
        <div
            id="owner-products-table"
            data-data-url="{{ route('products.data') }}"
            data-create-url="{{ url('/products') }}"
            data-edit-base-url="{{ url('/products') }}"
            data-csrf-token="{{ csrf_token() }}"
        ></div>

        <!-- Low Stock Alert Below Table -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
            <div class="flex items-start justify-between gap-4 mb-6">
                <div>
                    <h3 class="text-lg font-bold">Perlu Restock</h3>
                    <p class="text-sm text-slate-500 mt-1">Produk dengan stok rendah atau habis</p>
                </div>

                <div class="w-11 h-11 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center shrink-0">
                    <x-lucide-triangle-alert class="w-5 h-5" />
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
                @forelse($restockProducts as $product)
                    <div class="flex items-center justify-between p-4 rounded-2xl {{ $product->stock <= 0 ? 'bg-red-50 border-red-100' : 'bg-amber-50 border-amber-100' }} border">
                        <div>
                            <p class="font-semibold">{{ $product->name }}</p>
                            <p class="text-sm text-slate-500 mt-1">Sisa {{ $product->stock }} pcs</p>
                        </div>

                        @if($product->stock <= 0)
                            <span class="px-3 py-1 rounded-full bg-red-100 text-red-700 text-xs font-bold">Habis</span>
                        @else
                            <span class="px-3 py-1 rounded-full bg-amber-100 text-amber-700 text-xs font-bold">Rendah</span>
                        @endif
                    </div>
                @empty
                    <div class="md:col-span-2 xl:col-span-3 p-5 rounded-2xl bg-emerald-50 border border-emerald-100">
                        <p class="font-semibold text-emerald-700">Stok masih aman</p>
                        <p class="text-sm text-slate-500 mt-1">Belum ada produk yang perlu restock.</p>
                    </div>
                @endforelse
            </div>
        </div>

    </div>
@endsection