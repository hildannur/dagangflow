<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Superadmin - DagangFlow' }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-slate-100 text-slate-900">
    <div class="min-h-screen">

        <!-- Sidebar -->
        <aside class="fixed inset-y-0 left-0 w-72 bg-[#0F172A] text-white hidden lg:flex flex-col z-40">
            <div class="p-7 border-b border-white/10">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
                    <div class="w-11 h-11 rounded-2xl bg-emerald-500 flex items-center justify-center shadow-sm">
                        <x-lucide-bar-chart-3 class="w-6 h-6 text-white" />
                    </div>

                    <div>
                        <h1 class="text-xl font-black">
                            Dagang<span class="text-emerald-400">Flow</span>
                        </h1>
                        <p class="text-xs text-slate-400">Superadmin Panel</p>
                    </div>
                </a>
            </div>

            <nav class="p-5 space-y-2 flex-1">
                <a href="{{ route('admin.dashboard') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-2xl font-semibold transition
                    {{ request()->routeIs('admin.dashboard') ? 'bg-emerald-500 text-white' : 'text-slate-300 hover:bg-white/10' }}">
                    <x-lucide-layout-dashboard class="w-5 h-5" />
                    Dashboard
                </a>

                <a href="{{ route('admin.users.index') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-2xl font-semibold transition
                    {{ request()->routeIs('admin.users.*') ? 'bg-emerald-500 text-white' : 'text-slate-300 hover:bg-white/10' }}">
                    <x-lucide-users class="w-5 h-5" />
                    Users
                </a>

                <a href="{{ route('admin.subscriptions.index') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-2xl font-semibold transition
                    {{ request()->routeIs('admin.subscriptions.*') ? 'bg-emerald-500 text-white' : 'text-slate-300 hover:bg-white/10' }}">
                    <x-lucide-credit-card class="w-5 h-5" />
                    Subscriptions
                </a>

                <a href="{{ route('admin.plans.index') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-2xl font-semibold transition
                    {{ request()->routeIs('admin.plans.*') ? 'bg-emerald-500 text-white' : 'text-slate-300 hover:bg-white/10' }}">
                    <x-lucide-package class="w-5 h-5" />
                    Plans
                </a>

                <a href="{{ route('admin.support.index') }}"
                    class="flex items-center justify-between gap-3 px-4 py-3 rounded-2xl font-semibold transition
                    {{ request()->routeIs('admin.support.*') ? 'bg-emerald-500 text-white' : 'text-slate-300 hover:bg-white/10' }}">
                    <div class="flex items-center gap-3">
                        <x-lucide-headphones class="w-5 h-5" />
                        Support
                    </div>
                    
                    @if($unreadCount > 0)
                        <div class="relative flex items-center">
                            <span class="inline-flex items-center justify-center px-2 py-1 text-xs font-bold rounded-full 
                                {{ request()->routeIs('admin.support.*') ? 'bg-white/20 text-white' : 'bg-red-500 text-white' }}">
                                {{ $unreadCount > 99 ? '99+' : $unreadCount }}
                            </span>
                        </div>
                    @endif
                </a>
            </nav>
        </aside>

        <!-- Main -->
        <main class="lg:ml-72 min-h-screen">

            <!-- Header -->
            <header class="sticky top-0 z-30 bg-white/90 backdrop-blur-xl border-b border-slate-200 px-6 lg:px-10 py-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <p class="text-sm text-slate-500">
                            {{ $subtitle ?? 'Superadmin Panel' }}
                        </p>

                        <h2 class="text-3xl font-black tracking-tight mt-1">
                            {{ $pageTitle ?? 'Dashboard' }}
                        </h2>
                    </div>

                    <div class="relative">
                        <button
                            type="button"
                            onclick="toggleAdminUserMenu()"
                            class="flex items-center gap-3 px-3 py-3 rounded-2xl bg-slate-100 border border-slate-200 hover:bg-slate-50 transition"
                        >
                            <div class="w-11 h-11 rounded-full bg-emerald-500 text-white flex items-center justify-center font-black">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>

                            <div class="hidden sm:block text-left">
                                <p class="text-sm font-bold text-slate-900 leading-tight">
                                    {{ auth()->user()->name }}
                                </p>
                                <p class="text-xs text-slate-500 mt-1 capitalize">
                                    {{ auth()->user()->role }}
                                </p>
                            </div>

                            <div class="w-9 h-9 rounded-xl bg-white border border-slate-200 flex items-center justify-center text-slate-600">
                                <x-lucide-ellipsis class="w-5 h-5" />
                            </div>
                        </button>

                        <div
                            id="adminUserMenu"
                            class="hidden absolute right-0 mt-3 w-56 bg-white border border-slate-200 rounded-2xl shadow-xl overflow-hidden z-50"
                        >
                            <div class="px-4 py-4 border-b border-slate-100">
                                <p class="text-sm font-bold text-slate-900 truncate">
                                    {{ auth()->user()->name }}
                                </p>
                                <p class="text-xs text-slate-500 mt-1 truncate">
                                    {{ auth()->user()->email }}
                                </p>
                            </div>

                            <form action="{{ route('logout') }}" method="POST">
                                @csrf

                                <button
                                    type="submit"
                                    class="w-full flex items-center gap-3 px-4 py-3 text-sm font-bold text-red-600 hover:bg-red-50 transition"
                                >
                                    <x-lucide-log-out class="w-4 h-4" />
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Content -->
            <div class="p-6 lg:p-10 space-y-8">
                @if (session('success'))
                    <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-5 py-4 rounded-2xl text-sm font-bold">
                        {{ session('success') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-700 px-5 py-4 rounded-2xl text-sm font-bold">
                        {{ $errors->first() }}
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>

    <script>
        function toggleAdminUserMenu() {
            const menu = document.getElementById('adminUserMenu');

            if (!menu) return;

            menu.classList.toggle('hidden');
        }

        document.addEventListener('click', function (event) {
            const menu = document.getElementById('adminUserMenu');

            if (!menu) return;

            const button = event.target.closest('button[onclick="toggleAdminUserMenu()"]');
            const clickedInsideMenu = event.target.closest('#adminUserMenu');

            if (!button && !clickedInsideMenu) {
                menu.classList.add('hidden');
            }
        });
    </script>
</body>
</html>
