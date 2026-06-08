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

    <button
        type="button"
        onclick="window.dispatchEvent(new CustomEvent('open-create-sale-modal'))"
        class="px-4 py-2 rounded-xl bg-emerald-500 text-white text-sm font-semibold hover:bg-emerald-600"
    >
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

        @php
            $periodRevenueTrend = $periodRevenueTrend ?? ['status' => 'flat', 'percent' => 0];
            $totalRevenueTrend = $totalRevenueTrend ?? ['status' => 'flat', 'percent' => 0];
            $platformFeeTrend = $platformFeeTrend ?? ['status' => 'flat', 'percent' => 0];
            $netRevenueTrend = $netRevenueTrend ?? ['status' => 'flat', 'percent' => 0];
        @endphp

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

        <div
            id="owner-sales-table"
            data-data-url="{{ route('sales.data') }}"
            data-create-url="{{ url('/sales') }}"
            data-edit-base-url="{{ url('/sales') }}"
            data-csrf-token="{{ csrf_token() }}"
        ></div>

        <div class="w-full">
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

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @forelse($channelSummary as $channel => $summary)
                        <div class="flex items-center justify-between gap-4 p-4 rounded-2xl bg-slate-50 border border-slate-100">
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
                            <p class="text-sm font-bold text-emerald-600 whitespace-nowrap">
                                Rp{{ number_format($summary['total'] ?? 0, 0, ',', '.') }}
                            </p>
                        </div>
                    @empty
                        <div class="sm:col-span-2 p-4 rounded-xl bg-slate-50 border border-slate-100">
                            <p class="font-semibold text-slate-700">Belum ada data channel</p>
                            <p class="text-sm text-slate-500 mt-1">Data akan muncul setelah ada transaksi.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

    </div>
@endsection