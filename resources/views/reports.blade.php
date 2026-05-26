@extends('layouts.dashboard', [
    'title' => 'Laporan - DagangFlow',
    'pageTitle' => 'Laporan Bisnis',
    'subtitle' => 'Pantau performa omzet, laba, channel, dan produk terlaris'
])

@section('actions')
    <a href="#period-filter" class="hidden sm:block px-4 py-2 rounded-xl border border-slate-200 text-sm font-medium hover:bg-slate-50">
        Filter Periode
    </a>

    <a href="{{ route('reports.export', ['start_date' => $selectedPeriod['start_date'], 'end_date' => $selectedPeriod['end_date']]) }}"
        class="px-4 py-2 rounded-xl bg-emerald-500 text-white text-sm font-semibold hover:bg-emerald-600">
        Export Laporan
    </a>
@endsection

@section('content')
    <div class="space-y-8">

        <!-- AI Insight Card -->
        <div class="bg-[#0F172A] rounded-2xl text-white shadow-sm overflow-hidden border border-white/10">
            <div class="grid grid-cols-1 xl:grid-cols-12">

                <div class="xl:col-span-5 p-6">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-full bg-emerald-500 text-white flex items-center justify-center">
                            <x-lucide-sparkles class="w-4 h-4" />
                        </div>

                        <p class="text-sm text-emerald-300 font-semibold">
                            DagangFlow AI Insight
                        </p>
                    </div>

                    <h3 class="text-2xl font-bold mt-4">
                        Ringkasan penjualan & keuangan berbasis AI
                    </h3>

                    <p class="text-sm text-slate-300 mt-3 leading-relaxed">
                        Dapatkan rangkuman performa bisnis dan rekomendasi cerdas untuk pertumbuhan toko Anda.
                    </p>
                </div>

                <div class="xl:col-span-2 p-6 flex items-center xl:justify-center border-t xl:border-t-0 xl:border-l border-white/10">
                    <form action="{{ route('reports.ai-insight') }}" method="POST" class="w-full">
                        @csrf

                        <input type="hidden" name="start_date" value="{{ $selectedPeriod['start_date'] }}">
                        <input type="hidden" name="end_date" value="{{ $selectedPeriod['end_date'] }}">

                        <button class="w-full inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl bg-emerald-500 text-white text-sm font-semibold hover:bg-emerald-600 transition">
                            <x-lucide-sparkles class="w-4 h-4" />
                            Generate AI Insight
                        </button>
                    </form>
                </div>

                <div class="xl:col-span-5 p-6 border-t xl:border-t-0 xl:border-l border-white/10 bg-white/[0.03]">
                    <div class="flex items-center justify-between gap-4">
                        <p class="text-sm font-semibold text-white">
                            Insight Terbaru
                            <span class="text-slate-400 font-medium">
                                ({{ now()->format('d M Y') }})
                            </span>
                        </p>

                        <div class="inline-flex items-center gap-1.5 text-xs text-emerald-300 font-semibold">
                            <x-lucide-circle-check class="w-3.5 h-3.5" />
                            Dihasilkan
                        </div>
                    </div>

                    @if (session('aiNotice'))
                        <div class="mt-4 p-3 rounded-xl bg-amber-400/10 border border-amber-300/20 text-amber-100 text-xs leading-relaxed">
                            {{ session('aiNotice') }}
                        </div>
                    @endif

                    @if (session('aiInsight'))
                        <div class="ai-insight-content mt-4 text-sm leading-relaxed text-slate-100 max-h-40 overflow-y-auto pr-2">
                            {!! \Illuminate\Support\Str::markdown(session('aiInsight')) !!}
                        </div>
                    @else
                        <div class="mt-4 text-sm leading-relaxed text-slate-300 space-y-2">
                            <p>
                                Omzet Anda akan dianalisis berdasarkan periode yang dipilih.
                            </p>
                            <p>
                                Klik <span class="text-emerald-300 font-semibold">Generate AI Insight</span> untuk melihat ringkasan kondisi bisnis, masalah utama, dan rekomendasi aksi.
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Period Filter -->
        <div id="period-filter" class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm">
            <div class="flex flex-col xl:flex-row xl:items-end xl:justify-between gap-5">
                <form action="/reports" method="GET" class="flex flex-col lg:flex-row lg:items-end gap-3 flex-1">
                    <div>
                        <label class="text-xs font-semibold text-slate-500">Periode</label>

                        <div class="mt-2 flex flex-col sm:flex-row sm:items-center gap-3">
                            <div class="relative">
                                <input
                                    type="date"
                                    name="start_date"
                                    value="{{ $selectedPeriod['start_date'] }}"
                                    class="w-full sm:w-44 pl-4 pr-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500"
                                >
                            </div>

                            <span class="hidden sm:block text-slate-400 font-semibold">—</span>

                            <div class="relative">
                                <input
                                    type="date"
                                    name="end_date"
                                    value="{{ $selectedPeriod['end_date'] }}"
                                    class="w-full sm:w-44 pl-4 pr-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500"
                                >
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-3">
                        <button class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl bg-emerald-500 text-white text-sm font-semibold hover:bg-emerald-600">
                            <x-lucide-filter class="w-4 h-4" />
                            Terapkan Filter
                        </button>

                        <a href="/reports" class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl border border-slate-200 text-sm font-semibold hover:bg-slate-50 text-center">
                            <x-lucide-rotate-ccw class="w-4 h-4" />
                            Reset
                        </a>
                    </div>
                </form>

                <div class="flex flex-wrap items-center gap-3 xl:justify-end">
                    <a href="/reports?start_date={{ now()->toDateString() }}&end_date={{ now()->toDateString() }}"
                        class="px-4 py-2 rounded-xl border border-slate-200 text-sm font-medium hover:bg-slate-50">
                        Hari Ini
                    </a>

                    <a href="/reports?start_date={{ now()->subDays(6)->toDateString() }}&end_date={{ now()->toDateString() }}"
                        class="px-4 py-2 rounded-xl border border-slate-200 text-sm font-medium hover:bg-slate-50">
                        7 Hari
                    </a>

                    <a href="/reports?start_date={{ now()->startOfMonth()->toDateString() }}&end_date={{ now()->endOfMonth()->toDateString() }}"
                        class="px-4 py-2 rounded-xl bg-emerald-500 text-white border border-emerald-500 text-sm font-semibold hover:bg-emerald-600">
                        Bulan Ini
                    </a>

                    <a href="/reports?start_date={{ now()->subMonth()->startOfMonth()->toDateString() }}&end_date={{ now()->subMonth()->endOfMonth()->toDateString() }}"
                        class="px-4 py-2 rounded-xl border border-slate-200 text-sm font-medium hover:bg-slate-50">
                        Bulan Lalu
                    </a>
                </div>
            </div>
        </div>

        @php
            $grossRevenueTrend = $grossRevenueTrend ?? ['status' => 'flat', 'percent' => 0];
            $cogsTrend = $cogsTrend ?? ['status' => 'flat', 'percent' => 0];
            $expenseTrend = $expenseTrend ?? ['status' => 'flat', 'percent' => 0];
            $profitTrend = $profitTrend ?? ['status' => 'flat', 'percent' => 0];
            $marginTrend = $marginTrend ?? ['status' => 'flat', 'percent' => 0];
        @endphp

        @if($grossRevenue <= 0 && $totalExpenses <= 0)
            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
                <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 items-center">
                    <div class="xl:col-span-2">
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-100 text-sm font-semibold">
                            <x-lucide-chart-column-big class="w-4 h-4" />
                            Laporan belum tersedia
                        </div>

                        <h3 class="text-2xl font-bold text-slate-900 mt-4">
                            Belum ada data untuk dianalisis
                        </h3>

                        <p class="text-sm text-slate-500 mt-3 leading-relaxed max-w-2xl">
                            Laporan bisnis akan muncul setelah kamu mencatat produk, penjualan, dan pengeluaran.
                            DagangFlow akan menghitung omzet, HPP, biaya platform, estimasi laba, channel terbaik,
                            dan produk terlaris berdasarkan data tersebut.
                        </p>

                        <div class="flex flex-col sm:flex-row gap-3 mt-6">
                            <a href="/products" class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl bg-emerald-500 text-white text-sm font-semibold hover:bg-emerald-600">
                                <x-lucide-package class="w-4 h-4" />
                                Tambah Produk
                            </a>

                            <a href="/sales" class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl border border-slate-200 text-sm font-semibold hover:bg-slate-50">
                                <x-lucide-receipt-text class="w-4 h-4" />
                                Catat Penjualan
                            </a>

                            <a href="/help" class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl border border-slate-200 text-sm font-semibold hover:bg-slate-50">
                                <x-lucide-circle-help class="w-4 h-4" />
                                Pelajari Laporan
                            </a>
                        </div>
                    </div>

                    <div class="rounded-2xl bg-slate-50 border border-slate-100 p-5">
                        <p class="text-sm font-semibold text-slate-700">Data yang dibutuhkan</p>

                        <div class="space-y-4 mt-4">
                            <div class="flex items-start gap-3">
                                <div class="w-8 h-8 rounded-full bg-emerald-500 text-white flex items-center justify-center shrink-0">
                                    <x-lucide-package class="w-4 h-4" />
                                </div>

                                <div>
                                    <p class="font-semibold text-slate-900">Produk</p>
                                    <p class="text-sm text-slate-500 mt-1">Untuk membaca harga jual, modal, dan stok.</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-3">
                                <div class="w-8 h-8 rounded-full bg-blue-500 text-white flex items-center justify-center shrink-0">
                                    <x-lucide-receipt-text class="w-4 h-4" />
                                </div>

                                <div>
                                    <p class="font-semibold text-slate-900">Penjualan</p>
                                    <p class="text-sm text-slate-500 mt-1">Untuk menghitung omzet, HPP, dan biaya platform.</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-3">
                                <div class="w-8 h-8 rounded-full bg-red-500 text-white flex items-center justify-center shrink-0">
                                    <x-lucide-wallet class="w-4 h-4" />
                                </div>

                                <div>
                                    <p class="font-semibold text-slate-900">Pengeluaran</p>
                                    <p class="text-sm text-slate-500 mt-1">Untuk menghitung estimasi laba lebih realistis.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Stats Top Row -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
                <p class="text-sm text-slate-500">Omzet Kotor</p>

                <div class="flex items-center gap-4 mt-4">
                    <div class="w-16 h-16 rounded-full bg-emerald-500 text-white flex items-center justify-center shrink-0 shadow-sm">
                        <x-lucide-trending-up class="w-9 h-9" />
                    </div>

                    <h3 class="text-3xl font-bold leading-tight">
                        Rp{{ number_format($grossRevenue, 0, ',', '.') }}
                    </h3>
                </div>

                <div class="mt-5 flex items-center gap-2 text-sm font-semibold">
                    <span class="inline-flex items-center gap-1
                        {{ $grossRevenueTrend['status'] === 'up' ? 'text-emerald-600' : '' }}
                        {{ $grossRevenueTrend['status'] === 'flat' ? 'text-slate-500' : '' }}
                        {{ $grossRevenueTrend['status'] === 'down' ? 'text-red-600' : '' }}
                    ">
                        @if($grossRevenueTrend['status'] === 'up')
                            <x-lucide-trending-up class="w-4 h-4" />
                            {{ $grossRevenueTrend['percent'] }}%
                        @elseif($grossRevenueTrend['status'] === 'down')
                            <x-lucide-trending-down class="w-4 h-4" />
                            {{ $grossRevenueTrend['percent'] }}%
                        @else
                            <x-lucide-minus class="w-4 h-4" />
                            Stabil
                        @endif
                    </span>

                    <span class="text-slate-500 font-medium">dari bulan lalu</span>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
                <p class="text-sm text-slate-500">HPP Produk</p>

                <div class="flex items-center gap-4 mt-4">
                    <div class="w-16 h-16 rounded-full bg-blue-500 text-white flex items-center justify-center shrink-0 shadow-sm">
                        <x-lucide-package class="w-9 h-9" />
                    </div>

                    <h3 class="text-3xl font-bold leading-tight text-blue-600">
                        Rp{{ number_format($totalCOGS, 0, ',', '.') }}
                    </h3>
                </div>

                <div class="mt-5 flex items-center gap-2 text-sm font-semibold">
                    <span class="inline-flex items-center gap-1
                        {{ $cogsTrend['status'] === 'up' ? 'text-red-600' : '' }}
                        {{ $cogsTrend['status'] === 'flat' ? 'text-slate-500' : '' }}
                        {{ $cogsTrend['status'] === 'down' ? 'text-emerald-600' : '' }}
                    ">
                        @if($cogsTrend['status'] === 'up')
                            <x-lucide-trending-up class="w-4 h-4" />
                            {{ $cogsTrend['percent'] }}%
                        @elseif($cogsTrend['status'] === 'down')
                            <x-lucide-trending-down class="w-4 h-4" />
                            {{ $cogsTrend['percent'] }}%
                        @else
                            <x-lucide-minus class="w-4 h-4" />
                            Stabil
                        @endif
                    </span>

                    <span class="text-slate-500 font-medium">dari bulan lalu</span>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
                <p class="text-sm text-slate-500">Total Pengeluaran</p>

                <div class="flex items-center gap-4 mt-4">
                    <div class="w-16 h-16 rounded-full bg-red-500 text-white flex items-center justify-center shrink-0 shadow-sm">
                        <x-lucide-wallet class="w-9 h-9" />
                    </div>

                    <h3 class="text-3xl font-bold leading-tight text-red-600">
                        Rp{{ number_format($totalExpenses, 0, ',', '.') }}
                    </h3>
                </div>

                <div class="mt-5 flex items-center gap-2 text-sm font-semibold">
                    <span class="inline-flex items-center gap-1
                        {{ $expenseTrend['status'] === 'up' ? 'text-red-600' : '' }}
                        {{ $expenseTrend['status'] === 'flat' ? 'text-slate-500' : '' }}
                        {{ $expenseTrend['status'] === 'down' ? 'text-emerald-600' : '' }}
                    ">
                        @if($expenseTrend['status'] === 'up')
                            <x-lucide-trending-up class="w-4 h-4" />
                            {{ $expenseTrend['percent'] }}%
                        @elseif($expenseTrend['status'] === 'down')
                            <x-lucide-trending-down class="w-4 h-4" />
                            {{ $expenseTrend['percent'] }}%
                        @else
                            <x-lucide-minus class="w-4 h-4" />
                            Stabil
                        @endif
                    </span>

                    <span class="text-slate-500 font-medium">dari bulan lalu</span>
                </div>
            </div>
        </div>

        <!-- Stats Bottom Row -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
                <p class="text-sm text-slate-500">Estimasi Laba Bersih</p>

                <div class="flex items-center gap-4 mt-4">
                    <div class="w-16 h-16 rounded-full {{ $estimatedProfit >= 0 ? 'bg-emerald-500' : 'bg-red-500' }} text-white flex items-center justify-center shrink-0 shadow-sm">
                        <x-lucide-hand-coins class="w-9 h-9" />
                    </div>

                    <h3 class="text-3xl font-bold leading-tight {{ $estimatedProfit >= 0 ? 'text-emerald-600' : 'text-red-600' }}">
                        Rp{{ number_format($estimatedProfit, 0, ',', '.') }}
                    </h3>
                </div>

                <div class="mt-5 flex items-center gap-2 text-sm font-semibold">
                    <span class="inline-flex items-center gap-1
                        {{ $profitTrend['status'] === 'up' ? 'text-emerald-600' : '' }}
                        {{ $profitTrend['status'] === 'flat' ? 'text-slate-500' : '' }}
                        {{ $profitTrend['status'] === 'down' ? 'text-red-600' : '' }}
                    ">
                        @if($profitTrend['status'] === 'up')
                            <x-lucide-trending-up class="w-4 h-4" />
                            {{ $profitTrend['percent'] }}%
                        @elseif($profitTrend['status'] === 'down')
                            <x-lucide-trending-down class="w-4 h-4" />
                            {{ $profitTrend['percent'] }}%
                        @else
                            <x-lucide-minus class="w-4 h-4" />
                            Stabil
                        @endif
                    </span>

                    <span class="text-slate-500 font-medium">dari bulan lalu</span>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
                <p class="text-sm text-slate-500">Margin Estimasi</p>

                <div class="flex items-center gap-4 mt-4">
                    <div class="w-16 h-16 rounded-full {{ $profitMargin >= 30 ? 'bg-emerald-500' : 'bg-amber-500' }} text-white flex items-center justify-center shrink-0 shadow-sm">
                        <x-lucide-percent class="w-9 h-9" />
                    </div>

                    <h3 class="text-3xl font-bold leading-tight {{ $profitMargin >= 30 ? 'text-emerald-600' : 'text-amber-600' }}">
                        {{ $profitMargin }}%
                    </h3>
                </div>

                <div class="mt-5 flex items-center gap-2 text-sm font-semibold">
                    <span class="inline-flex items-center gap-1
                        {{ $marginTrend['status'] === 'up' ? 'text-emerald-600' : '' }}
                        {{ $marginTrend['status'] === 'flat' ? 'text-slate-500' : '' }}
                        {{ $marginTrend['status'] === 'down' ? 'text-red-600' : '' }}
                    ">
                        @if($marginTrend['status'] === 'up')
                            <x-lucide-trending-up class="w-4 h-4" />
                            {{ $marginTrend['percent'] }}%
                        @elseif($marginTrend['status'] === 'down')
                            <x-lucide-trending-down class="w-4 h-4" />
                            {{ $marginTrend['percent'] }}%
                        @else
                            <x-lucide-minus class="w-4 h-4" />
                            Stabil
                        @endif
                    </span>

                    <span class="text-slate-500 font-medium">dari bulan lalu</span>
                </div>
            </div>
        </div>

        <!-- Main Grid -->
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

            <!-- Revenue Chart -->
            <div class="xl:col-span-2 bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-lg font-bold">Tren Omzet</h3>
                        <p class="text-sm text-slate-500">Performa penjualan 7 hari terakhir</p>
                    </div>

                    <select class="px-4 py-2 rounded-xl border border-slate-200 text-sm focus:outline-none">
                        <option>Omzet</option>
                        <option>Laba</option>
                        <option>Transaksi</option>
                    </select>
                </div>

                <div class="h-80 rounded-2xl bg-gradient-to-br from-emerald-50 to-slate-50 border border-slate-100 p-6 flex items-end gap-4">
                    @foreach($sevenDaysSales as $item)
                        @php
                            $height = $item['total'] > 0 ? max(12, ($item['total'] / $maxDailySales) * 100) : 6;
                        @endphp

                        <div class="flex-1 h-full flex flex-col justify-end items-center gap-3">
                            <p class="text-xs font-semibold text-slate-500">
                                Rp{{ number_format($item['total'], 0, ',', '.') }}
                            </p>

                            <div class="w-full bg-emerald-500 rounded-t-xl hover:bg-emerald-600 transition" style="height: {{ $height }}%"></div>

                            <p class="text-xs text-slate-500">{{ $item['day'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Profit Summary -->
            <div class="bg-[#0F172A] rounded-2xl p-6 text-white shadow-sm">
                <p class="text-sm text-emerald-300 font-semibold">Profit Overview</p>

                <h3 class="text-3xl font-bold mt-3">
                    Rp{{ number_format($estimatedProfit, 0, ',', '.') }}
                </h3>

                <p class="text-sm text-slate-300 mt-2">Estimasi laba bersih periode ini</p>

                @php
                    $safeRevenue = max($grossRevenue, 1);
                    $cogsPercent = round(($totalCOGS / $safeRevenue) * 100);
                    $expensePercent = round(($totalExpenses / $safeRevenue) * 100);
                    $platformPercent = round(($platformFees / $safeRevenue) * 100);
                @endphp

                <div class="mt-8 space-y-5">
                    <div>
                        <div class="flex items-center justify-between gap-3 text-sm mb-2">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-8 h-8 rounded-full bg-emerald-400/15 text-emerald-300 flex items-center justify-center shrink-0">
                                    <x-lucide-trending-up class="w-4 h-4" />
                                </div>

                                <span class="text-slate-300">Omzet</span>
                            </div>

                            <span class="font-semibold text-right shrink-0">
                                Rp{{ number_format($grossRevenue, 0, ',', '.') }}
                            </span>
                        </div>

                        <div class="h-3 bg-white/10 rounded-full overflow-hidden">
                            <div class="h-full bg-emerald-400 rounded-full w-full"></div>
                        </div>
                    </div>

                    <div>
                        <div class="flex items-center justify-between gap-3 text-sm mb-2">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-8 h-8 rounded-full bg-blue-400/15 text-blue-300 flex items-center justify-center shrink-0">
                                    <x-lucide-package class="w-4 h-4" />
                                </div>

                                <span class="text-slate-300">HPP Produk</span>
                            </div>

                            <span class="font-semibold text-right shrink-0">
                                Rp{{ number_format($totalCOGS, 0, ',', '.') }}
                            </span>
                        </div>

                        <div class="h-3 bg-white/10 rounded-full overflow-hidden">
                            <div class="h-full bg-blue-400 rounded-full" style="width: {{ min($cogsPercent, 100) }}%"></div>
                        </div>
                    </div>

                    <div>
                        <div class="flex items-center justify-between gap-3 text-sm mb-2">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-8 h-8 rounded-full bg-red-400/15 text-red-300 flex items-center justify-center shrink-0">
                                    <x-lucide-wallet class="w-4 h-4" />
                                </div>

                                <span class="text-slate-300">Pengeluaran</span>
                            </div>

                            <span class="font-semibold text-right shrink-0">
                                Rp{{ number_format($totalExpenses, 0, ',', '.') }}
                            </span>
                        </div>

                        <div class="h-3 bg-white/10 rounded-full overflow-hidden">
                            <div class="h-full bg-red-400 rounded-full" style="width: {{ min($expensePercent, 100) }}%"></div>
                        </div>
                    </div>

                    <div>
                        <div class="flex items-center justify-between gap-3 text-sm mb-2">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-8 h-8 rounded-full bg-amber-400/15 text-amber-300 flex items-center justify-center shrink-0">
                                    <x-lucide-badge-percent class="w-4 h-4" />
                                </div>

                                <span class="text-slate-300">Biaya Platform</span>
                            </div>

                            <span class="font-semibold text-right shrink-0">
                                Rp{{ number_format($platformFees, 0, ',', '.') }}
                            </span>
                        </div>

                        <div class="h-3 bg-white/10 rounded-full overflow-hidden">
                            <div class="h-full bg-amber-400 rounded-full" style="width: {{ min($platformPercent, 100) }}%"></div>
                        </div>
                    </div>
                </div>

                <div class="mt-8 p-4 rounded-xl bg-white/10">
                    <p class="text-sm text-slate-300 leading-relaxed">
                        @if($grossRevenue <= 0 && $totalExpenses <= 0)
                            Belum ada data penjualan dan pengeluaran pada periode ini.
                        @elseif($grossRevenue <= 0)
                            Belum ada penjualan, tetapi sudah ada pengeluaran. Perlu mulai dorong transaksi masuk.
                        @elseif($estimatedProfit >= 0)
                            Laba bersih masih positif. Tetap pantau HPP, biaya platform, dan pengeluaran agar margin sehat.
                        @else
                            Laba bersih masih negatif. Cek HPP, biaya platform, dan kategori pengeluaran terbesar periode ini.
                        @endif
                    </p>
                </div>
            </div>
        </div>

        <!-- Bottom Grid -->
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

            <!-- Channel Performance -->
            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
                <h3 class="text-lg font-bold">Performa Channel</h3>
                <p class="text-sm text-slate-500 mb-6">Channel dengan kontribusi omzet terbesar</p>

                @php
                    $maxChannel = max($channelPerformance->pluck('total')->max() ?? 0, 1);
                @endphp

                <div class="space-y-5">
                    @forelse($channelPerformance as $channel => $summary)
                        @php
                            $percent = round(($summary['total'] / $maxChannel) * 100);
                        @endphp

                        <div>
                            <div class="flex items-center justify-between gap-3 text-sm mb-2">
                                <div class="flex items-center gap-3 min-w-0">
                                    <x-channel-logo :channel="$channel" size="sm" />

                                    <span class="font-medium text-slate-700 truncate">
                                        {{ $channel }}
                                    </span>
                                </div>

                                <span class="font-semibold shrink-0">
                                    Rp{{ number_format($summary['total'], 0, ',', '.') }}
                                </span>
                            </div>

                            <div class="h-3 bg-slate-100 rounded-full overflow-hidden">
                                <div class="h-full bg-emerald-500 rounded-full" style="width: {{ $percent }}%"></div>
                            </div>

                            <p class="text-xs text-slate-500 mt-1">
                                {{ $summary['count'] }} transaksi
                            </p>
                        </div>
                    @empty
                        <div class="p-4 rounded-xl bg-slate-50 border border-slate-100">
                            <p class="font-semibold text-slate-700">Belum ada data channel</p>
                            <p class="text-sm text-slate-500 mt-1">Data muncul setelah ada penjualan.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Top Products -->
            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
                <h3 class="text-lg font-bold">Produk Terlaris</h3>
                <p class="text-sm text-slate-500 mb-6">Berdasarkan jumlah terjual</p>

                <div class="space-y-4">
                    @forelse($topProducts as $product)
                        <div class="flex items-center justify-between p-4 rounded-xl bg-slate-50 border border-slate-100">
                            <div>
                                <p class="font-semibold">{{ $product['name'] }}</p>
                                <p class="text-sm text-slate-500">{{ number_format($product['sold'], 0, ',', '.') }} terjual</p>
                            </div>

                            <p class="font-bold text-emerald-600">
                                Rp{{ number_format($product['revenue'], 0, ',', '.') }}
                            </p>
                        </div>
                    @empty
                        <div class="p-4 rounded-xl bg-slate-50 border border-slate-100">
                            <p class="font-semibold text-slate-700">Belum ada produk terjual</p>
                            <p class="text-sm text-slate-500 mt-1">Produk terlaris muncul setelah ada transaksi.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Expense Breakdown -->
            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
                <h3 class="text-lg font-bold">Pengeluaran Terbesar</h3>
                <p class="text-sm text-slate-500 mb-6">Kategori biaya periode ini</p>

                @php
                    $totalExpenseBreakdown = $expenseBreakdown->sum();
                @endphp

                <div class="space-y-4">
                    @forelse($expenseBreakdown as $category => $amount)
                        @php
                            $percent = $totalExpenseBreakdown > 0
                                ? round(($amount / $totalExpenseBreakdown) * 100)
                                : 0;
                        @endphp

                        <div class="flex items-center justify-between p-4 rounded-xl bg-slate-50 border border-slate-100">
                            <div>
                                <p class="font-semibold">{{ $category }}</p>
                                <p class="text-sm text-slate-500">{{ $percent }}% dari pengeluaran</p>
                            </div>

                            <p class="font-bold text-red-600">
                                Rp{{ number_format($amount, 0, ',', '.') }}
                            </p>
                        </div>
                    @empty
                        <div class="p-4 rounded-xl bg-slate-50 border border-slate-100">
                            <p class="font-semibold text-slate-700">Belum ada pengeluaran</p>
                            <p class="text-sm text-slate-500 mt-1">Kategori biaya muncul setelah ada pengeluaran.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Insight Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="rounded-2xl p-6 bg-emerald-50 border border-emerald-100">
                <p class="text-sm text-emerald-700 font-semibold">Insight 01</p>

                @php
                    $topInsightChannel = $channelPerformance->keys()->first();
                @endphp

                <div class="flex items-center gap-3 mt-3">
                    @if($topInsightChannel)
                        <x-channel-logo :channel="$topInsightChannel" size="md" />
                    @else
                        <div class="w-10 h-10 rounded-full bg-emerald-500 text-white flex items-center justify-center shrink-0">
                            <x-lucide-store class="w-5 h-5" />
                        </div>
                    @endif

                    <h3 class="text-xl font-bold leading-tight">
                        {{ $topInsightChannel ?? 'Belum ada channel utama' }}
                    </h3>
                </div>

                <p class="text-sm text-slate-600 mt-3 leading-relaxed">
                    @if($channelPerformance->count() > 0)
                        Channel ini punya kontribusi omzet terbesar pada periode ini.
                    @else
                        Mulai catat transaksi agar DagangFlow bisa membaca channel terbaik kamu.
                    @endif
                </p>
            </div>

            <div class="rounded-2xl p-6 bg-amber-50 border border-amber-100">
                <p class="text-sm text-amber-700 font-semibold">Insight 02</p>
                <h3 class="text-xl font-bold mt-2">
                    Biaya platform: Rp{{ number_format($platformFees, 0, ',', '.') }}
                </h3>
                <p class="text-sm text-slate-600 mt-3 leading-relaxed">
                    Biaya platform dari marketplace dan delivery perlu tetap dihitung agar estimasi laba tidak terlalu optimis.
                </p>
            </div>

            <div class="rounded-2xl p-6 bg-blue-50 border border-blue-100">
                <p class="text-sm text-blue-700 font-semibold">Insight 03</p>
                <h3 class="text-xl font-bold mt-2">
                    HPP: Rp{{ number_format($totalCOGS, 0, ',', '.') }}
                </h3>
                <p class="text-sm text-slate-600 mt-3 leading-relaxed">
                    HPP adalah modal dari produk yang sudah terjual. Angka ini penting agar laba bersih tidak terlihat terlalu besar.
                </p>
            </div>
        </div>

    </div>

    <style>
        .ai-insight-content p {
            margin-bottom: 12px;
        }

        .ai-insight-content strong {
            color: #ffffff;
            font-weight: 800;
        }

        .ai-insight-content ol {
            list-style: decimal;
            padding-left: 20px;
            margin-top: 12px;
            margin-bottom: 12px;
        }

        .ai-insight-content ul {
            list-style: disc;
            padding-left: 20px;
            margin-top: 12px;
            margin-bottom: 12px;
        }

        .ai-insight-content li {
            margin-bottom: 8px;
        }
    </style>
@endsection