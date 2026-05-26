<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DagangFlow - Kelola Penjualan UMKM dari Semua Channel</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-[#F8FAF7] text-slate-900 overflow-x-hidden">

    <!-- Navbar -->
    <header class="fixed top-0 left-0 right-0 z-50 bg-[#F8FAF7]/80 backdrop-blur-xl border-b border-slate-200/70">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 h-20 flex items-center justify-between">

            <!-- Logo -->
            <a href="/" class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-[#0F172A] flex items-center justify-center shadow-sm">
                    <span class="text-emerald-400 font-black text-xl">D</span>
                </div>
                <div>
                    <h1 class="text-xl font-bold tracking-tight">
                        Dagang<span class="text-emerald-500">Flow</span>
                    </h1>
                    <p class="text-xs text-slate-500 -mt-1">Business Dashboard</p>
                </div>
            </a>

            <!-- Nav Desktop -->
            <nav class="hidden md:flex items-center gap-8 text-sm font-medium text-slate-600">
                <a href="#fitur" class="hover:text-emerald-600 transition">Fitur</a>
                <a href="#solusi" class="hover:text-emerald-600 transition">Solusi</a>
                <a href="#cara-kerja" class="hover:text-emerald-600 transition">Cara Kerja</a>
                <a href="#cta" class="hover:text-emerald-600 transition">Mulai</a>
            </nav>

            <!-- CTA -->
            <div class="flex items-center gap-3">
                <a href="/login" class="hidden sm:inline-flex px-4 py-2 rounded-xl text-sm font-semibold text-slate-700 hover:bg-white border border-transparent hover:border-slate-200 transition">
                    Login
                </a>

                <form action="{{ route('demo.login') }}" method="POST">
                    @csrf

                    <button type="submit" class="inline-flex px-5 py-2.5 rounded-xl bg-emerald-500 text-white text-sm font-semibold hover:bg-emerald-600 transition shadow-sm shadow-emerald-500/20">
                        Coba Demo
                    </button>
                </form>
            </div>
        </div>
    </header>

    <!-- Hero -->
    <main class="pt-20">

        <section class="relative overflow-hidden">
            <!-- Background Glow -->
            <div class="absolute -top-40 -right-40 w-[520px] h-[520px] bg-emerald-300/30 rounded-full blur-3xl"></div>
            <div class="absolute top-40 -left-40 w-[420px] h-[420px] bg-blue-200/30 rounded-full blur-3xl"></div>

            <div class="max-w-7xl mx-auto px-6 lg:px-8 py-24 lg:py-32 relative">

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-14 items-center">

                    <!-- Hero Text -->
                    <div>
                        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white border border-slate-200 shadow-sm mb-7">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                            <span class="text-sm font-semibold text-slate-700">Dashboard sederhana untuk UMKM multi-channel</span>
                        </div>

                        <h1 class="text-5xl lg:text-7xl font-black tracking-tight leading-[0.95] text-[#0F172A]">
                            Semua penjualanmu,
                            <span class="text-emerald-500">rapi dalam satu dashboard.</span>
                        </h1>

                        <p class="mt-7 text-lg lg:text-xl text-slate-600 leading-relaxed max-w-xl">
                            DagangFlow membantu owner UMKM mencatat penjualan dari offline, WhatsApp, marketplace, dan food delivery — lengkap dengan stok, pengeluaran, dan laporan laba.
                        </p>

                        <div class="mt-9 flex flex-col sm:flex-row gap-4">
                            <form action="{{ route('demo.login') }}" method="POST">
                                @csrf

                                <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center px-7 py-4 rounded-2xl bg-emerald-500 text-white font-bold hover:bg-emerald-600 transition shadow-lg shadow-emerald-500/20">
                                    Lihat Demo Dashboard
                                </button>
                            </form>

                            <a href="#fitur" class="inline-flex items-center justify-center px-7 py-4 rounded-2xl bg-white border border-slate-200 text-slate-800 font-bold hover:bg-slate-50 transition">
                                Pelajari Fitur
                            </a>
                        </div>

                        <div class="mt-10 grid grid-cols-3 gap-6 max-w-lg">
                            <div>
                                <h3 class="text-2xl font-black text-[#0F172A]">4+</h3>
                                <p class="text-sm text-slate-500 mt-1">Channel jualan</p>
                            </div>
                            <div>
                                <h3 class="text-2xl font-black text-[#0F172A]">1</h3>
                                <p class="text-sm text-slate-500 mt-1">Dashboard utama</p>
                            </div>
                            <div>
                                <h3 class="text-2xl font-black text-[#0F172A]">Cepat</h3>
                                <p class="text-sm text-slate-500 mt-1">Input transaksi</p>
                            </div>
                        </div>
                    </div>

                    <!-- Hero Dashboard Preview -->
                    <div class="relative">
                        <div class="absolute -inset-6 bg-emerald-300/20 blur-3xl rounded-[3rem]"></div>

                        <div class="relative rounded-[2rem] bg-[#0F172A] p-3 shadow-2xl shadow-slate-900/20 border border-white/10">
                            <div class="rounded-[1.5rem] bg-white overflow-hidden">

                                <!-- Mock Topbar -->
                                <div class="h-14 bg-slate-50 border-b border-slate-200 flex items-center justify-between px-5">
                                    <div class="flex items-center gap-2">
                                        <span class="w-3 h-3 rounded-full bg-red-400"></span>
                                        <span class="w-3 h-3 rounded-full bg-amber-400"></span>
                                        <span class="w-3 h-3 rounded-full bg-emerald-400"></span>
                                    </div>
                                    <div class="text-xs font-semibold text-slate-500">DagangFlow Dashboard</div>
                                </div>

                                <div class="p-6 bg-[#F8FAF7]">
                                    <div class="flex items-center justify-between mb-6">
                                        <div>
                                            <p class="text-sm text-slate-500">Omzet Bulan Ini</p>
                                            <h3 class="text-3xl font-black text-slate-900 mt-1">Rp8,45jt</h3>
                                        </div>
                                        <span class="px-3 py-1 rounded-full bg-emerald-100 text-emerald-700 text-xs font-bold">+12%</span>
                                    </div>

                                    <div class="grid grid-cols-3 gap-3 mb-5">
                                        <div class="rounded-2xl bg-white p-4 border border-slate-200">
                                            <p class="text-xs text-slate-500">Laba</p>
                                            <h4 class="text-lg font-black text-emerald-600 mt-2">Rp5,35jt</h4>
                                        </div>
                                        <div class="rounded-2xl bg-white p-4 border border-slate-200">
                                            <p class="text-xs text-slate-500">Stok Rendah</p>
                                            <h4 class="text-lg font-black text-amber-600 mt-2">5 item</h4>
                                        </div>
                                        <div class="rounded-2xl bg-white p-4 border border-slate-200">
                                            <p class="text-xs text-slate-500">Transaksi</p>
                                            <h4 class="text-lg font-black text-slate-900 mt-2">186</h4>
                                        </div>
                                    </div>

                                    <div class="rounded-2xl bg-white p-5 border border-slate-200">
                                        <div class="flex items-center justify-between mb-5">
                                            <h4 class="font-bold text-slate-900">Channel Terbaik</h4>
                                            <p class="text-xs text-slate-500">Mei 2026</p>
                                        </div>

                                        <div class="space-y-4">
                                            <div>
                                                <div class="flex justify-between text-xs mb-1">
                                                    <span>Offline Store</span>
                                                    <span class="font-bold">Rp3,2jt</span>
                                                </div>
                                                <div class="h-2 bg-slate-100 rounded-full overflow-hidden">
                                                    <div class="h-full w-[82%] bg-emerald-500 rounded-full"></div>
                                                </div>
                                            </div>

                                            <div>
                                                <div class="flex justify-between text-xs mb-1">
                                                    <span>Shopee</span>
                                                    <span class="font-bold">Rp2,4jt</span>
                                                </div>
                                                <div class="h-2 bg-slate-100 rounded-full overflow-hidden">
                                                    <div class="h-full w-[64%] bg-orange-400 rounded-full"></div>
                                                </div>
                                            </div>

                                            <div>
                                                <div class="flex justify-between text-xs mb-1">
                                                    <span>WhatsApp</span>
                                                    <span class="font-bold">Rp1,8jt</span>
                                                </div>
                                                <div class="h-2 bg-slate-100 rounded-full overflow-hidden">
                                                    <div class="h-full w-[48%] bg-emerald-400 rounded-full"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <!-- Floating Card -->
                        <div class="hidden lg:block absolute -bottom-8 -left-8 bg-white rounded-2xl p-5 border border-slate-200 shadow-xl">
                            <p class="text-sm text-slate-500">Produk Terlaris</p>
                            <h4 class="text-xl font-black text-slate-900 mt-1">Kopi Susu 250ml</h4>
                            <p class="text-sm text-emerald-600 font-semibold mt-2">86 terjual bulan ini</p>
                        </div>
                    </div>

                </div>
            </div>
        </section>

        <!-- Problem Section -->
        <section id="solusi" class="py-20 bg-white border-y border-slate-200">
            <div class="max-w-7xl mx-auto px-6 lg:px-8">
                <div class="max-w-3xl">
                    <p class="text-sm font-bold text-emerald-600 uppercase tracking-wider">Masalah UMKM</p>
                    <h2 class="text-4xl lg:text-5xl font-black tracking-tight text-[#0F172A] mt-4">
                        Jualannya jalan, tapi catatannya sering tercecer.
                    </h2>
                    <p class="text-lg text-slate-600 mt-5 leading-relaxed">
                        Banyak owner UMKM jualan dari banyak tempat sekaligus, tapi data penjualan, stok, dan pengeluaran masih tersebar di chat, marketplace, buku catatan, atau ingatan sendiri.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-12">
                    <div class="rounded-3xl p-7 bg-[#F8FAF7] border border-slate-200">
                        <div class="w-12 h-12 rounded-2xl bg-red-100 flex items-center justify-center text-2xl mb-6">🧾</div>
                        <h3 class="text-xl font-black text-slate-900">Transaksi tercecer</h3>
                        <p class="text-slate-600 mt-3 leading-relaxed">
                            Order dari offline, WhatsApp, Shopee, dan GoFood sering tercatat di tempat berbeda.
                        </p>
                    </div>

                    <div class="rounded-3xl p-7 bg-[#F8FAF7] border border-slate-200">
                        <div class="w-12 h-12 rounded-2xl bg-amber-100 flex items-center justify-center text-2xl mb-6">📦</div>
                        <h3 class="text-xl font-black text-slate-900">Stok tidak terpantau</h3>
                        <p class="text-slate-600 mt-3 leading-relaxed">
                            Produk baru sadar habis saat ada customer mau beli. Akhirnya peluang penjualan hilang.
                        </p>
                    </div>

                    <div class="rounded-3xl p-7 bg-[#F8FAF7] border border-slate-200">
                        <div class="w-12 h-12 rounded-2xl bg-blue-100 flex items-center justify-center text-2xl mb-6">📉</div>
                        <h3 class="text-xl font-black text-slate-900">Laba cuma kira-kira</h3>
                        <p class="text-slate-600 mt-3 leading-relaxed">
                            Omzet terlihat besar, tapi belum tentu tahu profit bersih setelah biaya platform dan pengeluaran.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Features -->
        <section id="fitur" class="py-24">
            <div class="max-w-7xl mx-auto px-6 lg:px-8">

                <div class="text-center max-w-3xl mx-auto">
                    <p class="text-sm font-bold text-emerald-600 uppercase tracking-wider">Fitur Utama</p>
                    <h2 class="text-4xl lg:text-5xl font-black tracking-tight text-[#0F172A] mt-4">
                        Semua yang dibutuhkan owner UMKM untuk membaca kondisi bisnis.
                    </h2>
                    <p class="text-lg text-slate-600 mt-5">
                        Mulai dari catat penjualan, stok, pengeluaran, sampai laporan performa channel.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-14">

                    <div class="rounded-3xl p-7 bg-white border border-slate-200 shadow-sm">
                        <div class="w-12 h-12 rounded-2xl bg-emerald-100 text-emerald-700 flex items-center justify-center text-2xl mb-6">📊</div>
                        <h3 class="text-xl font-black">Dashboard Ringkas</h3>
                        <p class="text-slate-600 mt-3 leading-relaxed">
                            Lihat omzet, pengeluaran, laba, transaksi, dan stok rendah dari satu halaman utama.
                        </p>
                    </div>

                    <div class="rounded-3xl p-7 bg-white border border-slate-200 shadow-sm">
                        <div class="w-12 h-12 rounded-2xl bg-blue-100 text-blue-700 flex items-center justify-center text-2xl mb-6">🧾</div>
                        <h3 class="text-xl font-black">Penjualan Multi-Channel</h3>
                        <p class="text-slate-600 mt-3 leading-relaxed">
                            Catat transaksi dari offline, WhatsApp, Instagram, Shopee, Tokopedia, GoFood, dan channel lain.
                        </p>
                    </div>

                    <div class="rounded-3xl p-7 bg-white border border-slate-200 shadow-sm">
                        <div class="w-12 h-12 rounded-2xl bg-amber-100 text-amber-700 flex items-center justify-center text-2xl mb-6">📦</div>
                        <h3 class="text-xl font-black">Produk & Stok</h3>
                        <p class="text-slate-600 mt-3 leading-relaxed">
                            Pantau harga jual, modal produk, stok tersedia, dan alert stok hampir habis.
                        </p>
                    </div>

                    <div class="rounded-3xl p-7 bg-white border border-slate-200 shadow-sm">
                        <div class="w-12 h-12 rounded-2xl bg-red-100 text-red-700 flex items-center justify-center text-2xl mb-6">💸</div>
                        <h3 class="text-xl font-black">Catat Pengeluaran</h3>
                        <p class="text-slate-600 mt-3 leading-relaxed">
                            Simpan biaya bahan baku, packaging, iklan, transport, biaya platform, dan operasional.
                        </p>
                    </div>

                    <div class="rounded-3xl p-7 bg-white border border-slate-200 shadow-sm">
                        <div class="w-12 h-12 rounded-2xl bg-purple-100 text-purple-700 flex items-center justify-center text-2xl mb-6">👥</div>
                        <h3 class="text-xl font-black">Data Customer</h3>
                        <p class="text-slate-600 mt-3 leading-relaxed">
                            Kenali customer repeat order, asal channel, total pembelian, dan catatan preferensi.
                        </p>
                    </div>

                    <div class="rounded-3xl p-7 bg-white border border-slate-200 shadow-sm">
                        <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-700 flex items-center justify-center text-2xl mb-6">📈</div>
                        <h3 class="text-xl font-black">Laporan Bisnis</h3>
                        <p class="text-slate-600 mt-3 leading-relaxed">
                            Bandingkan channel terbaik, produk terlaris, pengeluaran terbesar, dan estimasi laba.
                        </p>
                    </div>

                </div>
            </div>
        </section>

        <!-- How It Works -->
        <section id="cara-kerja" class="py-24 bg-[#0F172A] text-white">
            <div class="max-w-7xl mx-auto px-6 lg:px-8">

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-14 items-center">
                    <div>
                        <p class="text-sm font-bold text-emerald-300 uppercase tracking-wider">Cara Kerja</p>
                        <h2 class="text-4xl lg:text-5xl font-black tracking-tight mt-4">
                            Tidak perlu integrasi rumit. Mulai dari input manual yang cepat.
                        </h2>
                        <p class="text-lg text-slate-300 mt-5 leading-relaxed">
                            DagangFlow dibuat untuk UMKM kecil yang belum siap pakai sistem kompleks, tapi ingin mulai merapikan pencatatan bisnis.
                        </p>
                    </div>

                    <div class="space-y-5">
                        <div class="flex gap-5 p-6 rounded-3xl bg-white/10 border border-white/10">
                            <div class="w-10 h-10 rounded-full bg-emerald-400 text-[#0F172A] flex items-center justify-center font-black shrink-0">1</div>
                            <div>
                                <h3 class="text-xl font-black">Tambah produk dan stok</h3>
                                <p class="text-slate-300 mt-2">Masukkan nama produk, harga jual, modal, dan jumlah stok awal.</p>
                            </div>
                        </div>

                        <div class="flex gap-5 p-6 rounded-3xl bg-white/10 border border-white/10">
                            <div class="w-10 h-10 rounded-full bg-emerald-400 text-[#0F172A] flex items-center justify-center font-black shrink-0">2</div>
                            <div>
                                <h3 class="text-xl font-black">Catat transaksi dari channel mana pun</h3>
                                <p class="text-slate-300 mt-2">Pilih channel penjualan, produk, quantity, dan biaya platform.</p>
                            </div>
                        </div>

                        <div class="flex gap-5 p-6 rounded-3xl bg-white/10 border border-white/10">
                            <div class="w-10 h-10 rounded-full bg-emerald-400 text-[#0F172A] flex items-center justify-center font-black shrink-0">3</div>
                            <div>
                                <h3 class="text-xl font-black">Lihat laporan otomatis</h3>
                                <p class="text-slate-300 mt-2">Dashboard menghitung omzet, pengeluaran, laba, produk terlaris, dan channel terbaik.</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </section>

        <!-- CTA -->
        <section id="cta" class="py-24">
            <div class="max-w-7xl mx-auto px-6 lg:px-8">
                <div class="rounded-[2rem] bg-white border border-slate-200 shadow-sm overflow-hidden">
                    <div class="grid grid-cols-1 lg:grid-cols-2">

                        <div class="p-10 lg:p-14">
                            <p class="text-sm font-bold text-emerald-600 uppercase tracking-wider">Mulai Rapikan Bisnis</p>
                            <h2 class="text-4xl lg:text-5xl font-black tracking-tight text-[#0F172A] mt-4">
                                Siap tahu channel mana yang paling menghasilkan?
                            </h2>
                            <p class="text-lg text-slate-600 mt-5 leading-relaxed">
                                Coba demo DagangFlow dan lihat bagaimana dashboard membantu owner UMKM membaca bisnis dengan lebih jelas.
                            </p>

                            <div class="mt-9 flex flex-col sm:flex-row gap-4">
                                <form action="{{ route('demo.login') }}" method="POST">
                                    @csrf

                                    <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center px-7 py-4 rounded-2xl bg-emerald-500 text-white font-bold hover:bg-emerald-600 transition">
                                        Masuk ke Demo
                                    </button>
                                </form>

                                <a href="/login" class="inline-flex items-center justify-center px-7 py-4 rounded-2xl bg-slate-100 text-slate-800 font-bold hover:bg-slate-200 transition">
                                    Login Owner
                                </a>
                            </div>
                        </div>

                        <div class="bg-[#0F172A] p-10 lg:p-14 flex items-center">
                            <div class="w-full space-y-4">
                                <div class="rounded-2xl bg-white/10 border border-white/10 p-5">
                                    <p class="text-slate-300 text-sm">Channel terbaik</p>
                                    <h3 class="text-white text-2xl font-black mt-1">Offline Store</h3>
                                    <p class="text-emerald-300 text-sm mt-2">38% dari total omzet</p>
                                </div>

                                <div class="rounded-2xl bg-white/10 border border-white/10 p-5">
                                    <p class="text-slate-300 text-sm">Produk terlaris</p>
                                    <h3 class="text-white text-2xl font-black mt-1">Kopi Susu 250ml</h3>
                                    <p class="text-emerald-300 text-sm mt-2">86 terjual bulan ini</p>
                                </div>

                                <div class="rounded-2xl bg-white/10 border border-white/10 p-5">
                                    <p class="text-slate-300 text-sm">Estimasi laba</p>
                                    <h3 class="text-white text-2xl font-black mt-1">Rp5,35jt</h3>
                                    <p class="text-emerald-300 text-sm mt-2">Margin masih sehat</p>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </section>

    </main>

    <!-- Footer -->
    <footer class="bg-[#0F172A] text-white py-10">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 flex flex-col md:flex-row items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-black">
                    Dagang<span class="text-emerald-400">Flow</span>
                </h2>
                <p class="text-sm text-slate-400 mt-1">Multi-channel sales tracker for growing businesses.</p>
            </div>

            <p class="text-sm text-slate-400">
                © 2026 DagangFlow. Built for UMKM owners.
            </p>
        </div>
    </footer>

</body>
</html>