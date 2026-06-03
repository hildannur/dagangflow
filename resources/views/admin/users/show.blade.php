@extends('layouts.admin', [
    'title' => 'Detail User - Superadmin DagangFlow',
    'pageTitle' => 'Detail User',
    'subtitle' => 'Superadmin / Users / Detail',
])

@section('content')
    @php
        $planName = $user->plan_name ?: 'Free';

        $planClass = match ($planName) {
            'Trial' => 'bg-blue-50 text-blue-700',
            'Bulanan' => 'bg-emerald-50 text-emerald-700',
            'Tahunan' => 'bg-indigo-50 text-indigo-700',
            default => 'bg-slate-100 text-slate-700',
        };

        $subscriptionStatus = $user->subscription_status ?: '-';

        $subscriptionClass = match ($subscriptionStatus) {
            'trial' => 'bg-blue-50 text-blue-700',
            'active' => 'bg-emerald-50 text-emerald-700',
            'expired' => 'bg-red-50 text-red-700',
            'cancelled' => 'bg-slate-100 text-slate-700',
            'suspended' => 'bg-slate-100 text-slate-700',
            default => 'bg-slate-100 text-slate-700',
        };

        $statusClass = $user->status === 'active'
            ? 'bg-emerald-50 text-emerald-700'
            : 'bg-slate-100 text-slate-600';

        $quickPlans = [
            [
                'label' => '+14 Hari Trial',
                'days' => 14,
                'plan' => 'Trial',
            ],
            [
                'label' => '+30 Hari Bulanan',
                'days' => 30,
                'plan' => 'Bulanan',
            ],
            [
                'label' => '+365 Hari Tahunan',
                'days' => 365,
                'plan' => 'Tahunan',
            ],
        ];
    @endphp

    <!-- Header Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <a href="{{ route('admin.users.index') }}"
            class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-2xl border border-slate-200 bg-white text-sm font-bold hover:bg-slate-50 transition">
            <x-lucide-arrow-left class="w-4 h-4" />
            Kembali ke Users
        </a>

        <div class="flex flex-col sm:flex-row gap-3">
            @if($user->status === 'active')
                <form action="{{ route('admin.users.suspend', $user) }}" method="POST"
                    onsubmit="return confirm('Yakin ingin menonaktifkan akun user ini?')">
                    @csrf
                    @method('PATCH')

                    <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-3 rounded-2xl bg-slate-100 border border-slate-200 text-slate-700 text-sm font-bold hover:bg-slate-200 transition">
                        <x-lucide-user-x class="w-4 h-4" />
                        Suspend User
                    </button>
                </form>
            @else
                <form action="{{ route('admin.users.activate', $user) }}" method="POST"
                    onsubmit="return confirm('Aktifkan kembali akun user ini?')">
                    @csrf
                    @method('PATCH')

                    <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-3 rounded-2xl bg-blue-500 text-white text-sm font-bold hover:bg-blue-600 transition">
                        <x-lucide-user-check class="w-4 h-4" />
                        Activate User
                    </button>
                </form>
            @endif

            <form action="{{ route('admin.users.destroy', $user) }}" method="POST"
                onsubmit="return confirm('Yakin ingin menghapus akun owner ini? Semua data bisnisnya akan ikut terhapus permanen.')">
                @csrf
                @method('DELETE')

                <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-3 rounded-2xl bg-red-50 border border-red-200 text-red-700 text-sm font-bold hover:bg-red-100 transition">
                    <x-lucide-trash-2 class="w-4 h-4" />
                    Hapus Akun
                </button>
            </form>
        </div>
    </div>

    <!-- Profile -->
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        <div class="xl:col-span-2 bg-white rounded-3xl p-6 border border-slate-200 shadow-sm">
            <div class="flex items-start gap-5">
                <div class="w-16 h-16 rounded-full bg-emerald-500 text-white flex items-center justify-center text-2xl font-black shrink-0">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>

                <div class="min-w-0">
                    <h3 class="text-2xl font-black text-slate-900">
                        {{ $user->name }}
                    </h3>

                    <p class="text-sm text-slate-500 mt-1">
                        {{ $user->email }}
                    </p>

                    <div class="flex flex-wrap gap-2 mt-4">
                        <span class="px-3 py-1 rounded-full {{ $planClass }} text-xs font-bold">
                            {{ $planName }}
                        </span>

                        <span class="px-3 py-1 rounded-full {{ $statusClass }} text-xs font-bold capitalize">
                            {{ $user->status ?: '-' }}
                        </span>

                        <span class="px-3 py-1 rounded-full {{ $subscriptionClass }} text-xs font-bold capitalize">
                            {{ $subscriptionStatus }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-8">
                <div class="rounded-2xl bg-slate-50 border border-slate-100 p-5">
                    <p class="text-xs text-slate-500">Nama Bisnis</p>
                    <p class="font-bold text-slate-900 mt-1">
                        {{ $user->business_name ?: '-' }}
                    </p>
                </div>

                <div class="rounded-2xl bg-slate-50 border border-slate-100 p-5">
                    <p class="text-xs text-slate-500">Jenis Usaha</p>
                    <p class="font-bold text-slate-900 mt-1">
                        {{ $user->business_type ?: '-' }}
                    </p>
                </div>

                <div class="rounded-2xl bg-slate-50 border border-slate-100 p-5">
                    <p class="text-xs text-slate-500">Tanggal Daftar</p>
                    <p class="font-bold text-slate-900 mt-1">
                        {{ $user->created_at ? $user->created_at->format('d M Y H:i') : '-' }}
                    </p>
                </div>

                <div class="rounded-2xl bg-slate-50 border border-slate-100 p-5">
                    <p class="text-xs text-slate-500">Terakhir Login</p>
                    <p class="font-bold text-slate-900 mt-1">
                        {{ $user->last_login_at ? $user->last_login_at->format('d M Y H:i') : 'Belum login' }}
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-[#0F172A] rounded-3xl p-6 text-white shadow-sm">
            <div class="w-12 h-12 rounded-2xl bg-emerald-500 flex items-center justify-center mb-5">
                <x-lucide-credit-card class="w-6 h-6" />
            </div>

            <p class="text-sm text-emerald-300 font-bold">Subscription</p>

            <h3 class="text-2xl font-black mt-3">
                {{ $planName }}
            </h3>

            <div class="mt-6 space-y-3">
                <div class="p-4 rounded-2xl bg-white/10 border border-white/10">
                    <p class="text-xs text-slate-400">Status</p>
                    <p class="text-lg font-black mt-1 capitalize">
                        {{ $subscriptionStatus }}
                    </p>
                </div>

                <div class="p-4 rounded-2xl bg-white/10 border border-white/10">
                    <p class="text-xs text-slate-400">Mulai</p>
                    <p class="text-lg font-black mt-1">
                        {{ $user->subscription_started_at ? $user->subscription_started_at->format('d M Y') : '-' }}
                    </p>
                </div>

                <div class="p-4 rounded-2xl bg-white/10 border border-white/10">
                    <p class="text-xs text-slate-400">Berakhir</p>
                    <p class="text-lg font-black mt-1">
                        {{ $user->subscription_ends_at ? $user->subscription_ends_at->format('d M Y') : '-' }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Subscription Management -->
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        <div class="xl:col-span-2 bg-white rounded-3xl p-6 border border-slate-200 shadow-sm">
            <div class="flex items-start justify-between gap-4 mb-6">
                <div>
                    <h3 class="text-xl font-black">Kelola Subscription</h3>
                    <p class="text-sm text-slate-500 mt-1">
                        Ubah paket dan masa aktif langganan user secara manual.
                    </p>
                </div>

                <div class="w-11 h-11 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                    <x-lucide-credit-card class="w-5 h-5" />
                </div>
            </div>

            <form action="{{ route('admin.users.subscription.update', $user) }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @csrf
                @method('PATCH')

                <div>
                    <label class="text-sm font-bold text-slate-700">Paket</label>
                    <select
                        name="plan_name"
                        class="mt-2 w-full px-4 py-3 rounded-2xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500"
                        required
                    >
                        <option value="Free" @selected($user->plan_name === 'Free')>Free</option>
                        <option value="Trial" @selected($user->plan_name === 'Trial')>Trial</option>
                        <option value="Bulanan" @selected($user->plan_name === 'Bulanan')>Bulanan</option>
                        <option value="Tahunan" @selected($user->plan_name === 'Tahunan')>Tahunan</option>
                    </select>
                </div>

                <div>
                    <label class="text-sm font-bold text-slate-700">Status Subscription</label>
                    <select
                        name="subscription_status"
                        class="mt-2 w-full px-4 py-3 rounded-2xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500"
                        required
                    >
                        <option value="trial" @selected($user->subscription_status === 'trial')>Trial</option>
                        <option value="active" @selected($user->subscription_status === 'active')>Active</option>
                        <option value="expired" @selected($user->subscription_status === 'expired')>Expired</option>
                        <option value="cancelled" @selected($user->subscription_status === 'cancelled')>Cancelled</option>
                        <option value="suspended" @selected($user->subscription_status === 'suspended')>Suspended</option>
                    </select>
                </div>

                <div>
                    <label class="text-sm font-bold text-slate-700">Mulai Subscription</label>
                    <input
                        type="date"
                        name="subscription_started_at"
                        value="{{ $user->subscription_started_at ? $user->subscription_started_at->format('Y-m-d') : now()->toDateString() }}"
                        class="mt-2 w-full px-4 py-3 rounded-2xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500"
                    >
                </div>

                <div>
                    <label class="text-sm font-bold text-slate-700">Berakhir Subscription</label>
                    <input
                        type="date"
                        name="subscription_ends_at"
                        value="{{ $user->subscription_ends_at ? $user->subscription_ends_at->format('Y-m-d') : '' }}"
                        class="mt-2 w-full px-4 py-3 rounded-2xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500"
                    >
                </div>

                <div class="md:col-span-2">
                    <button class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-2xl bg-emerald-500 text-white text-sm font-bold hover:bg-emerald-600 transition">
                        <x-lucide-save class="w-4 h-4" />
                        Simpan Subscription
                    </button>
                </div>
            </form>
        </div>

        <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm">
            <div class="flex items-start justify-between gap-4 mb-6">
                <div>
                    <h3 class="text-xl font-black">Perpanjang Cepat</h3>
                    <p class="text-sm text-slate-500 mt-1">
                        Pilih paket cepat. Paket user akan otomatis ikut berubah.
                    </p>
                </div>

                <div class="w-11 h-11 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                    <x-lucide-calendar-plus class="w-5 h-5" />
                </div>
            </div>

            <div class="grid grid-cols-1 gap-3">
                @foreach($quickPlans as $quickPlan)
                    @php
                        $isCurrentPlan = $planName === $quickPlan['plan'];

                        $buttonClass = $isCurrentPlan
                            ? 'bg-emerald-500 text-white border-emerald-500 hover:bg-emerald-600'
                            : 'bg-white text-slate-900 border-slate-200 hover:bg-slate-50';
                    @endphp

                    <form action="{{ route('admin.users.subscription.extend', $user) }}" method="POST">
                        @csrf
                        @method('PATCH')

                        <input type="hidden" name="days" value="{{ $quickPlan['days'] }}">

                        <button
                            type="submit"
                            class="w-full px-5 py-3 rounded-2xl border text-sm font-black transition {{ $buttonClass }}"
                        >
                            <span class="flex items-center justify-center gap-2">
                                @if($isCurrentPlan)
                                    <x-lucide-check class="w-4 h-4" />
                                @endif

                                {{ $quickPlan['label'] }}
                            </span>
                        </button>
                    </form>
                @endforeach
            </div>

            <div class="mt-5 rounded-2xl bg-slate-50 border border-slate-100 p-4">
                <p class="text-xs text-slate-500">Paket aktif saat ini</p>
                <p class="text-sm font-black text-slate-900 mt-1">
                    {{ $planName }}
                </p>
            </div>

            <div class="mt-3 rounded-2xl bg-slate-50 border border-slate-100 p-4">
                <p class="text-xs text-slate-500">Expired saat ini</p>
                <p class="text-sm font-black text-slate-900 mt-1">
                    {{ $user->subscription_ends_at ? $user->subscription_ends_at->format('d M Y') : 'Tidak ada tanggal expired' }}
                </p>
            </div>
        </div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-5 gap-5">
        <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm">
            <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-700 flex items-center justify-center mb-5">
                <x-lucide-package class="w-6 h-6" />
            </div>

            <p class="text-sm text-slate-500">Produk</p>
            <h3 class="text-4xl font-black mt-3">
                {{ number_format($totalProducts, 0, ',', '.') }}
            </h3>
        </div>

        <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm">
            <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center mb-5">
                <x-lucide-receipt-text class="w-6 h-6" />
            </div>

            <p class="text-sm text-slate-500">Transaksi</p>
            <h3 class="text-4xl font-black mt-3">
                {{ number_format($totalSales, 0, ',', '.') }}
            </h3>
        </div>

        <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm">
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center mb-5">
                <x-lucide-trending-up class="w-6 h-6" />
            </div>

            <p class="text-sm text-slate-500">Omzet</p>
            <h3 class="text-2xl font-black mt-3">
                Rp{{ number_format($totalRevenue, 0, ',', '.') }}
            </h3>
        </div>

        <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm">
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center mb-5">
                <x-lucide-hand-coins class="w-6 h-6" />
            </div>

            <p class="text-sm text-slate-500">Uang Bersih</p>
            <h3 class="text-2xl font-black mt-3 text-emerald-600">
                Rp{{ number_format($totalNetRevenue, 0, ',', '.') }}
            </h3>
        </div>

        <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm">
            <div class="w-12 h-12 rounded-2xl bg-red-50 text-red-600 flex items-center justify-center mb-5">
                <x-lucide-wallet class="w-6 h-6" />
            </div>

            <p class="text-sm text-slate-500">Pengeluaran</p>
            <h3 class="text-2xl font-black mt-3 text-red-600">
                Rp{{ number_format($totalExpenses, 0, ',', '.') }}
            </h3>
        </div>
    </div>

    <!-- Tables -->
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-slate-200 flex items-start justify-between gap-4">
                <div>
                    <h3 class="text-xl font-black">Produk Terbaru</h3>
                    <p class="text-sm text-slate-500 mt-1">
                        Produk terbaru dari owner ini.
                    </p>
                </div>

                <div class="w-11 h-11 rounded-2xl bg-slate-100 text-slate-700 flex items-center justify-center shrink-0">
                    <x-lucide-package class="w-5 h-5" />
                </div>
            </div>

            <div class="divide-y divide-slate-100">
                @forelse($latestProducts as $product)
                    <div class="p-5 flex items-center justify-between gap-4">
                        <div>
                            <p class="font-bold text-slate-900">{{ $product->name }}</p>
                            <p class="text-xs text-slate-500 mt-1">{{ $product->category ?: '-' }}</p>
                        </div>

                        <div class="text-right">
                            <p class="font-bold">Stok {{ number_format($product->stock, 0, ',', '.') }}</p>
                            <p class="text-xs text-slate-500 mt-1">
                                Rp{{ number_format($product->selling_price, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-slate-500">
                        Belum ada produk.
                    </div>
                @endforelse
            </div>
        </div>

        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-slate-200 flex items-start justify-between gap-4">
                <div>
                    <h3 class="text-xl font-black">Transaksi Terbaru</h3>
                    <p class="text-sm text-slate-500 mt-1">
                        Transaksi terbaru dari owner ini.
                    </p>
                </div>

                <div class="w-11 h-11 rounded-2xl bg-slate-100 text-slate-700 flex items-center justify-center shrink-0">
                    <x-lucide-receipt-text class="w-5 h-5" />
                </div>
            </div>

            <div class="divide-y divide-slate-100">
                @forelse($latestSales as $sale)
                    <div class="p-5 flex items-center justify-between gap-4">
                        <div>
                            <p class="font-bold text-slate-900">
                                {{ $sale->product->name ?? 'Produk terhapus' }}
                            </p>
                            <p class="text-xs text-slate-500 mt-1">
                                {{ $sale->channel }} • {{ $sale->sale_date ? \Carbon\Carbon::parse($sale->sale_date)->format('d M Y') : '-' }}
                            </p>
                        </div>

                        <div class="text-right">
                            <p class="font-bold">
                                Rp{{ number_format($sale->gross_total, 0, ',', '.') }}
                            </p>
                            <p class="text-xs text-emerald-600 mt-1">
                                Bersih Rp{{ number_format($sale->net_total, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-slate-500">
                        Belum ada transaksi.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection