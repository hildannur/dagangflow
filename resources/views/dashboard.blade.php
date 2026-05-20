@extends('layouts.dashboard', [
    'title' => 'Dashboard - DagangFlow',
    'pageTitle' => 'Dashboard',
    'subtitle' => 'Selamat datang kembali'
])

@section('actions')
    <a href="/reports" class="hidden sm:block px-4 py-2 rounded-xl border border-slate-200 text-sm font-medium hover:bg-slate-50">
        Lihat Laporan
    </a>

    <a href="/sales" class="px-4 py-2 rounded-xl bg-emerald-500 text-white text-sm font-semibold hover:bg-emerald-600">
        + Tambah Penjualan
    </a>
@endsection

@section('content')
    <div class="space-y-8">

        <!-- Stats -->
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-5">
            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
                <p class="text-sm text-slate-500">Omzet Bulan Ini</p>

                <div class="flex items-center gap-4 mt-4">
                    <div class="w-16 h-16 rounded-full bg-emerald-500 text-white flex items-center justify-center shrink-0 shadow-sm">
                        <x-lucide-trending-up class="w-9 h-9" />
                    </div>

                    <h3 class="text-3xl font-bold leading-tight">
                        Rp{{ number_format($monthlyRevenue, 0, ',', '.') }}
                    </h3>
                </div>

                <div class="mt-5 flex items-center gap-2 text-sm font-semibold">
                    <span class="inline-flex items-center gap-1
                        {{ $revenueTrend['status'] === 'up' ? 'text-emerald-600' : '' }}
                        {{ $revenueTrend['status'] === 'flat' ? 'text-slate-500' : '' }}
                        {{ $revenueTrend['status'] === 'down' ? 'text-red-600' : '' }}
                    ">
                        @if($revenueTrend['status'] === 'up')
                            <x-lucide-trending-up class="w-4 h-4" />
                            {{ $revenueTrend['percent'] }}%
                        @elseif($revenueTrend['status'] === 'down')
                            <x-lucide-trending-down class="w-4 h-4" />
                            {{ $revenueTrend['percent'] }}%
                        @else
                            <x-lucide-minus class="w-4 h-4" />
                            Stabil
                        @endif
                    </span>

                    <span class="text-slate-500 font-medium">dari bulan lalu</span>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
                <p class="text-sm text-slate-500">Pengeluaran</p>

                <div class="flex items-center gap-4 mt-4">
                    <div class="w-16 h-16 rounded-full bg-amber-500 text-white flex items-center justify-center shrink-0 shadow-sm">
                        <x-lucide-wallet class="w-9 h-9" />
                    </div>

                    <h3 class="text-3xl font-bold leading-tight">
                        Rp{{ number_format($monthlyExpenseTotal, 0, ',', '.') }}
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

            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
                <p class="text-sm text-slate-500">Estimasi Laba</p>

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
                <p class="text-sm text-slate-500">Total Transaksi</p>

                <div class="flex items-center gap-4 mt-4">
                    <div class="w-16 h-16 rounded-full bg-blue-500 text-white flex items-center justify-center shrink-0 shadow-sm">
                        <x-lucide-receipt-text class="w-9 h-9" />
                    </div>

                    <h3 class="text-3xl font-bold leading-tight">
                        {{ $totalTransactions }}
                    </h3>
                </div>

                <div class="mt-5 flex items-center gap-2 text-sm font-semibold">
                    <span class="inline-flex items-center gap-1
                        {{ $transactionTrend['status'] === 'up' ? 'text-emerald-600' : '' }}
                        {{ $transactionTrend['status'] === 'flat' ? 'text-slate-500' : '' }}
                        {{ $transactionTrend['status'] === 'down' ? 'text-red-600' : '' }}
                    ">
                        @if($transactionTrend['status'] === 'up')
                            <x-lucide-trending-up class="w-4 h-4" />
                            {{ $transactionTrend['percent'] }}%
                        @elseif($transactionTrend['status'] === 'down')
                            <x-lucide-trending-down class="w-4 h-4" />
                            {{ $transactionTrend['percent'] }}%
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

            <!-- Chart -->
            <div class="xl:col-span-2 bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-lg font-bold">Performa Penjualan</h3>
                        <p class="text-sm text-slate-500">Ringkasan omzet 7 hari terakhir</p>
                    </div>

                    <a href="/sales" class="text-sm font-semibold text-emerald-600 hover:text-emerald-700">
                        Detail
                    </a>
                </div>

                <div class="h-72 rounded-2xl bg-gradient-to-br from-emerald-50 to-slate-50 border border-slate-100 flex items-end gap-4 p-6">
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

            <!-- Channel Performance -->
            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
                <h3 class="text-lg font-bold">Channel Terbaik</h3>
                <p class="text-sm text-slate-500 mb-6">Berdasarkan omzet bulan ini</p>

                @php
                    $maxChannelTotal = max($channelSummary->pluck('total')->max() ?? 0, 1);
                @endphp

                <div class="space-y-5">
                    @forelse($channelSummary as $channel => $summary)
                        @php
                            $percent = round(($summary['total'] / $maxChannelTotal) * 100);
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

                            <p class="text-xs text-slate-500 mt-1">{{ $summary['count'] }} transaksi</p>
                        </div>
                    @empty
                        <div class="p-4 rounded-xl bg-slate-50 border border-slate-100">
                            <p class="font-semibold text-slate-700">Belum ada data channel</p>
                            <p class="text-sm text-slate-500 mt-1">Data muncul setelah ada penjualan.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Bottom Grid -->
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

            <!-- Recent Sales -->
            <div class="xl:col-span-2 bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-slate-200 flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-bold">Transaksi Terbaru</h3>
                        <p class="text-sm text-slate-500">Penjualan terakhir dari berbagai channel</p>
                    </div>

                    <a href="/sales" class="text-sm font-semibold text-emerald-600">Lihat semua</a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-slate-50 text-slate-500">
                            <tr>
                                <th class="text-left px-6 py-4 font-medium">Produk</th>
                                <th class="text-left px-6 py-4 font-medium">Channel</th>
                                <th class="text-left px-6 py-4 font-medium">Total</th>
                                <th class="text-left px-6 py-4 font-medium">Status</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100">
                            @forelse($recentSales as $sale)
                                <tr>
                                    <td class="px-6 py-4">
                                        <p class="font-medium">
                                            {{ $sale->product->name ?? 'Produk terhapus' }}
                                        </p>
                                        <p class="text-xs text-slate-500">
                                            {{ $sale->quantity }} terjual
                                        </p>
                                    </td>

                                    <td class="px-6 py-4">
                                        {{ $sale->channel }}
                                    </td>

                                    <td class="px-6 py-4 font-semibold">
                                        Rp{{ number_format($sale->gross_total, 0, ',', '.') }}
                                    </td>

                                    <td class="px-6 py-4">
                                        @if($sale->status === 'Selesai')
                                            <span class="px-3 py-1 rounded-full bg-emerald-100 text-emerald-700 text-xs font-semibold">
                                                Selesai
                                            </span>
                                        @elseif($sale->status === 'Diproses')
                                            <span class="px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-xs font-semibold">
                                                Diproses
                                            </span>
                                        @else
                                            <span class="px-3 py-1 rounded-full bg-amber-100 text-amber-700 text-xs font-semibold">
                                                {{ $sale->status }}
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-10 text-center text-slate-500">
                                        Belum ada transaksi.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Low Stock -->
            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
                <h3 class="text-lg font-bold">Stok Hampir Habis</h3>
                <p class="text-sm text-slate-500 mb-6">Produk yang perlu restock</p>

                <div class="space-y-4">
                    @forelse($lowStockProducts->take(5) as $product)
                        <div class="flex items-center justify-between p-4 rounded-xl {{ $product->stock <= 0 ? 'bg-red-50 border-red-100' : 'bg-amber-50 border-amber-100' }} border">
                            <div>
                                <p class="font-semibold">{{ $product->name }}</p>
                                <p class="text-sm text-slate-500">Sisa {{ $product->stock }} pcs</p>
                            </div>

                            @if($product->stock <= 0)
                                <span class="text-xs font-bold text-red-600">Habis</span>
                            @else
                                <span class="text-xs font-bold text-amber-600">Low</span>
                            @endif
                        </div>
                    @empty
                        <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-100">
                            <p class="font-semibold text-emerald-700">Stok aman</p>
                            <p class="text-sm text-slate-500 mt-1">Belum ada produk yang perlu restock.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

    </div>
@endsection