@extends('layouts.admin', [
    'title' => 'Subscriptions - Superadmin DagangFlow',
    'pageTitle' => 'Subscription Management',
    'subtitle' => 'Superadmin / Subscriptions',
])

@section('content')
    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-5">
        <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm">
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center mb-5">
                <x-lucide-badge-check class="w-6 h-6" />
            </div>

            <p class="text-sm text-slate-500">Active</p>
            <h3 class="text-4xl font-black mt-3 text-emerald-600">
                {{ number_format($activeSubscriptions, 0, ',', '.') }}
            </h3>
        </div>

        <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm">
            <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center mb-5">
                <x-lucide-sparkles class="w-6 h-6" />
            </div>

            <p class="text-sm text-slate-500">Trial</p>
            <h3 class="text-4xl font-black mt-3 text-blue-600">
                {{ number_format($trialSubscriptions, 0, ',', '.') }}
            </h3>
        </div>

        <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm">
            <div class="w-12 h-12 rounded-2xl bg-red-50 text-red-600 flex items-center justify-center mb-5">
                <x-lucide-badge-x class="w-6 h-6" />
            </div>

            <p class="text-sm text-slate-500">Expired</p>
            <h3 class="text-4xl font-black mt-3 text-red-600">
                {{ number_format($expiredSubscriptions, 0, ',', '.') }}
            </h3>
        </div>

        <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm">
            <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center mb-5">
                <x-lucide-clock class="w-6 h-6" />
            </div>

            <p class="text-sm text-slate-500">Expired Soon</p>
            <h3 class="text-4xl font-black mt-3 text-amber-600">
                {{ number_format($expiredSoonSubscriptions, 0, ',', '.') }}
            </h3>
        </div>
    </div>

    <!-- Filter -->
    <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm">
        <form action="{{ route('admin.subscriptions.index') }}" method="GET" class="grid grid-cols-1 lg:grid-cols-4 gap-4">
            <div class="lg:col-span-2">
                <label class="text-sm font-bold text-slate-700">Cari user</label>
                <input
                    type="text"
                    name="search"
                    value="{{ $search }}"
                    placeholder="Cari nama, email, atau nama bisnis"
                    class="mt-2 w-full px-4 py-3 rounded-2xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500"
                >
            </div>

            <div>
                <label class="text-sm font-bold text-slate-700">Status</label>
                <select
                    name="status"
                    class="mt-2 w-full px-4 py-3 rounded-2xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500"
                >
                    <option value="">Semua status</option>
                    <option value="trial" @selected($status === 'trial')>Trial</option>
                    <option value="active" @selected($status === 'active')>Active</option>
                    <option value="expired" @selected($status === 'expired')>Expired</option>
                    <option value="cancelled" @selected($status === 'cancelled')>Cancelled</option>
                    <option value="suspended" @selected($status === 'suspended')>Suspended</option>
                </select>
            </div>

            <div>
                <label class="text-sm font-bold text-slate-700">Paket</label>
                <select
                    name="plan"
                    class="mt-2 w-full px-4 py-3 rounded-2xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500"
                >
                    <option value="">Semua paket</option>
                    @foreach($plans as $planOption)
                        <option value="{{ $planOption }}" @selected($plan === $planOption)>
                            {{ $planOption }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="lg:col-span-4 flex flex-col sm:flex-row gap-3">
                <button class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-2xl bg-emerald-500 text-white text-sm font-bold hover:bg-emerald-600 transition">
                    <x-lucide-filter class="w-4 h-4" />
                    Terapkan Filter
                </button>

                <a href="{{ route('admin.subscriptions.index') }}" class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-2xl border border-slate-200 text-sm font-bold hover:bg-slate-50 transition text-center">
                    <x-lucide-rotate-ccw class="w-4 h-4" />
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-200 flex items-start justify-between gap-4">
            <div>
                <h3 class="text-xl font-black">Daftar Subscription</h3>
                <p class="text-sm text-slate-500 mt-1">
                    Pantau paket langganan dan tanggal expired semua owner.
                </p>
            </div>

            <div class="w-11 h-11 rounded-2xl bg-slate-100 text-slate-700 flex items-center justify-center shrink-0">
                <x-lucide-list class="w-5 h-5" />
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 text-slate-500">
                    <tr>
                        <th class="text-left px-6 py-4 font-semibold">User</th>
                        <th class="text-left px-6 py-4 font-semibold">Bisnis</th>
                        <th class="text-left px-6 py-4 font-semibold">Paket</th>
                        <th class="text-left px-6 py-4 font-semibold">Status</th>
                        <th class="text-left px-6 py-4 font-semibold">Mulai</th>
                        <th class="text-left px-6 py-4 font-semibold">Berakhir</th>
                        <th class="text-left px-6 py-4 font-semibold">Sisa Hari</th>
                        <th class="text-right px-6 py-4 font-semibold">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                    @forelse($users as $user)
                        @php
                            $daysLeft = $user->subscription_ends_at
                                ? now()->startOfDay()->diffInDays($user->subscription_ends_at->copy()->startOfDay(), false)
                                : null;

                            $statusClass = 'bg-slate-100 text-slate-700';

                            if ($user->subscription_status === 'active') {
                                $statusClass = 'bg-emerald-50 text-emerald-700';
                            } elseif ($user->subscription_status === 'trial') {
                                $statusClass = 'bg-blue-50 text-blue-700';
                            } elseif ($user->subscription_status === 'expired') {
                                $statusClass = 'bg-red-50 text-red-700';
                            }
                        @endphp

                        <tr class="hover:bg-slate-50/70">
                            <td class="px-6 py-4">
                                <p class="font-bold text-slate-900">{{ $user->name }}</p>
                                <p class="text-xs text-slate-500">{{ $user->email }}</p>
                            </td>

                            <td class="px-6 py-4">
                                <p class="font-semibold text-slate-900">{{ $user->business_name ?: '-' }}</p>
                                <p class="text-xs text-slate-500">{{ $user->business_type ?: '-' }}</p>
                            </td>

                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-full bg-blue-50 text-blue-700 text-xs font-bold">
                                    {{ $user->plan_name ?: 'Free' }}
                                </span>
                            </td>

                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-full {{ $statusClass }} text-xs font-bold capitalize">
                                    {{ $user->subscription_status ?: '-' }}
                                </span>
                            </td>

                            <td class="px-6 py-4 text-slate-600 whitespace-nowrap">
                                {{ $user->subscription_started_at ? $user->subscription_started_at->format('d M Y') : '-' }}
                            </td>

                            <td class="px-6 py-4 text-slate-600 whitespace-nowrap">
                                {{ $user->subscription_ends_at ? $user->subscription_ends_at->format('d M Y') : '-' }}
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                @if(is_null($daysLeft))
                                    <span class="text-slate-400">-</span>
                                @elseif($daysLeft < 0)
                                    <span class="font-bold text-red-600">Expired</span>
                                @elseif($daysLeft <= 7)
                                    <span class="font-bold text-amber-600">{{ $daysLeft }} hari</span>
                                @else
                                    <span class="font-bold text-emerald-600">{{ $daysLeft }} hari</span>
                                @endif
                            </td>

                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('admin.users.show', $user) }}" class="inline-flex items-center justify-end gap-2 text-sm font-bold text-emerald-600 hover:text-emerald-700">
                                    Kelola
                                    <x-lucide-arrow-right class="w-4 h-4" />
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-slate-500">
                                Belum ada subscription yang sesuai filter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 border-t border-slate-100">
            {{ $users->links() }}
        </div>
    </div>
@endsection