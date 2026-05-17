<aside class="w-72 bg-[#0F172A] text-white hidden lg:flex flex-col">
    <div class="px-8 py-7 border-b border-white/10">
        <h1 class="text-2xl font-bold tracking-tight">
            Dagang<span class="text-emerald-400">Flow</span>
        </h1>
        <p class="text-sm text-slate-400 mt-1">Business Dashboard</p>
    </div>

    <nav class="flex-1 px-5 py-6 space-y-2">
        <a href="/dashboard" class="flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->is('dashboard') ? 'bg-emerald-500 text-white font-medium' : 'text-slate-300 hover:bg-white/10' }}">
            <span>📊</span>
            Dashboard
        </a>

        <a href="/sales" class="flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->is('sales') ? 'bg-emerald-500 text-white font-medium' : 'text-slate-300 hover:bg-white/10' }}">
            <span>🧾</span>
            Penjualan
        </a>

        <a href="/products" class="flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->is('products') ? 'bg-emerald-500 text-white font-medium' : 'text-slate-300 hover:bg-white/10' }}">
            <span>📦</span>
            Produk & Stok
        </a>

        <a href="/expenses" class="flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->is('expenses') ? 'bg-emerald-500 text-white font-medium' : 'text-slate-300 hover:bg-white/10' }}">
            <span>💸</span>
            Pengeluaran
        </a>

        <a href="/customers" class="flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->is('customers') ? 'bg-emerald-500 text-white font-medium' : 'text-slate-300 hover:bg-white/10' }}">
            <span>👥</span>
            Customer
        </a>

        <a href="/reports" class="flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->is('reports') ? 'bg-emerald-500 text-white font-medium' : 'text-slate-300 hover:bg-white/10' }}">
            <span>📈</span>
            Laporan
        </a>
    </nav>

    <div class="p-5 space-y-3">
        <div class="rounded-2xl bg-white/10 p-4">
            <p class="text-sm text-slate-300">Bisnis aktif</p>
            <p class="font-semibold mt-1">
                {{ auth()->user()->business_name ?? 'Bisnis Saya' }}
            </p>
        </div>

        <form action="/logout" method="POST">
            @csrf
            <button type="submit" class="flex items-center justify-center gap-2 w-full px-4 py-3 rounded-xl bg-red-500/10 text-red-300 text-sm font-semibold hover:bg-red-500 hover:text-white transition">
                <span>🚪</span>
                Logout
            </button>
        </form>
    </div>
</aside>