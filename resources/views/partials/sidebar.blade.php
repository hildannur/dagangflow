<aside class="w-72 bg-[#0F172A] text-white hidden lg:flex flex-col">
    <div class="px-8 py-7 border-b border-white/10">
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

    <nav class="flex-1 px-5 py-6 space-y-2">
        <a href="/dashboard" class="flex items-center gap-3 px-4 py-3 rounded-xl transition {{ request()->is('dashboard') ? 'bg-emerald-500 text-white font-medium shadow-lg shadow-emerald-500/20' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
            <x-lucide-layout-dashboard class="w-5 h-5 shrink-0" />
            <span>Dashboard</span>
        </a>

        <a href="/sales" class="flex items-center gap-3 px-4 py-3 rounded-xl transition {{ request()->is('sales') ? 'bg-emerald-500 text-white font-medium shadow-lg shadow-emerald-500/20' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
            <x-lucide-receipt-text class="w-5 h-5 shrink-0" />
            <span>Penjualan</span>
        </a>

        <a href="/products" class="flex items-center gap-3 px-4 py-3 rounded-xl transition {{ request()->is('products') ? 'bg-emerald-500 text-white font-medium shadow-lg shadow-emerald-500/20' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
            <x-lucide-package class="w-5 h-5 shrink-0" />
            <span>Produk & Stok</span>
        </a>

        <a href="/expenses" class="flex items-center gap-3 px-4 py-3 rounded-xl transition {{ request()->is('expenses') ? 'bg-emerald-500 text-white font-medium shadow-lg shadow-emerald-500/20' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
            <x-lucide-wallet-cards class="w-5 h-5 shrink-0" />
            <span>Pengeluaran</span>
        </a>

        <a href="/customers" class="flex items-center gap-3 px-4 py-3 rounded-xl transition {{ request()->is('customers') ? 'bg-emerald-500 text-white font-medium shadow-lg shadow-emerald-500/20' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
            <x-lucide-users class="w-5 h-5 shrink-0" />
            <span>Customer</span>
        </a>

        <a href="/reports" class="flex items-center gap-3 px-4 py-3 rounded-xl transition {{ request()->is('reports') ? 'bg-emerald-500 text-white font-medium shadow-lg shadow-emerald-500/20' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
            <x-lucide-chart-column-big class="w-5 h-5 shrink-0" />
            <span>Laporan</span>
        </a>
    </nav>

    <div class="p-5 space-y-3">
        <div class="rounded-2xl bg-white/10 border border-white/10 p-4">
            <div class="flex items-start gap-3">
                <div class="w-10 h-10 rounded-xl bg-emerald-500/15 flex items-center justify-center">
                    <x-lucide-store class="w-5 h-5 text-emerald-400" />
                </div>

                <div class="min-w-0">
                    <p class="text-sm text-slate-300">Bisnis aktif</p>
                    <p class="font-semibold mt-1 truncate">
                        {{ auth()->user()->business_name ?? 'Bisnis Saya' }}
                    </p>
                </div>
            </div>
        </div>

        <form action="/logout" method="POST">
            @csrf
            <button type="submit" class="flex items-center justify-center gap-2 w-full px-4 py-3 rounded-xl bg-red-500/10 text-red-300 text-sm font-semibold hover:bg-red-500 hover:text-white transition">
                <x-lucide-log-out class="w-5 h-5" />
                <span>Logout</span>
            </button>
        </form>
    </div>
</aside>