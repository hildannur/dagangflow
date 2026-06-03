@extends('layouts.admin', [
    'title' => 'Plans - Superadmin DagangFlow',
    'pageTitle' => 'Plans Management',
    'subtitle' => 'Superadmin / Plans',
])

@section('content')
    @php
        $plans = [
            [
                'name' => 'Free',
                'code' => 'free',
                'price' => 'Rp0',
                'period' => 'Selamanya',
                'description' => 'Untuk user baru yang ingin mencoba fitur dasar DagangFlow.',
                'features' => [
                    'Dashboard dasar',
                    'Catat produk dan stok',
                    'Catat penjualan manual',
                    'Catat pengeluaran',
                    'Laporan sederhana',
                    'Maksimal 20 produk',
                    'Maksimal 50 transaksi per bulan',
                    '1 akun owner',
                ],
                'badge' => 'Gratis',
                'icon' => 'badge',
                'highlight' => false,
            ],
            [
                'name' => 'Trial',
                'code' => 'trial',
                'price' => 'Rp0',
                'period' => '14 hari',
                'description' => 'Untuk mencoba fitur premium sebelum berlangganan.',
                'features' => [
                    'Semua fitur Free',
                    'Export laporan',
                    'AI Insight',
                    'Filter laporan lengkap',
                    'Import data sederhana',
                    'Akses fitur premium sementara',
                    'Setelah habis diarahkan upgrade',
                ],
                'badge' => 'Percobaan',
                'icon' => 'sparkles',
                'highlight' => false,
            ],
            [
                'name' => 'Bulanan',
                'code' => 'monthly',
                'price' => 'Rp49.000',
                'period' => 'per bulan',
                'description' => 'Untuk owner yang ingin memakai DagangFlow secara rutin per bulan.',
                'features' => [
                    'Semua fitur Trial',
                    'Produk lebih banyak atau unlimited',
                    'Transaksi lebih banyak atau unlimited',
                    'Export laporan',
                    'AI Insight',
                    'Analisis channel penjualan',
                    'Analisis laba dan pengeluaran',
                    'Support prioritas dasar',
                ],
                'badge' => 'Recommended',
                'icon' => 'credit-card',
                'highlight' => true,
            ],
            [
                'name' => 'Tahunan',
                'code' => 'yearly',
                'price' => 'Rp499.000',
                'period' => 'per tahun',
                'description' => 'Untuk owner yang sudah yakin menggunakan DagangFlow jangka panjang.',
                'features' => [
                    'Semua fitur Bulanan',
                    'Masa aktif 365 hari',
                    'Harga lebih hemat',
                    'Prioritas support',
                    'Bonus fitur premium',
                    'Cocok untuk user aktif jangka panjang',
                ],
                'badge' => 'Hemat',
                'icon' => 'calendar-check',
                'highlight' => false,
            ],
        ];
    @endphp

    <!-- Overview -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-5">
        @foreach($plans as $plan)
            <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm">
                <div class="w-12 h-12 rounded-2xl {{ $plan['highlight'] ? 'bg-emerald-50 text-emerald-600' : 'bg-slate-100 text-slate-700' }} flex items-center justify-center mb-5">
                    @if($plan['code'] === 'free')
                        <x-lucide-badge class="w-6 h-6" />
                    @elseif($plan['code'] === 'trial')
                        <x-lucide-sparkles class="w-6 h-6" />
                    @elseif($plan['code'] === 'monthly')
                        <x-lucide-credit-card class="w-6 h-6" />
                    @else
                        <x-lucide-calendar-check class="w-6 h-6" />
                    @endif
                </div>

                <p class="text-sm text-slate-500">{{ $plan['name'] }}</p>

                <h3 class="text-3xl font-black mt-3">
                    {{ $plan['price'] }}
                </h3>

                <p class="text-xs text-slate-500 mt-2">
                    {{ $plan['period'] }}
                </p>
            </div>
        @endforeach
    </div>

    <!-- Plans Cards -->
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
        @foreach($plans as $plan)
            <div class="bg-white rounded-3xl border {{ $plan['highlight'] ? 'border-emerald-300 ring-4 ring-emerald-500/10' : 'border-slate-200' }} shadow-sm overflow-hidden">
                <div class="p-6 border-b border-slate-100">
                    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
                        <div>
                            <div class="flex items-center gap-3">
                                <h3 class="text-2xl font-black text-slate-900">
                                    {{ $plan['name'] }}
                                </h3>

                                <span class="px-3 py-1 rounded-full {{ $plan['highlight'] ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-700' }} text-xs font-bold">
                                    {{ $plan['badge'] }}
                                </span>
                            </div>

                            <p class="text-sm text-slate-500 mt-2 leading-relaxed">
                                {{ $plan['description'] }}
                            </p>
                        </div>

                        <div class="sm:text-right">
                            <p class="text-2xl font-black text-slate-900">
                                {{ $plan['price'] }}
                            </p>
                            <p class="text-xs text-slate-500 mt-1">
                                {{ $plan['period'] }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="p-6">
                    <p class="text-sm font-black text-slate-900 mb-4">
                        Fitur Paket
                    </p>

                    <div class="space-y-3">
                        @foreach($plan['features'] as $feature)
                            <div class="flex items-start gap-3">
                                <div class="w-5 h-5 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0 mt-0.5">
                                    <x-lucide-check class="w-3.5 h-3.5" />
                                </div>

                                <p class="text-sm text-slate-700 leading-relaxed">
                                    {{ $feature }}
                                </p>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="px-6 py-5 bg-slate-50 border-t border-slate-100">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                        <div>
                            <p class="text-xs text-slate-500">Kode sistem</p>
                            <p class="text-sm font-black text-slate-900 mt-1">
                                {{ $plan['code'] }}
                            </p>
                        </div>

                        <span class="inline-flex items-center justify-center gap-2 px-4 py-3 rounded-2xl bg-white border border-slate-200 text-sm font-bold text-slate-700">
                            <x-lucide-lock class="w-4 h-4" />
                            Belum bisa diedit
                        </span>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Note -->
    <div class="bg-[#0F172A] rounded-3xl p-6 text-white shadow-sm">
        <div class="flex items-start gap-4">
            <div class="w-12 h-12 rounded-2xl bg-emerald-500 flex items-center justify-center shrink-0">
                <x-lucide-info class="w-6 h-6" />
            </div>

            <div>
                <h3 class="text-xl font-black">
                    Catatan Pengembangan
                </h3>

                <p class="text-sm text-slate-300 mt-2 leading-relaxed">
                    Untuk tahap awal, halaman Plans masih statis agar aman dibuat lewat GitHub Mobile.
                    Nanti saat sudah pegang laptop, paket ini bisa dipindahkan ke database agar superadmin
                    bisa mengubah harga, fitur, dan limit langsung dari panel.
                </p>
            </div>
        </div>
    </div>
@endsection