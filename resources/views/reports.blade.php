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

        <!-- AI Insight Card -->
        <div class="bg-[#0F172A] rounded-2xl p-6 text-white shadow-sm">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div>
                    <p class="text-sm text-emerald-300 font-semibold">DagangFlow AI Insight</p>
                    <h3 class="text-2xl font-bold mt-2">Ringkasan penjualan & keuangan berbasis AI</h3>
                    <p class="text-sm text-slate-300 mt-2">
                        Gemini akan membaca data laporan sesuai periode yang dipilih dan memberi saran bisnis yang praktis.
                    </p>
                </div>

                <form action="{{ route('reports.ai-insight') }}" method="POST">
                    @csrf

                    <input type="hidden" name="start_date" value="{{ $selectedPeriod['start_date'] }}">
                    <input type="hidden" name="end_date" value="{{ $selectedPeriod['end_date'] }}">

                    <button class="px-5 py-3 rounded-xl bg-emerald-500 text-white text-sm font-semibold hover:bg-emerald-600">
                        Generate AI Insight
                    </button>
                </form>
            </div>

            @if (session('aiNotice'))
                <div class="mt-6 p-4 rounded-2xl bg-amber-400/10 border border-amber-300/20 text-amber-100 text-sm leading-relaxed">
                    {{ session('aiNotice') }}
                </div>
            @endif

            @if (session('aiInsight'))
                <div class="mt-6 p-5 rounded-2xl bg-white/10 border border-white/10">
                    <div class="ai-insight-content text-sm leading-relaxed text-slate-100">
                        {!! \Illuminate\Support\Str::markdown(session('aiInsight')) !!}
                    </div>
                </div>
            @else
                <div class="mt-6 p-5 rounded-2xl bg-white/10 border border-white/10">
                    <p class="text-sm text-slate-300 leading-relaxed">
                        Belum ada insight AI. Klik tombol Generate AI Insight untuk membuat ringkasan otomatis.
                    </p>
                </div>
            @endif
        </div>

        <!-- Period Filter -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm">
            <div class="flex flex-col xl:flex-row xl:items-end xl:justify-between gap-5">
                <div>
                    <h3 class="text-lg font-bold">Ringkasan Periode</h3>
                    <p class="text-sm text-slate-500 mt-1">
                        Data dihitung dari
                        {{ \Carbon\Carbon::parse($selectedPeriod['start_date'])->format('d M Y') }}
                        sampai
                        {{ \Carbon\Carbon::parse($selectedPeriod['end_date'])->format('d M Y') }}
                    </p>
                </div>

                <form action="/reports" method="GET" class="flex flex-col sm:flex-row sm:items-end gap-3">
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

                    <a href="/reports" class="px-5 py-3 rounded-xl border border-slate-200 text-sm font-semibold hover:bg-slate-50 text-center">
                        Reset
                    </a>
                </form>
            </div>

            <div class="flex flex-wrap items-center gap-3 mt-5">
                <a href="/reports?start_date={{ now()->toDateString() }}&end_date={{ now()->toDateString() }}"
                    class="px-4 py-2 rounded-xl border border-slate-200 text-sm font-medium hover:bg-slate-50">
                    Hari Ini
                </a>

                <a href="/reports?start_date={{ now()->subDays(6)->toDateString() }}&end_date={{ now()->toDateString() }}"
                    class="px-4 py-2 rounded-xl border border-slate-200 text-sm font-medium hover:bg-slate-50">
                    7 Hari
                </a>

                <a href="/reports?start_date={{ now()->startOfMonth()->toDateString() }}&end_date={{ now()->endOfMonth()->toDateString() }}"
                    class="px-4 py-2 rounded-xl bg-emerald-50 text-emerald-700 border border-emerald-100 text-sm font-semibold hover:bg-emerald-100">
                    Bulan Ini
                </a>

                <a href="/reports?start_date={{ now()->subMonth()->startOfMonth()->toDateString() }}&end_date={{ now()->subMonth()->endOfMonth()->toDateString() }}"
                    class="px-4 py-2 rounded-xl border border-slate-200 text-sm font-medium hover:bg-slate-50">
                    Bulan Lalu
                </a>
            </div>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-5 gap-5">
            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
                <p class="text-sm text-slate-500">Omzet Kotor</p>
                <h3 class="text-3xl font-bold mt-3">
                    Rp{{ number_format($grossRevenue, 0, ',', '.') }}
                </h3>
                <p class="text-sm text-emerald-600 font-medium mt-2">Periode aktif</p>
            </div>

            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
                <p class="text-sm text-slate-500">HPP Produk</p>
                <h3 class="text-3xl font-bold mt-3 text-blue-600">
                    Rp{{ number_format($totalCOGS, 0, ',', '.') }}
                </h3>
                <p class="text-sm text-slate-500 mt-2">Modal produk terjual</p>
            </div>

            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
                <p class="text-sm text-slate-500">Total Pengeluaran</p>
                <h3 class="text-3xl font-bold mt-3 text-red-600">
                    Rp{{ number_format($totalExpenses, 0, ',', '.') }}
                </h3>
                <p class="text-sm text-slate-500 mt-2">Operasional bisnis</p>
            </div>

            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
                <p class="text-sm text-slate-500">Estimasi Laba Bersih</p>
                <h3 class="text-3xl font-bold mt-3 {{ $estimatedProfit >= 0 ? 'text-emerald-600' : 'text-red-600' }}">
                    Rp{{ number_format($estimatedProfit, 0, ',', '.') }}
                </h3>
                <p class="text-sm text-slate-500 mt-2">Omzet - HPP - fee - pengeluaran</p>
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

                <p class="text-sm text-slate-300 mt-2">Estimasi laba bersih periode ini</p>

                @php
                    $safeRevenue = max($grossRevenue, 1);
                    $cogsPercent = round(($totalCOGS / $safeRevenue) * 100);
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
                            <span class="text-slate-300">HPP Produk</span>
                            <span class="font-semibold">Rp{{ number_format($totalCOGS, 0, ',', '.') }}</span>
                        </div>
                        <div class="h-3 bg-white/10 rounded-full overflow-hidden">
                            <div class="h-full bg-blue-400 rounded-full" style="width: {{ min($cogsPercent, 100) }}%"></div>
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
                <h3 class="text-xl font-bold mt-2">
                    {{ $channelPerformance->keys()->first() ?? 'Belum ada channel utama' }}
                </h3>
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