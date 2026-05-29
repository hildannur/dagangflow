@extends('layouts.admin', [
    'title' => 'Superadmin - DagangFlow',
    'pageTitle' => 'Superadmin Dashboard',
    'subtitle' => 'Selamat datang, ' . auth()->user()->name,
])

@section('content')
    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-5 gap-5">
        <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm">
            <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-700 flex items-center justify-center mb-5">
                <x-lucide-users class="w-6 h-6" />
            </div>

            <p class="text-sm text-slate-500">Total Owner</p>
            <h3 class="text-4xl font-black mt-3">
                {{ number_format($totalUsers, 0, ',', '.') }}
            </h3>
        </div>

        <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm">
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center mb-5">
                <x-lucide-user-check class="w-6 h-6" />
            </div>

            <p class="text-sm text-slate-500">User Aktif</p>
            <h3 class="text-4xl font-black mt-3 text-emerald-600">
                {{ number_format($activeUsers, 0, ',', '.') }}
            </h3>
        </div>

        <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm">
            <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center mb-5">
                <x-lucide-user-plus class="w-6 h-6" />
            </div>

            <p class="text-sm text-slate-500">User Baru Bulan Ini</p>
            <h3 class="text-4xl font-black mt-3">
                {{ number_format($newUsersThisMonth, 0, ',', '.') }}
            </h3>
        </div>

        <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm">
            <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center mb-5">
                <x-lucide-badge class="w-6 h-6" />
            </div>

            <p class="text-sm text-slate-500">Free Plan</p>
            <h3 class="text-4xl font-black mt-3 text-blue-600">
                {{ number_format($freePlanUsers, 0, ',', '.') }}
            </h3>
        </div>

        <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm">
            <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center mb-5">
                <x-lucide-clock class="w-6 h-6" />
            </div>

            <p class="text-sm text-slate-500">Expired Soon</p>
            <h3 class="text-4xl font-black mt-3 text-amber-600">
                {{ number_format($expiredSoonUsers, 0, ',', '.') }}
            </h3>
        </div>
    </div>

    <!-- Info -->
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        <div class="xl:col-span-2 bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-slate-200 flex items-start justify-between gap-4">
                <div>
                    <h3 class="text-xl font-black">User Terbaru</h3>
                    <p class="text-sm text-slate-500 mt-1">
                        Daftar owner yang baru terdaftar di DagangFlow.
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
                            <th class="text-left px-6 py-4 font-semibold">Owner</th>
                            <th class="text-left px-6 py-4 font-semibold">Bisnis</th>
                            <th class="text-left px-6 py-4 font-semibold">Paket</th>
                            <th class="text-left px-6 py-4 font-semibold">Status</th>
                            <th class="text-left px-6 py-4 font-semibold">Expired</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100">
                        @forelse($latestUsers as $user)
                            <tr class="hover:bg-slate-50/70">
                                <td class="px-6 py-4">
                                    <p class="font-bold text-slate-900">{{ $user->name }}</p>
                                    <p class="text-xs text-slate-500">{{ $user->email }}</p>
                                </td>

                                <td class="px-6 py-4">
                                    <p class="font-semibold">{{ $user->business_name ?: '-' }}</p>
                                    <p class="text-xs text-slate-500">{{ $user->business_type ?: '-' }}</p>
                                </td>

                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 rounded-full bg-blue-50 text-blue-700 text-xs font-bold">
                                        {{ $user->plan_name ?: 'Free' }}
                                    </span>
                                </td>

                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 rounded-full {{ $user->status === 'active' ? 'bg-emerald-50 text-emerald-700' : 'bg-red-50 text-red-700' }} text-xs font-bold">
                                        {{ $user->status ?: '-' }}
                                    </span>
                                </td>

                                <td class="px-6 py-4 text-slate-600 whitespace-nowrap">
                                    {{ $user->subscription_ends_at ? $user->subscription_ends_at->format('d M Y') : '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-10 text-center text-slate-500">
                                    Belum ada user owner.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-[#0F172A] rounded-3xl p-6 text-white shadow-sm">
            <div class="w-12 h-12 rounded-2xl bg-emerald-500 text-white flex items-center justify-center mb-5">
                <x-lucide-shield-check class="w-6 h-6" />
            </div>

            <p class="text-sm text-emerald-300 font-bold">Platform Overview</p>

            <h3 class="text-2xl font-black mt-3">
                Panel internal DagangFlow
            </h3>

            <p class="text-sm text-slate-300 mt-4 leading-relaxed">
                Superadmin digunakan untuk memantau user, paket langganan, status akun, dan aktivitas platform.
                Halaman ini terpisah dari dashboard owner.
            </p>

            <div class="mt-6 space-y-3">
                <div class="p-4 rounded-2xl bg-white/10 border border-white/10">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-white/10 flex items-center justify-center">
                            <x-lucide-user-cog class="w-5 h-5 text-emerald-300" />
                        </div>

                        <div>
                            <p class="text-xs text-slate-400">Internal Users</p>
                            <p class="text-xl font-black mt-1">
                                {{ number_format($internalUsers, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="p-4 rounded-2xl bg-white/10 border border-white/10">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-white/10 flex items-center justify-center">
                            <x-lucide-code-2 class="w-5 h-5 text-emerald-300" />
                        </div>

                        <div>
                            <p class="text-xs text-slate-400">Mode</p>
                            <p class="text-xl font-black mt-1">
                                Development
                            </p>
                        </div>
                    </div>
                </div>

                <div class="p-4 rounded-2xl bg-white/10 border border-white/10">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-white/10 flex items-center justify-center">
                            <x-lucide-lock-keyhole class="w-5 h-5 text-emerald-300" />
                        </div>

                        <div>
                            <p class="text-xs text-slate-400">Access</p>
                            <p class="text-xl font-black mt-1">
                                Superadmin Only
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection