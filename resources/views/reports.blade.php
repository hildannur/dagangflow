@extends('layouts.dashboard', [
    'title' => 'Laporan - DagangFlow',
    'pageTitle' => 'Laporan Bisnis',
    'subtitle' => 'Pantau performa omzet, laba, channel, dan produk terlaris'
])

@section('actions')
    <button class="hidden sm:block px-4 py-2 rounded-xl border border-slate-200 text-sm font-medium hover:bg-slate-50">
        Filter Periode
    </button>
    <button class="px-4 py-2 rounded-xl bg-emerald-500 text-white text-sm font-semibold hover:bg-emerald-600">
        Export Laporan
    </button>
@endsection

@section('content')
    <div class="space-y-8">

        <!-- Period Filter -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div>
                <h3 class="text-lg font-bold">Ringkasan Bulan {{ now()->translatedFormat('F Y') }}</h3>
                <p class="text-sm text-slate-500">Data laporan dihitung dari transaksi dan pengeluaran yang kamu input</p>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <button class="px-4 py-2 rounded-xl bg-emerald-500 text-white text-sm font-semibold">Bulan Ini</button>
                <button class="px-4 py-2 rounded-xl border border-slate-200 text-sm font-medium hover:bg-slate-50">7 Hari</button>
                <button class="px-4 py-2 rounded-xl border border-slate-200 text-sm font-medium hover:bg-slate-50">30 Hari</button>
                <button class="px-4 py-2 rounded-xl border border-slate-200 text-sm font-medium hover:bg-slate-50">Custom</button>
            </div>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-5">
            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
                <p class="text-sm text-slate-500">Omzet Kotor</p>
                <h3 class="text-3xl font-bold mt-3">
                    Rp{{ number_format($grossRevenue, 0, ',', '.') }}
                </h3>
                <p class="text-sm text-emerald-600 font-medium mt-2">Bulan ini</p>
            </div>

            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
                <p class="text-sm text-slate-500">Total Pengeluaran</p>
                <h3 class="text-3xl font-bold mt-3 text-red-600">
                    Rp{{ number_format($totalExpenses, 0, ',', '.') }}
                </h3>
                <p class="text-sm text-slate-500 mt-2">Bulan ini</p>
            </div>

            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
                <p class="text-sm text-slate-500">Estimasi Laba</p>
                <h3 class="text-3xl font-bold mt-3 {{ $estimatedProfit >= 0 ? 'text-emerald-600' : 'text-red-600' }}">
                    Rp{{ number_format($estimatedProfit, 0, ',', '.') }}
                </h3>
                <p class="text-sm text-slate-500 mt-2">Omzet - pengeluaran</p>
            </div>

            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
                <p class="text-sm text-slate-500">Margin Estimasi</p>
                <h3 class="text-3xl font-bold mt-3">
                    {{ $profitMargin }}%
                </h3>
                <p class="text-sm {{ $profitMargin >= 30 ? 'text-emerald-600' : 'text-amber-600' }} font-medium mt-2">
                    {{ $profitMargin >= 30 ? 'Masih sehat' : 'Perlu dipantau' }}
                </p>
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

                <p class="text-sm text-slate-300 mt-2">Estimasi laba bulan ini</p>

                @php
                    $safeRevenue = max($grossRevenue, 1);
                    $expensePercent = round(($totalExpenses / $safeRevenue) * 100);
                    $platformPercent = round(($platformFees / $safeRevenue) * 100);
                @endphp

                <div class="mt-8 space-y-5">
                    <div>
                        <div class="flex justify-between text-sm mb-2">
                            <span class="text-slate-300">Omzet</span>
                            <span class="font-semibold">Rp{{ number_format($grossRevenue, 0, ',', '.') }}</span>
                        </div>
                        <div class="h-3 bg-white/10 rounded-full overflow-hidden">
                            <div class="h-full bg-emerald-400 rounded-full w-full"></div>
                        </div>
                    </div>

                    <div>
                        <div class="flex justify-between text-sm mb-2">
                            <span class="text-slate-300">Pengeluaran</span>
                            <span class="font-semibold">Rp{{ number_format($totalExpenses, 0, ',', '.') }}</span>
                        </div>
                        <div class="h-3 bg-white/10 rounded-full overflow-hidden">
                            <div class="h-full bg-red-400 rounded-full" style="width: {{ min($expensePercent, 100) }}%"></div>
                        </div>
                    </div>

                    <div>
                        <div class="flex justify-between text-sm mb-2">
                            <span class="text-slate-300">Biaya Platform</span>
                            <span class="font-semibold">Rp{{ number_format($platformFees, 0, ',', '.') }}</span>
                        </div>
                        <div class="h-3 bg-white/10 rounded-full overflow-hidden">
                            <div class="h-full bg-amber-400 rounded-full" style="width: {{ min($platformPercent, 100) }}%"></div>
                        </div>
                    </div>
                </div>

                <div class="mt-8 p-4 rounded-xl bg-white/10">
                    <p class="text-sm text-slate-300 leading-relaxed">
                        @if($grossRevenue <= 0)
                            Belum ada penjualan bulan ini. Mulai catat transaksi agar laporan bisa terbaca.
                        @elseif($estimatedProfit >= 0)
                            Laba masih positif. Pantau pengeluaran agar margin tetap sehat.
                        @else
                            Pengeluaran lebih besar dari omzet. Cek kategori biaya terbesar bulan ini.
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
                            <div class="flex justify-between text-sm mb-2">
                                <span>{{ $channel }}</span>
                                <span class="font-semibold">
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
                                <p class="text-sm text-slate-500">{{ $product['sold'] }} terjual</p>
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
                <p class="text-sm text-slate-500 mb-6">Kategori biaya bulan ini</p>

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
                <h3 class="text-xl font-bold mt-2">
                    {{ $channelPerformance->keys()->first() ?? 'Belum ada channel utama' }}
                </h3>
                <p class="text-sm text-slate-600 mt-3 leading-relaxed">
                    @if($channelPerformance->count() > 0)
                        Channel ini punya kontribusi omzet terbesar bulan ini. Pertimbangkan untuk mempertahankan strategi penjualan di channel tersebut.
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
                    {{ $topProducts->first()['name'] ?? 'Belum ada produk terlaris' }}
                </h3>
                <p class="text-sm text-slate-600 mt-3 leading-relaxed">
                    @if($topProducts->count() > 0)
                        Produk ini paling sering dibeli bulan ini. Cocok dijadikan fokus stok, promo, atau bundling.
                    @else
                        Produk terlaris akan muncul setelah kamu mencatat penjualan.
                    @endif
                </p>
            </div>
        </div>

    </div>
@endsection