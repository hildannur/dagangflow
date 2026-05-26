<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Superadmin - DagangFlow</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-slate-100 text-slate-900">
    <div class="min-h-screen flex">

        <!-- Sidebar -->
        <aside class="w-72 bg-[#0F172A] text-white hidden lg:flex flex-col">
            <div class="p-7 border-b border-white/10">
                <div class="flex items-center gap-3">
                    <div class="w-11 h-11 rounded-2xl bg-emerald-500 flex items-center justify-center font-black text-lg">
                        D
                    </div>

                    <div>
                        <h1 class="text-xl font-black">
                            Dagang<span class="text-emerald-400">Flow</span>
                        </h1>
                        <p class="text-xs text-slate-400">Superadmin Panel</p>
                    </div>
                </div>
            </div>

            <nav class="p-5 space-y-2 flex-1">
                <a href="/admin/dashboard" class="flex items-center gap-3 px-4 py-3 rounded-2xl bg-emerald-500 text-white font-semibold">
                    <span>📊</span>
                    Dashboard
                </a>

                <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-slate-300 hover:bg-white/10 font-semibold">
                    <span>👥</span>
                    Users
                </a>

                <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-slate-300 hover:bg-white/10 font-semibold">
                    <span>💳</span>
                    Subscriptions
                </a>

                <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-slate-300 hover:bg-white/10 font-semibold">
                    <span>📦</span>
                    Plans
                </a>

                <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-slate-300 hover:bg-white/10 font-semibold">
                    <span>🛟</span>
                    Support
                </a>
            </nav>

            <div class="p-5 border-t border-white/10">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf

                    <button class="w-full px-4 py-3 rounded-2xl bg-white/10 hover:bg-white/15 text-sm font-bold text-white">
                        Logout
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main -->
        <main class="flex-1">
            <header class="bg-white border-b border-slate-200 px-6 lg:px-10 py-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <p class="text-sm text-slate-500">
                            Selamat datang, {{ auth()->user()->name }}
                        </p>
                        <h2 class="text-3xl font-black tracking-tight mt-1">
                            Superadmin Dashboard
                        </h2>
                    </div>

                    <div class="px-4 py-3 rounded-2xl bg-slate-100 border border-slate-200">
                        <p class="text-xs text-slate-500">Role</p>
                        <p class="text-sm font-bold text-emerald-600">
                            {{ auth()->user()->role }}
                        </p>
                    </div>
                </div>
            </header>

            <div class="p-6 lg:p-10 space-y-8">

                <!-- Stats -->
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-5 gap-5">
                    <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm">
                        <p class="text-sm text-slate-500">Total Owner</p>
                        <h3 class="text-4xl font-black mt-3">{{ number_format($totalUsers, 0, ',', '.') }}</h3>
                    </div>

                    <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm">
                        <p class="text-sm text-slate-500">User Aktif</p>
                        <h3 class="text-4xl font-black mt-3 text-emerald-600">{{ number_format($activeUsers, 0, ',', '.') }}</h3>
                    </div>

                    <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm">
                        <p class="text-sm text-slate-500">User Baru Bulan Ini</p>
                        <h3 class="text-4xl font-black mt-3">{{ number_format($newUsersThisMonth, 0, ',', '.') }}</h3>
                    </div>

                    <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm">
                        <p class="text-sm text-slate-500">Free Plan</p>
                        <h3 class="text-4xl font-black mt-3 text-blue-600">{{ number_format($freePlanUsers, 0, ',', '.') }}</h3>
                    </div>

                    <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm">
                        <p class="text-sm text-slate-500">Expired Soon</p>
                        <h3 class="text-4xl font-black mt-3 text-amber-600">{{ number_format($expiredSoonUsers, 0, ',', '.') }}</h3>
                    </div>
                </div>

                <!-- Info -->
                <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
                    <div class="xl:col-span-2 bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
                        <div class="p-6 border-b border-slate-200">
                            <h3 class="text-xl font-black">User Terbaru</h3>
                            <p class="text-sm text-slate-500 mt-1">Daftar owner yang baru terdaftar di DagangFlow.</p>
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
                                        <tr>
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

                                            <td class="px-6 py-4 text-slate-600">
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
                                <p class="text-xs text-slate-400">Internal Users</p>
                                <p class="text-xl font-black mt-1">{{ number_format($internalUsers, 0, ',', '.') }}</p>
                            </div>

                            <div class="p-4 rounded-2xl bg-white/10 border border-white/10">
                                <p class="text-xs text-slate-400">Mode</p>
                                <p class="text-xl font-black mt-1">Development</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </main>
    </div>
</body>
</html>