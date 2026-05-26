@extends('layouts.dashboard', [
    'title' => 'Penjualan - DagangFlow',
    'pageTitle' => 'Penjualan',
    'subtitle' => 'Catat transaksi dari offline, WhatsApp, marketplace, dan food delivery'
])

@section('actions')
    <a href="{{ route('sales.export', ['start_date' => $selectedPeriod['start_date'], 'end_date' => $selectedPeriod['end_date']]) }}"
        class="hidden sm:block px-4 py-2 rounded-xl border border-slate-200 text-sm font-medium hover:bg-slate-50">
        Export Transaksi
    </a>

    <button onclick="document.getElementById('quick-import-sale').scrollIntoView({ behavior: 'smooth' })"
        class="hidden sm:block px-4 py-2 rounded-xl border border-slate-200 text-sm font-medium hover:bg-slate-50">
        Import CSV
    </button>

    <button onclick="document.getElementById('quick-add-sale').scrollIntoView({ behavior: 'smooth' })"
        class="px-4 py-2 rounded-xl bg-emerald-500 text-white text-sm font-semibold hover:bg-emerald-600">
        + Tambah Penjualan
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

        @if (session('importErrors'))
            <div class="bg-amber-50 border border-amber-200 text-amber-800 px-5 py-4 rounded-2xl text-sm">
                <p class="font-bold">Sebagian data gagal diimport:</p>

                <ul class="list-disc pl-5 mt-2 space-y-1">
                    @foreach(session('importErrors') as $importError)
                        <li>{{ $importError }}</li>
                    @endforeach
                </ul>

                <p class="text-xs text-amber-700 mt-3">
                    Maksimal 10 error pertama ditampilkan. Periksa kembali nama produk, stok, dan format CSV.
                </p>
            </div>
        @endif

        <!-- Period Filter -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm">
            <div class="flex flex-col xl:flex-row xl:items-end xl:justify-between gap-5">
                <div>
                    <h3 class="text-lg font-bold">Filter Penjualan</h3>
                    <p class="text-sm text-slate-500 mt-1">
                        Data ditampilkan dari
                        {{ \Carbon\Carbon::parse($selectedPeriod['start_date'])->format('d M Y') }}
                        sampai
                        {{ \Carbon\Carbon::parse($selectedPeriod['end_date'])->format('d M Y') }}
                    </p>
                </div>

                <form action="/sales" method="GET" class="flex flex-col sm:flex-row sm:items-end gap-3">
                    <div>
                        <label class="text-xs font-semibold text-slate-500">Tanggal mulai</label>
                        <input
                            type="date"
                            name="start_date"
                            value="{{ $selectedPeriod['start_date'] }}"
                            class="mt-2 px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500"
                        >
                    </div>

                    <div>
                        <label class="text-xs font-semibold text-slate-500">Tanggal akhir</label>
                        <input
                            type="date"
                            name="end_date"
                            value="{{ $selectedPeriod['end_date'] }}"
                            class="mt-2 px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500"
                        >
                    </div>

                    <button class="px-5 py-3 rounded-xl bg-emerald-500 text-white text-sm font-semibold hover:bg-emerald-600">
                        Terapkan Filter
                    </button>

                    <a href="/sales" class="px-5 py-3 rounded-xl border border-slate-200 text-sm font-semibold hover:bg-slate-50 text-center">
                        Reset
                    </a>
                </form>
            </div>

            <div class="flex flex-wrap items-center gap-3 mt-5">
                <a href="/sales?start_date={{ now()->toDateString() }}&end_date={{ now()->toDateString() }}"
                    class="px-4 py-2 rounded-xl border border-slate-200 text-sm font-medium hover:bg-slate-50">
                    Hari Ini
                </a>

                <a href="/sales?start_date={{ now()->subDays(6)->toDateString() }}&end_date={{ now()->toDateString() }}"
                    class="px-4 py-2 rounded-xl border border-slate-200 text-sm font-medium hover:bg-slate-50">
                    7 Hari
                </a>

                <a href="/sales?start_date={{ now()->startOfMonth()->toDateString() }}&end_date={{ now()->endOfMonth()->toDateString() }}"
                    class="px-4 py-2 rounded-xl bg-emerald-50 text-emerald-700 border border-emerald-100 text-sm font-semibold hover:bg-emerald-100">
                    Bulan Ini
                </a>

                <a href="/sales?start_date={{ now()->subMonth()->startOfMonth()->toDateString() }}&end_date={{ now()->subMonth()->endOfMonth()->toDateString() }}"
                    class="px-4 py-2 rounded-xl border border-slate-200 text-sm font-medium hover:bg-slate-50">
                    Bulan Lalu
                </a>
            </div>
        </div>

        @php
            $periodRevenueTrend = $periodRevenueTrend ?? ['status' => 'flat', 'percent' => 0];
            $totalRevenueTrend = $totalRevenueTrend ?? ['status' => 'flat', 'percent' => 0];
            $platformFeeTrend = $platformFeeTrend ?? ['status' => 'flat', 'percent' => 0];
            $netRevenueTrend = $netRevenueTrend ?? ['status' => 'flat', 'percent' => 0];
        @endphp

        <!-- Stats -->
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-5">
            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
                <p class="text-sm text-slate-500">Omzet Periode Ini</p>

                <div class="flex items-center gap-4 mt-4">
                    <div class="w-16 h-16 rounded-full bg-emerald-500 text-white flex items-center justify-center shrink-0 shadow-sm">
                        <x-lucide-trending-up class="w-9 h-9" />
                    </div>

                    <h3 class="text-3xl font-bold leading-tight">
                        Rp{{ number_format($todayRevenue, 0, ',', '.') }}
                    </h3>
                </div>

                <div class="mt-5 flex items-center gap-2 text-sm font-semibold">
                    <span class="inline-flex items-center gap-1
                        {{ $periodRevenueTrend['status'] === 'up' ? 'text-emerald-600' : '' }}
                        {{ $periodRevenueTrend['status'] === 'flat' ? 'text-slate-500' : '' }}
                        {{ $periodRevenueTrend['status'] === 'down' ? 'text-red-600' : '' }}
                    ">
                        @if($periodRevenueTrend['status'] === 'up')
                            <x-lucide-trending-up class="w-4 h-4" />
                            {{ $periodRevenueTrend['percent'] }}%
                        @elseif($periodRevenueTrend['status'] === 'down')
                            <x-lucide-trending-down class="w-4 h-4" />
                            {{ $periodRevenueTrend['percent'] }}%
                        @else
                            <x-lucide-minus class="w-4 h-4" />
                            Stabil
                        @endif
                    </span>

                    <span class="text-slate-500 font-medium">dari periode sebelumnya</span>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
                <p class="text-sm text-slate-500">Total Omzet</p>

                <div class="flex items-center gap-4 mt-4">
                    <div class="w-16 h-16 rounded-full bg-blue-500 text-white flex items-center justify-center shrink-0 shadow-sm">
                        <x-lucide-circle-dollar-sign class="w-9 h-9" />
                    </div>

                    <h3 class="text-3xl font-bold leading-tight">
                        Rp{{ number_format($monthRevenue, 0, ',', '.') }}
                    </h3>
                </div>

                <div class="mt-5 flex items-center gap-2 text-sm font-semibold">
                    <span class="inline-flex items-center gap-1
                        {{ $totalRevenueTrend['status'] === 'up' ? 'text-emerald-600' : '' }}
                        {{ $totalRevenueTrend['status'] === 'flat' ? 'text-slate-500' : '' }}
                        {{ $totalRevenueTrend['status'] === 'down' ? 'text-red-600' : '' }}
                    ">
                        @if($totalRevenueTrend['status'] === 'up')
                            <x-lucide-trending-up class="w-4 h-4" />
                            {{ $totalRevenueTrend['percent'] }}%
                        @elseif($totalRevenueTrend['status'] === 'down')
                            <x-lucide-trending-down class="w-4 h-4" />
                            {{ $totalRevenueTrend['percent'] }}%
                        @else
                            <x-lucide-minus class="w-4 h-4" />
                            Stabil
                        @endif
                    </span>

                    <span class="text-slate-500 font-medium">dari periode sebelumnya</span>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
                <p class="text-sm text-slate-500">Biaya Platform</p>

                <div class="flex items-center gap-4 mt-4">
                    <div class="w-16 h-16 rounded-full bg-amber-500 text-white flex items-center justify-center shrink-0 shadow-sm">
                        <x-lucide-wallet class="w-9 h-9" />
                    </div>

                    <h3 class="text-3xl font-bold leading-tight text-amber-600">
                        Rp{{ number_format($platformFees, 0, ',', '.') }}
                    </h3>
                </div>

                <div class="mt-5 flex items-center gap-2 text-sm font-semibold">
                    <span class="inline-flex items-center gap-1
                        {{ $platformFeeTrend['status'] === 'up' ? 'text-red-600' : '' }}
                        {{ $platformFeeTrend['status'] === 'flat' ? 'text-slate-500' : '' }}
                        {{ $platformFeeTrend['status'] === 'down' ? 'text-emerald-600' : '' }}
                    ">
                        @if($platformFeeTrend['status'] === 'up')
                            <x-lucide-trending-up class="w-4 h-4" />
                            {{ $platformFeeTrend['percent'] }}%
                        @elseif($platformFeeTrend['status'] === 'down')
                            <x-lucide-trending-down class="w-4 h-4" />
                            {{ $platformFeeTrend['percent'] }}%
                        @else
                            <x-lucide-minus class="w-4 h-4" />
                            Stabil
                        @endif
                    </span>

                    <span class="text-slate-500 font-medium">dari periode sebelumnya</span>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
                <p class="text-sm text-slate-500">Uang Bersih</p>

                <div class="flex items-center gap-4 mt-4">
                    <div class="w-16 h-16 rounded-full bg-emerald-500 text-white flex items-center justify-center shrink-0 shadow-sm">
                        <x-lucide-hand-coins class="w-9 h-9" />
                    </div>

                    <h3 class="text-3xl font-bold leading-tight text-emerald-600">
                        Rp{{ number_format($netRevenue, 0, ',', '.') }}
                    </h3>
                </div>

                <div class="mt-5 flex items-center gap-2 text-sm font-semibold">
                    <span class="inline-flex items-center gap-1
                        {{ $netRevenueTrend['status'] === 'up' ? 'text-emerald-600' : '' }}
                        {{ $netRevenueTrend['status'] === 'flat' ? 'text-slate-500' : '' }}
                        {{ $netRevenueTrend['status'] === 'down' ? 'text-red-600' : '' }}
                    ">
                        @if($netRevenueTrend['status'] === 'up')
                            <x-lucide-trending-up class="w-4 h-4" />
                            {{ $netRevenueTrend['percent'] }}%
                        @elseif($netRevenueTrend['status'] === 'down')
                            <x-lucide-trending-down class="w-4 h-4" />
                            {{ $netRevenueTrend['percent'] }}%
                        @else
                            <x-lucide-minus class="w-4 h-4" />
                            Stabil
                        @endif
                    </span>

                    <span class="text-slate-500 font-medium">dari periode sebelumnya</span>
                </div>
            </div>
        </div>

        <!-- Main Grid -->
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

            <!-- Sales Table -->
            <div class="xl:col-span-2 bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-slate-200 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                    <div>
                        <h3 class="text-lg font-bold">Daftar Penjualan</h3>
                        <p class="text-sm text-slate-500">Data transaksi tersimpan di database</p>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-slate-50 text-slate-500">
                            <tr>
                                <th class="text-left px-6 py-4 font-medium">Transaksi</th>
                                <th class="text-left px-6 py-4 font-medium">Channel</th>
                                <th class="text-left px-6 py-4 font-medium">Produk</th>
                                <th class="text-left px-6 py-4 font-medium">Jumlah Terjual</th>
                                <th class="text-left px-6 py-4 font-medium">Total</th>
                                <th class="text-left px-6 py-4 font-medium">Potongan</th>
                                <th class="text-left px-6 py-4 font-medium">Bersih</th>
                                <th class="text-left px-6 py-4 font-medium">Status</th>
                                <th class="text-right px-6 py-4 font-medium">Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100">
                            @forelse($sales as $sale)
                                <tr class="hover:bg-slate-50/70">
                                    <td class="px-6 py-4">
                                        <p class="font-semibold text-slate-900">
                                            #TRX-{{ str_pad($sale->id, 4, '0', STR_PAD_LEFT) }}
                                        </p>
                                        <p class="text-xs text-slate-500">
                                            {{ $sale->sale_date ? \Carbon\Carbon::parse($sale->sale_date)->format('d M Y') : $sale->created_at->format('d M Y') }}
                                        </p>
                                    </td>

                                    <td class="px-6 py-4">
                                        @if($sale->channel === 'Offline')
                                            <span class="px-3 py-1 rounded-full bg-slate-100 text-slate-700 text-xs font-semibold">Offline</span>
                                        @elseif($sale->channel === 'Shopee')
                                            <span class="px-3 py-1 rounded-full bg-orange-100 text-orange-700 text-xs font-semibold">Shopee</span>
                                        @elseif($sale->channel === 'GoFood')
                                            <span class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-xs font-semibold">GoFood</span>
                                        @elseif($sale->channel === 'WhatsApp')
                                            <span class="px-3 py-1 rounded-full bg-emerald-100 text-emerald-700 text-xs font-semibold">WhatsApp</span>
                                        @elseif($sale->channel === 'Tokopedia')
                                            <span class="px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-xs font-semibold">Tokopedia</span>
                                        @else
                                            <span class="px-3 py-1 rounded-full bg-purple-100 text-purple-700 text-xs font-semibold">{{ $sale->channel }}</span>
                                        @endif
                                    </td>

                                    <td class="px-6 py-4">
                                        <p class="font-medium text-slate-900">
                                            {{ $sale->product->name ?? 'Produk terhapus' }}
                                        </p>
                                        <p class="text-xs text-slate-500">
                                            Harga: Rp{{ number_format($sale->selling_price, 0, ',', '.') }}
                                        </p>
                                    </td>

                                    <td class="px-6 py-4 font-semibold">
                                        {{ number_format($sale->quantity, 0, ',', '.') }}x
                                    </td>

                                    <td class="px-6 py-4 font-semibold">
                                        Rp{{ number_format($sale->gross_total, 0, ',', '.') }}
                                    </td>

                                    <td class="px-6 py-4 text-amber-600">
                                        Rp{{ number_format($sale->platform_fee, 0, ',', '.') }}
                                    </td>

                                    <td class="px-6 py-4 font-semibold text-emerald-600">
                                        Rp{{ number_format($sale->net_total, 0, ',', '.') }}
                                    </td>

                                    <td class="px-6 py-4">
                                        @if($sale->status === 'Selesai')
                                            <span class="px-3 py-1 rounded-full bg-emerald-100 text-emerald-700 text-xs font-semibold">Selesai</span>
                                        @elseif($sale->status === 'Diproses')
                                            <span class="px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-xs font-semibold">Diproses</span>
                                        @else
                                            <span class="px-3 py-1 rounded-full bg-amber-100 text-amber-700 text-xs font-semibold">{{ $sale->status }}</span>
                                        @endif
                                    </td>

                                    <td class="px-6 py-4 text-right">
                                        <div class="flex justify-end gap-3">
                                            <button
                                                type="button"
                                                onclick="openEditSaleModal(
                                                    '{{ $sale->id }}',
                                                    '{{ $sale->product_id }}',
                                                    '{{ $sale->channel }}',
                                                    '{{ $sale->quantity }}',
                                                    '{{ $sale->platform_fee }}',
                                                    '{{ $sale->status }}',
                                                    '{{ $sale->sale_date }}',
                                                    `{{ addslashes($sale->note ?? '') }}`
                                                )"
                                                class="text-sm font-semibold text-emerald-600 hover:text-emerald-700"
                                            >
                                                Edit
                                            </button>

                                            <form action="/sales/{{ $sale->id }}" method="POST"
                                                onsubmit="return confirm('Yakin ingin menghapus transaksi ini? Stok produk akan dikembalikan.')">
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
                                    <td colspan="9" class="px-6 py-14 text-center">
                                        <div class="max-w-md mx-auto">
                                            <div class="w-16 h-16 rounded-full bg-emerald-500 text-white flex items-center justify-center mx-auto shadow-sm">
                                                <x-lucide-receipt-text class="w-8 h-8" />
                                            </div>
                            
                                            <h3 class="font-bold text-slate-900 mt-5 text-lg">
                                                Belum ada transaksi penjualan
                                            </h3>
                            
                                            <p class="text-sm text-slate-500 mt-2 leading-relaxed">
                                                Catat penjualan pertama agar DagangFlow bisa menghitung omzet,
                                                biaya platform, uang bersih, dan stok produk secara otomatis.
                                            </p>
                            
                                            <div class="flex flex-col sm:flex-row justify-center gap-3 mt-5">
                                                @if($products->count() > 0)
                                                    <button
                                                        type="button"
                                                        onclick="document.getElementById('quick-add-sale').scrollIntoView({ behavior: 'smooth' })"
                                                        class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl bg-emerald-500 text-white text-sm font-semibold hover:bg-emerald-600"
                                                    >
                                                        <x-lucide-plus-circle class="w-4 h-4" />
                                                        Catat Penjualan
                                                    </button>
                                                @else
                                                    <a href="/products" class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl bg-emerald-500 text-white text-sm font-semibold hover:bg-emerald-600">
                                                        <x-lucide-package class="w-4 h-4" />
                                                        Tambah Produk Dulu
                                                    </a>
                                                @endif
                            
                                                <a href="/help" class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl border border-slate-200 text-sm font-semibold hover:bg-slate-50">
                                                    <x-lucide-circle-help class="w-4 h-4" />
                                                    Pelajari Alur
                                                </a>
                                            </div>
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

                <!-- Quick Add Sales -->
                <div id="quick-add-sale" class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
                    <h3 class="text-lg font-bold">Tambah Penjualan</h3>
                    <p class="text-sm text-slate-500 mt-1">Stok produk akan otomatis berkurang</p>

                    @if($products->count() > 0)
                        <form action="/sales" method="POST" class="mt-6 space-y-4">
                            @csrf

                            <div>
                                <label class="text-sm font-medium text-slate-700">Produk</label>
                                <select
                                    name="product_id"
                                    id="product_id"
                                    class="mt-2 w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500"
                                    required
                                >
                                    <option value="">Pilih produk</option>
                                    @foreach($products as $product)
                                        <option
                                            value="{{ $product->id }}"
                                            data-price="{{ $product->selling_price }}"
                                            data-stock="{{ $product->stock }}"
                                        >
                                            {{ $product->name }} - Rp{{ number_format($product->selling_price, 0, ',', '.') }} | Stok {{ number_format($product->stock, 0, ',', '.') }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="text-sm font-medium text-slate-700">Channel</label>
                                <select
                                    name="channel"
                                    class="mt-2 w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500"
                                    required
                                >
                                    <option value="Offline">Offline</option>
                                    <option value="WhatsApp">WhatsApp</option>
                                    <option value="Instagram">Instagram</option>
                                    <option value="Shopee">Shopee</option>
                                    <option value="Tokopedia">Tokopedia</option>
                                    <option value="GoFood">GoFood</option>
                                    <option value="GrabFood">GrabFood</option>
                                    <option value="TikTok Shop">TikTok Shop</option>
                                </select>
                            </div>

                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="text-sm font-medium text-slate-700">Jumlah Terjual</label>
                                    <input
                                        type="text"
                                        inputmode="numeric"
                                        name="quantity"
                                        id="quantity"
                                        value="1"
                                        placeholder="1"
                                        class="mt-2 w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 number-input"
                                        required
                                    >
                                    <p id="stock_info" class="text-xs text-slate-500 mt-1"></p>
                                </div>

                                <div>
                                    <label class="text-sm font-medium text-slate-700">Biaya platform</label>
                                    <input
                                        type="text"
                                        inputmode="numeric"
                                        name="platform_fee"
                                        id="platform_fee"
                                        value="0"
                                        placeholder="0"
                                        class="mt-2 w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 rupiah-input"
                                    >
                                </div>
                            </div>

                            <div>
                                <label class="text-sm font-medium text-slate-700">Status</label>
                                <select
                                    name="status"
                                    class="mt-2 w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500"
                                    required
                                >
                                    <option value="Selesai">Selesai</option>
                                    <option value="Diproses">Diproses</option>
                                    <option value="Belum Bayar">Belum Bayar</option>
                                </select>
                            </div>

                            <div>
                                <label class="text-sm font-medium text-slate-700">Tanggal penjualan</label>
                                <input
                                    type="date"
                                    name="sale_date"
                                    value="{{ now()->toDateString() }}"
                                    class="mt-2 w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500"
                                >
                            </div>

                            <div>
                                <label class="text-sm font-medium text-slate-700">Catatan</label>
                                <textarea
                                    name="note"
                                    rows="3"
                                    placeholder="Catatan tambahan"
                                    class="mt-2 w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 resize-none"
                                ></textarea>
                            </div>

                            <div class="rounded-2xl bg-slate-50 border border-slate-100 p-4">
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-slate-500">Estimasi total</span>
                                    <span id="gross_preview" class="font-bold text-slate-900">Rp0</span>
                                </div>

                                <div class="flex items-center justify-between text-sm mt-2">
                                    <span class="text-slate-500">Estimasi bersih</span>
                                    <span id="net_preview" class="font-bold text-emerald-600">Rp0</span>
                                </div>
                            </div>

                            <button class="w-full py-3 rounded-xl bg-emerald-500 text-white text-sm font-semibold hover:bg-emerald-600">
                                Simpan Penjualan
                            </button>
                        </form>
                    @else
                        <div class="mt-6 p-5 rounded-2xl bg-amber-50 border border-amber-100">
                            <p class="font-semibold text-amber-700">Belum ada produk tersedia</p>
                            <p class="text-sm text-slate-600 mt-2">
                                Tambahkan produk dulu sebelum mencatat penjualan.
                            </p>

                            <a href="/products" class="inline-flex mt-4 px-4 py-2 rounded-xl bg-emerald-500 text-white text-sm font-semibold hover:bg-emerald-600">
                                Tambah Produk
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Import CSV Trigger -->
                <div id="quick-import-sale" class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <h3 class="text-lg font-bold">Import CSV Penjualan</h3>
                            <p class="text-sm text-slate-500 mt-1 leading-relaxed">
                                Punya banyak data penjualan dari marketplace? Import CSV agar tidak perlu input manual satu per satu.
                            </p>
                        </div>

                        <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                            <x-lucide-upload class="w-5 h-5" />
                        </div>
                    </div>

                    <div class="mt-5 rounded-2xl bg-slate-50 border border-slate-100 p-4">
                        <p class="text-sm font-semibold text-slate-700">Format yang didukung</p>
                        <p class="text-xs text-slate-500 mt-2 leading-relaxed">
                            Gunakan template CSV DagangFlow agar data marketplace bisa dibaca dengan benar.
                        </p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mt-5">
                        <a href="{{ route('sales.import-template') }}"
                            class="inline-flex items-center justify-center gap-2 px-4 py-3 rounded-xl border border-slate-200 text-sm font-semibold hover:bg-slate-50">
                            <x-lucide-download class="w-4 h-4" />
                            Download Template
                        </a>

                        <button
                            type="button"
                            onclick="openImportSaleModal()"
                            class="inline-flex items-center justify-center gap-2 px-4 py-3 rounded-xl bg-emerald-500 text-white text-sm font-semibold hover:bg-emerald-600">
                            <x-lucide-upload class="w-4 h-4" />
                            Import CSV
                        </button>
                    </div>
                </div>

                <!-- Channel Summary -->
                <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
                    <h3 class="text-lg font-bold">Ringkasan Channel</h3>
                    <p class="text-sm text-slate-500 mb-5">Omzet berdasarkan sumber penjualan</p>

                    @php
                        $channelSummary = $sales->groupBy('channel')->map(function ($items) {
                            return [
                                'total' => $items->sum('gross_total'),
                                'count' => $items->count(),
                            ];
                        })->sortByDesc('total');
                    @endphp

                    <div class="space-y-4">
                        @forelse($channelSummary as $channel => $summary)
                            <div class="flex items-center gap-3 min-w-0">
                                <x-channel-logo :channel="$channel" size="sm" />

                                <div class="min-w-0">
                                    <p class="font-semibold text-slate-900 truncate">
                                        {{ $channel }}
                                    </p>

                                    <p class="text-sm text-slate-500">
                                        {{ $summary['count'] ?? 0 }} transaksi
                                    </p>
                                </div>
                            </div>
                        @empty
                            <div class="p-4 rounded-xl bg-slate-50 border border-slate-100">
                                <p class="font-semibold text-slate-700">Belum ada data channel</p>
                                <p class="text-sm text-slate-500 mt-1">Data akan muncul setelah ada transaksi.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

            </div>
        </div>

        <!-- Edit Sale Modal -->
        <div id="editSaleModal" class="fixed inset-0 bg-slate-900/50 hidden z-50 px-4 py-6 overflow-y-auto">
            <div class="min-h-full flex items-start justify-center">
                <div class="bg-white rounded-2xl p-6 w-full max-w-lg shadow-xl my-6">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-lg font-bold">Edit Penjualan</h3>
                            <p class="text-sm text-slate-500">Perbarui data transaksi penjualan</p>
                        </div>

                        <button type="button" onclick="closeEditSaleModal()" class="w-9 h-9 rounded-xl bg-slate-100 hover:bg-slate-200">
                            ✕
                        </button>
                    </div>

                    <form id="editSaleForm" method="POST" class="space-y-4">
                        @csrf
                        @method('PUT')

                        <div>
                            <label class="text-sm font-medium text-slate-700">Produk</label>
                            <select
                                name="product_id"
                                id="edit_product_id"
                                class="mt-2 w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500"
                                required
                            >
                                @foreach($products as $product)
                                    <option
                                        value="{{ $product->id }}"
                                        data-price="{{ $product->selling_price }}"
                                        data-stock="{{ $product->stock }}"
                                    >
                                        {{ $product->name }} - Rp{{ number_format($product->selling_price, 0, ',', '.') }} | Stok {{ number_format($product->stock, 0, ',', '.') }}
                                    </option>
                                @endforeach

                                @foreach($sales as $saleOption)
                                    @if($saleOption->product && !$products->contains('id', $saleOption->product->id))
                                        <option
                                            value="{{ $saleOption->product->id }}"
                                            data-price="{{ $saleOption->product->selling_price }}"
                                            data-stock="{{ $saleOption->product->stock }}"
                                        >
                                            {{ $saleOption->product->name }} - Rp{{ number_format($saleOption->product->selling_price, 0, ',', '.') }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="text-sm font-medium text-slate-700">Channel</label>
                            <select
                                name="channel"
                                id="edit_channel"
                                class="mt-2 w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500"
                                required
                            >
                                <option value="Offline">Offline</option>
                                <option value="WhatsApp">WhatsApp</option>
                                <option value="Instagram">Instagram</option>
                                <option value="Shopee">Shopee</option>
                                <option value="Tokopedia">Tokopedia</option>
                                <option value="GoFood">GoFood</option>
                                <option value="GrabFood">GrabFood</option>
                                <option value="TikTok Shop">TikTok Shop</option>
                            </select>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="text-sm font-medium text-slate-700">Jumlah Terjual</label>
                                <input
                                    type="text"
                                    inputmode="numeric"
                                    name="quantity"
                                    id="edit_quantity"
                                    class="mt-2 w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 number-input"
                                    required
                                >
                                <p id="edit_stock_info" class="text-xs text-slate-500 mt-1"></p>
                            </div>

                            <div>
                                <label class="text-sm font-medium text-slate-700">Biaya platform</label>
                                <input
                                    type="text"
                                    inputmode="numeric"
                                    name="platform_fee"
                                    id="edit_platform_fee"
                                    class="mt-2 w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 rupiah-input"
                                >
                            </div>
                        </div>

                        <div>
                            <label class="text-sm font-medium text-slate-700">Status</label>
                            <select
                                name="status"
                                id="edit_status"
                                class="mt-2 w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500"
                                required
                            >
                                <option value="Selesai">Selesai</option>
                                <option value="Diproses">Diproses</option>
                                <option value="Belum Bayar">Belum Bayar</option>
                            </select>
                        </div>

                        <div>
                            <label class="text-sm font-medium text-slate-700">Tanggal penjualan</label>
                            <input
                                type="date"
                                name="sale_date"
                                id="edit_sale_date"
                                class="mt-2 w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500"
                            >
                        </div>

                        <div>
                            <label class="text-sm font-medium text-slate-700">Catatan</label>
                            <textarea
                                name="note"
                                id="edit_note"
                                rows="3"
                                placeholder="Catatan tambahan"
                                class="mt-2 w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 resize-none"
                            ></textarea>
                        </div>

                        <div class="rounded-2xl bg-slate-50 border border-slate-100 p-4">
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-slate-500">Estimasi total</span>
                                <span id="edit_gross_preview" class="font-bold text-slate-900">Rp0</span>
                            </div>

                            <div class="flex items-center justify-between text-sm mt-2">
                                <span class="text-slate-500">Estimasi bersih</span>
                                <span id="edit_net_preview" class="font-bold text-emerald-600">Rp0</span>
                            </div>
                        </div>

                        <button type="submit" class="w-full py-3 rounded-xl bg-emerald-500 text-white text-sm font-semibold hover:bg-emerald-600">
                            Simpan Perubahan
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Import Sale CSV Modal -->
        <div id="importSaleModal" class="fixed inset-0 bg-slate-900/50 hidden z-50 px-4 py-6 overflow-y-auto">
            <div class="min-h-full flex items-start justify-center">
                <div class="bg-white rounded-2xl p-6 w-full max-w-lg shadow-xl my-6">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-lg font-bold">Import CSV Penjualan</h3>
                            <p class="text-sm text-slate-500 mt-1">
                                Upload file CSV untuk memasukkan banyak transaksi sekaligus.
                            </p>
                        </div>

                        <button type="button" onclick="closeImportSaleModal()" class="w-9 h-9 rounded-xl bg-slate-100 hover:bg-slate-200">
                            ✕
                        </button>
                    </div>

                    <div class="rounded-2xl bg-amber-50 border border-amber-100 p-4 mb-5">
                        <p class="text-sm font-semibold text-amber-700">Format kolom wajib</p>
                        <p class="text-xs text-amber-700 mt-2 leading-relaxed">
                            tanggal, produk, jumlah, channel, biaya_platform, status, catatan
                        </p>
                    </div>

                    <div class="rounded-2xl bg-slate-50 border border-slate-100 p-4 mb-5">
                        <p class="text-sm font-semibold text-slate-700">Catatan penting</p>
                        <p class="text-xs text-slate-500 mt-2 leading-relaxed">
                            Nama produk di CSV harus sama dengan nama produk di DagangFlow. Jika berbeda, baris tersebut akan gagal diimport.
                        </p>
                    </div>

                    <form action="{{ route('sales.import') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                        @csrf

                        <div>
                            <label class="text-sm font-medium text-slate-700">File CSV</label>
                            <input
                                type="file"
                                name="csv_file"
                                accept=".csv,text/csv"
                                class="mt-2 w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500"
                                required
                            >
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <a href="{{ route('sales.import-template') }}"
                                class="inline-flex items-center justify-center gap-2 px-4 py-3 rounded-xl border border-slate-200 text-sm font-semibold hover:bg-slate-50">
                                <x-lucide-download class="w-4 h-4" />
                                Download Template
                            </a>

                            <button class="inline-flex items-center justify-center gap-2 px-4 py-3 rounded-xl bg-emerald-500 text-white text-sm font-semibold hover:bg-emerald-600">
                                <x-lucide-upload class="w-4 h-4" />
                                Import Data
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <script>
            function openImportSaleModal() {
                const modal = document.getElementById('importSaleModal');

                modal.classList.remove('hidden');
                modal.classList.add('block');

                document.body.classList.add('overflow-hidden');
            }

            function closeImportSaleModal() {
                const modal = document.getElementById('importSaleModal');

                modal.classList.add('hidden');
                modal.classList.remove('block');

                document.body.classList.remove('overflow-hidden');
            }

            function formatRupiah(number) {
                return new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 0
                }).format(number);
            }

            function formatRibuan(number) {
                return new Intl.NumberFormat('id-ID', {
                    minimumFractionDigits: 0
                }).format(number);
            }

            function cleanNumber(value) {
                return parseInt(String(value || '0').replace(/\D/g, '')) || 0;
            }

            function updateSalePreview() {
                const productSelect = document.getElementById('product_id');
                const quantityInput = document.getElementById('quantity');
                const platformFeeInput = document.getElementById('platform_fee');

                if (!productSelect || !quantityInput || !platformFeeInput) return;

                const selectedOption = productSelect.options[productSelect.selectedIndex];

                if (!selectedOption || !selectedOption.dataset.price) {
                    document.getElementById('gross_preview').innerText = 'Rp0';
                    document.getElementById('net_preview').innerText = 'Rp0';
                    document.getElementById('stock_info').innerText = '';
                    return;
                }

                const price = cleanNumber(selectedOption.dataset.price);
                const stock = cleanNumber(selectedOption.dataset.stock);
                const quantity = cleanNumber(quantityInput.value);
                const platformFee = cleanNumber(platformFeeInput.value);

                const grossTotal = price * quantity;
                const netTotal = grossTotal - platformFee;

                document.getElementById('gross_preview').innerText = formatRupiah(grossTotal);
                document.getElementById('net_preview').innerText = formatRupiah(netTotal);
                document.getElementById('stock_info').innerText = `Stok tersedia: ${formatRibuan(stock)} pcs`;

                if (quantity > stock) {
                    document.getElementById('stock_info').innerText = `Jumlah terjual melebihi stok. Tersedia: ${formatRibuan(stock)} pcs`;
                    document.getElementById('stock_info').className = 'text-xs text-red-500 mt-1 font-semibold';
                } else {
                    document.getElementById('stock_info').className = 'text-xs text-slate-500 mt-1';
                }
            }

            function formatRibuanPlain(number) {
                return new Intl.NumberFormat('id-ID', {
                    minimumFractionDigits: 0
                }).format(number);
            }

            function openEditSaleModal(id, productId, channel, quantity, platformFee, status, saleDate, note) {
                const modal = document.getElementById('editSaleModal');
                const form = document.getElementById('editSaleForm');

                form.action = `/sales/${id}`;

                document.getElementById('edit_product_id').value = productId;
                document.getElementById('edit_channel').value = channel;
                document.getElementById('edit_quantity').value = formatRibuanPlain(cleanNumber(quantity));
                document.getElementById('edit_platform_fee').value = formatRibuanPlain(cleanNumber(platformFee));
                document.getElementById('edit_status').value = status;
                document.getElementById('edit_sale_date').value = saleDate || '';
                document.getElementById('edit_note').value = note || '';

                updateEditSalePreview();

                modal.classList.remove('hidden');
                modal.classList.add('block');

                document.body.classList.add('overflow-hidden');
            }

            function closeEditSaleModal() {
                const modal = document.getElementById('editSaleModal');

                modal.classList.add('hidden');
                modal.classList.remove('block');

                document.body.classList.remove('overflow-hidden');
            }

            function updateEditSalePreview() {
                const productSelect = document.getElementById('edit_product_id');
                const quantityInput = document.getElementById('edit_quantity');
                const platformFeeInput = document.getElementById('edit_platform_fee');

                if (!productSelect || !quantityInput || !platformFeeInput) return;

                const selectedOption = productSelect.options[productSelect.selectedIndex];

                if (!selectedOption || !selectedOption.dataset.price) {
                    document.getElementById('edit_gross_preview').innerText = 'Rp0';
                    document.getElementById('edit_net_preview').innerText = 'Rp0';
                    document.getElementById('edit_stock_info').innerText = '';
                    return;
                }

                const price = cleanNumber(selectedOption.dataset.price);
                const stock = cleanNumber(selectedOption.dataset.stock);
                const quantity = cleanNumber(quantityInput.value);
                const platformFee = cleanNumber(platformFeeInput.value);

                const grossTotal = price * quantity;
                const netTotal = grossTotal - platformFee;

                document.getElementById('edit_gross_preview').innerText = formatRupiah(grossTotal);
                document.getElementById('edit_net_preview').innerText = formatRupiah(netTotal);
                document.getElementById('edit_stock_info').innerText = `Stok saat ini: ${formatRibuan(stock)} pcs`;

                if (quantity > stock) {
                    document.getElementById('edit_stock_info').innerText = `Perhatian: jumlah terjual melebihi stok saat ini. Stok: ${formatRibuan(stock)} pcs`;
                    document.getElementById('edit_stock_info').className = 'text-xs text-amber-600 mt-1 font-semibold';
                } else {
                    document.getElementById('edit_stock_info').className = 'text-xs text-slate-500 mt-1';
                }
            }

            document.addEventListener('DOMContentLoaded', function () {
                const productSelect = document.getElementById('product_id');
                const quantityInput = document.getElementById('quantity');
                const platformFeeInput = document.getElementById('platform_fee');

                if (productSelect) productSelect.addEventListener('change', updateSalePreview);
                if (quantityInput) quantityInput.addEventListener('input', updateSalePreview);
                if (platformFeeInput) platformFeeInput.addEventListener('input', updateSalePreview);

                const editProductSelect = document.getElementById('edit_product_id');
                const editQuantityInput = document.getElementById('edit_quantity');
                const editPlatformFeeInput = document.getElementById('edit_platform_fee');

                if (editProductSelect) editProductSelect.addEventListener('change', updateEditSalePreview);
                if (editQuantityInput) editQuantityInput.addEventListener('input', updateEditSalePreview);
                if (editPlatformFeeInput) editPlatformFeeInput.addEventListener('input', updateEditSalePreview);

                updateSalePreview();
            });
        </script>

    </div>
@endsection