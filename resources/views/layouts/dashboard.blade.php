<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>{{ $title ?? 'DagangFlow Dashboard' }}</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    
    <body class="bg-[#F8FAF7] text-slate-900">
        <div class="min-h-screen bg-[#F8FAF7]">
    
            <!-- Mobile Overlay -->
            <div
                id="mobileSidebarOverlay"
                onclick="closeMobileSidebar()"
                class="fixed inset-0 bg-slate-900/50 z-40 hidden lg:hidden"
            ></div>
    
            @include('partials.sidebar')
    
            <main class="min-h-screen lg:pl-72">
                <header class="sticky top-0 z-30 bg-white/95 backdrop-blur border-b border-slate-200 px-4 sm:px-6 lg:px-10 py-4 lg:py-5">
                    <div class="flex items-center justify-between gap-4">
    
                        <div class="flex items-center gap-3 min-w-0">
                            <button
                                type="button"
                                onclick="openMobileSidebar()"
                                class="lg:hidden w-10 h-10 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center shrink-0 hover:bg-slate-200 transition"
                            >
                                <x-lucide-menu class="w-5 h-5" />
                            </button>
    
                            <div class="min-w-0">
                                <p class="text-xs sm:text-sm text-slate-500">
                                    Selamat datang kembali, {{ auth()->user()->name ?? 'Owner' }}
                                </p>
    
                                <h2 class="text-xl sm:text-2xl font-bold text-slate-900 truncate">
                                    {{ $pageTitle ?? 'Dashboard' }}
                                </h2>
    
                                @isset($subtitle)
                                    <p class="hidden sm:block text-sm text-slate-500 mt-1 truncate">
                                        {{ $subtitle }}
                                    </p>
                                @endisset
                            </div>
                        </div>
    
                        <div class="flex items-center gap-3 shrink-0">
                            <div class="hidden xl:flex items-center gap-3">
                                @yield('actions')
                            </div>
    
                            <div class="relative">
                                <button
                                    type="button"
                                    onclick="toggleUserMenu()"
                                    class="flex items-center gap-3 pl-2 sm:pl-3 pr-2 py-2 rounded-2xl border border-slate-200 bg-white hover:bg-slate-50 transition"
                                >
                                    <div class="w-10 h-10 rounded-full bg-emerald-500 text-white flex items-center justify-center font-bold shrink-0">
                                        {{ strtoupper(substr(auth()->user()->name ?? 'O', 0, 1)) }}
                                    </div>
    
                                    <div class="hidden md:block text-left leading-tight">
                                        <p class="text-sm font-bold text-slate-900 max-w-36 truncate">
                                            {{ auth()->user()->name ?? 'Owner' }}
                                        </p>
    
                                        <p class="text-xs text-slate-500 max-w-36 truncate">
                                            {{ auth()->user()->business_name ?? 'Bisnis aktif' }}
                                        </p>
                                    </div>
    
                                    <div class="w-9 h-9 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center shrink-0">
                                        <x-lucide-more-horizontal class="w-5 h-5" />
                                    </div>
                                </button>
    
                                <div
                                    id="userMenuDropdown"
                                    class="hidden absolute right-0 mt-3 w-64 rounded-2xl bg-white border border-slate-200 shadow-xl overflow-hidden z-50"
                                >
                                    <div class="p-4 border-b border-slate-100">
                                        <div class="flex items-center gap-3">
                                            <div class="w-11 h-11 rounded-full bg-emerald-500 text-white flex items-center justify-center font-bold shrink-0">
                                                {{ strtoupper(substr(auth()->user()->name ?? 'O', 0, 1)) }}
                                            </div>
    
                                            <div class="min-w-0">
                                                <p class="font-bold text-slate-900 truncate">
                                                    {{ auth()->user()->name ?? 'Owner' }}
                                                </p>
    
                                                <p class="text-sm text-slate-500 truncate">
                                                    {{ auth()->user()->business_name ?? 'Bisnis aktif' }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
    
                                    <div class="p-2">
                                        <a href="/sales" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium text-slate-700 hover:bg-slate-50">
                                            <x-lucide-plus-circle class="w-5 h-5 text-slate-500" />
                                            <span>Tambah Penjualan</span>
                                        </a>
                                    
                                        <a href="/reports" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium text-slate-700 hover:bg-slate-50">
                                            <x-lucide-chart-column-big class="w-5 h-5 text-slate-500" />
                                            <span>Lihat Laporan</span>
                                        </a>
                                    
                                        <a href="{{ route('reports.export') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium text-slate-700 hover:bg-slate-50">
                                            <x-lucide-download class="w-5 h-5 text-slate-500" />
                                            <span>Export Laporan</span>
                                        </a>
                                    
                                        <a href="/help" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium text-slate-700 hover:bg-slate-50">
                                            <x-lucide-circle-help class="w-5 h-5 text-slate-500" />
                                            <span>Pusat Bantuan</span>
                                        </a>
                                    </div>
    
                                    <div class="p-2 border-t border-slate-100">
                                        <form action="/logout" method="POST">
                                            @csrf
    
                                            <button
                                                type="submit"
                                                class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold text-red-600 hover:bg-red-50"
                                            >
                                                <x-lucide-log-out class="w-5 h-5" />
                                                <span>Logout</span>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </header>
    
                <section class="p-4 sm:p-6 lg:p-10">
                    @yield('content')
                </section>
            </main>
    
        </div>
    
        <script>
            function toggleUserMenu() {
                const dropdown = document.getElementById('userMenuDropdown');
    
                if (!dropdown) return;
    
                dropdown.classList.toggle('hidden');
            }
    
            function openMobileSidebar() {
                const sidebar = document.getElementById('mobileSidebar');
                const overlay = document.getElementById('mobileSidebarOverlay');
    
                if (!sidebar || !overlay) return;
    
                sidebar.classList.remove('-translate-x-full');
                sidebar.classList.add('translate-x-0');
    
                overlay.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
            }
    
            function closeMobileSidebar() {
                const sidebar = document.getElementById('mobileSidebar');
                const overlay = document.getElementById('mobileSidebarOverlay');
    
                if (!sidebar || !overlay) return;
    
                sidebar.classList.add('-translate-x-full');
                sidebar.classList.remove('translate-x-0');
    
                overlay.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }
    
            document.addEventListener('click', function (event) {
                const dropdown = document.getElementById('userMenuDropdown');
    
                if (!dropdown) return;
    
                const button = event.target.closest('button[onclick="toggleUserMenu()"]');
                const menu = event.target.closest('#userMenuDropdown');
    
                if (!button && !menu) {
                    dropdown.classList.add('hidden');
                }
            });
    
            document.addEventListener('keydown', function (event) {
                if (event.key === 'Escape') {
                    const dropdown = document.getElementById('userMenuDropdown');
    
                    if (dropdown) {
                        dropdown.classList.add('hidden');
                    }
    
                    closeMobileSidebar();
                }
            });
        </script>
    </body>
</html>