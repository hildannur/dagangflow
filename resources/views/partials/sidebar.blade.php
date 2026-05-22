<!-- Desktop Sidebar -->
<aside class="fixed inset-y-0 left-0 z-40 w-72 bg-[#0F172A] text-white hidden lg:flex flex-col">
    <div class="px-8 py-7 border-b border-white/10 shrink-0">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-2xl bg-emerald-500/15 border border-emerald-400/20 flex items-center justify-center">
                <x-lucide-chart-no-axes-combined class="w-5 h-5 text-emerald-400" />
            </div>

            <div>
                <h1 class="text-2xl font-bold tracking-tight">
                    Dagang<span class="text-emerald-400">Flow</span>
                </h1>
                <p class="text-sm text-slate-400 mt-1">Business Dashboard</p>
            </div>
        </div>
    </div>

    <nav class="flex-1 px-5 py-6 space-y-2 overflow-y-auto">
        <a href="/dashboard" class="flex items-center gap-3 px-4 py-3 rounded-xl transition {{ request()->is('dashboard') ? 'bg-emerald-500 text-white font-medium shadow-lg shadow-emerald-500/20' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
            <x-lucide-layout-dashboard class="w-5 h-5 shrink-0" />
            <span>Dashboard</span>
        </a>

        <a href="/products" class="flex items-center gap-3 px-4 py-3 rounded-xl transition {{ request()->is('products') ? 'bg-emerald-500 text-white font-medium shadow-lg shadow-emerald-500/20' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
            <x-lucide-package class="w-5 h-5 shrink-0" />
            <span>Produk & Stok</span>
        </a>

        <a href="/sales" class="flex items-center gap-3 px-4 py-3 rounded-xl transition {{ request()->is('sales') ? 'bg-emerald-500 text-white font-medium shadow-lg shadow-emerald-500/20' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
            <x-lucide-receipt-text class="w-5 h-5 shrink-0" />
            <span>Penjualan</span>
        </a>

        <a href="/expenses" class="flex items-center gap-3 px-4 py-3 rounded-xl transition {{ request()->is('expenses') ? 'bg-emerald-500 text-white font-medium shadow-lg shadow-emerald-500/20' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
            <x-lucide-wallet class="w-5 h-5 shrink-0" />
            <span>Pengeluaran</span>
        </a>

        <a href="/customers" class="flex items-center gap-3 px-4 py-3 rounded-xl transition {{ request()->is('customers') ? 'bg-emerald-500 text-white font-medium shadow-lg shadow-emerald-500/20' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
            <x-lucide-users class="w-5 h-5 shrink-0" />
            <span>Customers</span>
        </a>

        <a href="/reports" class="flex items-center gap-3 px-4 py-3 rounded-xl transition {{ request()->is('reports') ? 'bg-emerald-500 text-white font-medium shadow-lg shadow-emerald-500/20' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
            <x-lucide-chart-column-big class="w-5 h-5 shrink-0" />
            <span>Laporan</span>
        </a>

        <a href="/help" class="flex items-center gap-3 px-4 py-3 rounded-xl transition {{ request()->is('help') ? 'bg-emerald-500 text-white font-medium shadow-lg shadow-emerald-500/20' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
            <x-lucide-circle-help class="w-5 h-5 shrink-0" />
            <span>Pusat Bantuan</span>
        </a>
    </nav>
</aside>

<!-- Mobile Sidebar -->
<aside
    id="mobileSidebar"
    class="fixed inset-y-0 left-0 z-50 w-72 bg-[#0F172A] text-white flex lg:hidden flex-col transform -translate-x-full transition-transform duration-300 ease-out"
>
    <div class="px-6 py-5 border-b border-white/10 shrink-0">
        <div class="flex items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-emerald-500/15 border border-emerald-400/20 flex items-center justify-center">
                    <x-lucide-chart-no-axes-combined class="w-5 h-5 text-emerald-400" />
                </div>

                <div>
                    <h1 class="text-2xl font-bold tracking-tight">
                        Dagang<span class="text-emerald-400">Flow</span>
                    </h1>
                    <p class="text-sm text-slate-400 mt-1">Business Dashboard</p>
                </div>
            </div>

            <button
                type="button"
                onclick="closeMobileSidebar()"
                class="w-9 h-9 rounded-xl bg-white/10 text-slate-300 hover:bg-white/20 flex items-center justify-center"
            >
                <x-lucide-x class="w-5 h-5" />
            </button>
        </div>
    </div>

    <nav class="flex-1 px-5 py-6 space-y-2 overflow-y-auto">
        <a href="/dashboard" onclick="closeMobileSidebar()" class="flex items-center gap-3 px-4 py-3 rounded-xl transition {{ request()->is('dashboard') ? 'bg-emerald-500 text-white font-medium shadow-lg shadow-emerald-500/20' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
            <x-lucide-layout-dashboard class="w-5 h-5 shrink-0" />
            <span>Dashboard</span>
        </a>

        <a href="/products" onclick="closeMobileSidebar()" class="flex items-center gap-3 px-4 py-3 rounded-xl transition {{ request()->is('products') ? 'bg-emerald-500 text-white font-medium shadow-lg shadow-emerald-500/20' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
            <x-lucide-package class="w-5 h-5 shrink-0" />
            <span>Produk & Stok</span>
        </a>

        <a href="/sales" onclick="closeMobileSidebar()" class="flex items-center gap-3 px-4 py-3 rounded-xl transition {{ request()->is('sales') ? 'bg-emerald-500 text-white font-medium shadow-lg shadow-emerald-500/20' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
            <x-lucide-receipt-text class="w-5 h-5 shrink-0" />
            <span>Penjualan</span>
        </a>

        <a href="/expenses" onclick="closeMobileSidebar()" class="flex items-center gap-3 px-4 py-3 rounded-xl transition {{ request()->is('expenses') ? 'bg-emerald-500 text-white font-medium shadow-lg shadow-emerald-500/20' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
            <x-lucide-wallet class="w-5 h-5 shrink-0" />
            <span>Pengeluaran</span>
        </a>

        <a href="/customers" onclick="closeMobileSidebar()" class="flex items-center gap-3 px-4 py-3 rounded-xl transition {{ request()->is('customers') ? 'bg-emerald-500 text-white font-medium shadow-lg shadow-emerald-500/20' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
            <x-lucide-users class="w-5 h-5 shrink-0" />
            <span>Customers</span>
        </a>

        <a href="/reports" onclick="closeMobileSidebar()" class="flex items-center gap-3 px-4 py-3 rounded-xl transition {{ request()->is('reports') ? 'bg-emerald-500 text-white font-medium shadow-lg shadow-emerald-500/20' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
            <x-lucide-chart-column-big class="w-5 h-5 shrink-0" />
            <span>Laporan</span>
        </a>

        <a href="/help" onclick="closeMobileSidebar()" class="flex items-center gap-3 px-4 py-3 rounded-xl transition {{ request()->is('help') ? 'bg-emerald-500 text-white font-medium shadow-lg shadow-emerald-500/20' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
            <x-lucide-circle-help class="w-5 h-5 shrink-0" />
            <span>Pusat Bantuan</span>
        </a>
    </nav>
</aside>