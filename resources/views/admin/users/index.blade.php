@extends('layouts.admin', [
    'title' => 'Users - Superadmin DagangFlow',
    'pageTitle' => 'Users Management',
    'subtitle' => 'Superadmin / Users',
])

@section('content')
    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-5">
        <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm">
            <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-700 flex items-center justify-center mb-5">
                <x-lucide-users class="w-6 h-6" />
            </div>

            <p class="text-sm text-slate-500">Total Owner</p>
            <h3 class="text-4xl font-black mt-3">
                {{ number_format($totalOwners, 0, ',', '.') }}
            </h3>
        </div>

        <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm">
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center mb-5">
                <x-lucide-user-check class="w-6 h-6" />
            </div>

            <p class="text-sm text-slate-500">Aktif</p>
            <h3 class="text-4xl font-black mt-3 text-emerald-600">
                {{ number_format($activeOwners, 0, ',', '.') }}
            </h3>
        </div>

        <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm">
            <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-600 flex items-center justify-center mb-5">
                <x-lucide-user-x class="w-6 h-6" />
            </div>

            <p class="text-sm text-slate-500">Suspended</p>
            <h3 class="text-4xl font-black mt-3 text-slate-600">
                {{ number_format($suspendedOwners, 0, ',', '.') }}
            </h3>
        </div>

        <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm">
            <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center mb-5">
                <x-lucide-clock class="w-6 h-6" />
            </div>

            <p class="text-sm text-slate-500">Expired</p>
            <h3 class="text-4xl font-black mt-3 text-amber-600">
                {{ number_format($expiredOwners, 0, ',', '.') }}
            </h3>
        </div>
    </div>

    <!-- Filter -->
    <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm">
        <form action="{{ route('admin.users.index') }}" method="GET" class="grid grid-cols-1 lg:grid-cols-4 gap-4">
            <div class="lg:col-span-2">
                <label class="text-sm font-bold text-slate-700">Cari user</label>
                <input
                    type="text"
                    name="search"
                    value="{{ $search }}"
                    placeholder="Cari nama, email, bisnis, atau jenis usaha"
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
                    <option value="active" @selected($status === 'active')>Active</option>
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

                <a href="{{ route('admin.users.index') }}" class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-2xl border border-slate-200 text-sm font-bold hover:bg-slate-50 transition text-center">
                    <x-lucide-rotate-ccw class="w-4 h-4" />
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Users Table -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-200 flex items-start justify-between gap-4">
            <div>
                <h3 class="text-xl font-black">Daftar Users</h3>
                <p class="text-sm text-slate-500 mt-1">
                    Semua akun owner yang terdaftar di DagangFlow.
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
                        <th class="text-left px-6 py-4 font-semibold">Subscription</th>
                        <th class="text-left px-6 py-4 font-semibold">Last Login</th>
                        <th class="text-left px-6 py-4 font-semibold">Status</th>
                        <th class="text-right px-6 py-4 font-semibold">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                    @forelse($users as $user)
                        <tr class="hover:bg-slate-50/70">
                            <td class="px-6 py-4">
                                <p class="font-bold text-slate-900">{{ $user->name }}</p>
                                <p class="text-xs text-slate-500">{{ $user->email }}</p>
                                <p class="text-xs text-slate-400 mt-1">
                                    Daftar: {{ $user->created_at ? $user->created_at->format('d M Y') : '-' }}
                                </p>
                            </td>

                            <td class="px-6 py-4">
                                <p class="font-semibold text-slate-900">{{ $user->business_name ?: '-' }}</p>
                                <p class="text-xs text-slate-500">{{ $user->business_type ?: '-' }}</p>
                            </td>

                            <td class="px-6 py-4">
                                @php
                                    $planName = $user->plan_name ?: 'Free';
                            
                                    $planClass = match ($planName) {
                                        'Trial' => 'bg-blue-50 text-blue-700',
                                        'Bulanan' => 'bg-emerald-50 text-emerald-700',
                                        'Tahunan' => 'bg-indigo-50 text-indigo-700',
                                        default => 'bg-slate-100 text-slate-700',
                                    };
                                @endphp
                            
                                <span class="px-3 py-1 rounded-full {{ $planClass }} text-xs font-bold">
                                    {{ $planName }}
                                </span>
                            </td>

                            <td class="px-6 py-4">
                                <p class="font-semibold text-slate-900 capitalize">
                                    {{ $user->subscription_status ?: '-' }}
                                </p>
                                <p class="text-xs text-slate-500 mt-1">
                                    Expired:
                                    {{ $user->subscription_ends_at ? $user->subscription_ends_at->format('d M Y') : '-' }}
                                </p>
                            </td>

                            <td class="px-6 py-4 text-slate-600 whitespace-nowrap">
                                {{ $user->last_login_at ? $user->last_login_at->format('d M Y H:i') : 'Belum login' }}
                            </td>

                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-full {{ $user->status === 'active' ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-600' }} text-xs font-bold capitalize">
                                    {{ $user->status ?: '-' }}
                                </span>
                            </td>

                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a
                                        href="{{ route('admin.users.show', $user) }}"
                                        class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 hover:bg-emerald-100 transition flex items-center justify-center"
                                        title="Detail user"
                                    >
                                        <x-lucide-eye class="w-4 h-4" />
                                    </a>

                                    @if($user->status === 'active')
                                        <form
                                            action="{{ route('admin.users.suspend', $user) }}"
                                            method="POST"
                                            onsubmit="return confirm('Yakin ingin menonaktifkan akun user ini?')"
                                        >
                                            @csrf
                                            @method('PATCH')

                                            <button
                                                type="submit"
                                                class="w-9 h-9 rounded-xl bg-slate-100 text-slate-600 hover:bg-slate-200 transition flex items-center justify-center"
                                                title="Suspend user"
                                            >
                                                <x-lucide-user-x class="w-4 h-4" />
                                            </button>
                                        </form>
                                    @else
                                        <form
                                            action="{{ route('admin.users.activate', $user) }}"
                                            method="POST"
                                            onsubmit="return confirm('Aktifkan kembali akun user ini?')"
                                        >
                                            @csrf
                                            @method('PATCH')

                                            <button
                                                type="submit"
                                                class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 hover:bg-blue-100 transition flex items-center justify-center"
                                                title="Activate user"
                                            >
                                                <x-lucide-user-check class="w-4 h-4" />
                                            </button>
                                        </form>
                                    @endif

                                    <form
                                        action="{{ route('admin.users.destroy', $user) }}"
                                        method="POST"
                                        onsubmit="return confirm('Yakin ingin menghapus akun owner ini? Semua produk, penjualan, pengeluaran, customer, dan data bisnisnya akan ikut terhapus permanen.')"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="w-9 h-9 rounded-xl bg-red-50 text-red-600 hover:bg-red-100 transition flex items-center justify-center"
                                            title="Hapus akun"
                                        >
                                            <x-lucide-trash-2 class="w-4 h-4" />
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-slate-500">
                                Belum ada user yang sesuai filter.
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