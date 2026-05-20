@extends('layouts.dashboard', [
    'title' => 'Pusat Bantuan - DagangFlow',
    'pageTitle' => 'Pusat Bantuan',
    'subtitle' => 'Pelajari arti istilah, rumus, dan cara membaca data bisnis di DagangFlow'
])

@section('actions')
    <a href="/dashboard" class="px-4 py-2 rounded-xl border border-slate-200 text-sm font-medium hover:bg-slate-50">
        Kembali ke Dashboard
    </a>
@endsection

@section('content')
    <div class="space-y-8">

        <!-- Intro -->
        <div class="bg-[#0F172A] rounded-2xl p-6 text-white shadow-sm overflow-hidden relative">
            <div class="absolute -right-10 -top-10 w-40 h-40 bg-emerald-500/20 rounded-full blur-3xl"></div>
            <div class="absolute right-20 bottom-0 w-32 h-32 bg-blue-500/10 rounded-full blur-3xl"></div>

            <div class="relative grid grid-cols-1 xl:grid-cols-3 gap-6 items-center">
                <div class="xl:col-span-2">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-500/10 border border-emerald-400/20 text-emerald-300 text-sm font-semibold">
                        <x-lucide-circle-help class="w-4 h-4" />
                        Panduan DagangFlow
                    </div>

                    <h3 class="text-3xl font-bold mt-4">
                        Pahami angka bisnis sebelum mengambil keputusan
                    </h3>

                    <p class="text-sm text-slate-300 mt-3 leading-relaxed max-w-3xl">
                        Halaman ini menjelaskan arti setiap istilah penting di DagangFlow, mulai dari omzet, HPP,
                        biaya platform, uang bersih, estimasi laba, margin, stok, hingga AI Insight.
                    </p>
                </div>

                <div class="bg-white/10 border border-white/10 rounded-2xl p-5">
                    <p class="text-sm text-emerald-300 font-semibold">Catatan Penting</p>
                    <p class="text-sm text-slate-300 mt-2 leading-relaxed">
                        DagangFlow membantu memberi estimasi bisnis. Akurasi laporan tetap bergantung pada kelengkapan
                        data produk, penjualan, dan pengeluaran yang kamu input.
                    </p>
                </div>
            </div>
        </div>

        <!-- Quick Start -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-full bg-emerald-500 text-white flex items-center justify-center">
                    <x-lucide-list-checks class="w-6 h-6" />
                </div>

                <div>
                    <h3 class="text-xl font-bold">Cara Pakai DagangFlow</h3>
                    <p class="text-sm text-slate-500 mt-1">Alur sederhana agar data bisnis kamu terbaca dengan benar</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mt-6">
                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100">
                    <p class="text-sm font-bold text-emerald-600">01</p>
                    <h4 class="font-bold mt-2">Tambah Produk</h4>
                    <p class="text-sm text-slate-500 mt-2">Masukkan nama produk, harga jual, modal, dan stok awal.</p>
                </div>

                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100">
                    <p class="text-sm font-bold text-emerald-600">02</p>
                    <h4 class="font-bold mt-2">Catat Penjualan</h4>
                    <p class="text-sm text-slate-500 mt-2">Input transaksi dari offline, WhatsApp, marketplace, atau delivery.</p>
                </div>

                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100">
                    <p class="text-sm font-bold text-emerald-600">03</p>
                    <h4 class="font-bold mt-2">Catat Pengeluaran</h4>
                    <p class="text-sm text-slate-500 mt-2">Masukkan biaya operasional agar laba tidak terlihat terlalu besar.</p>
                </div>

                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100">
                    <p class="text-sm font-bold text-emerald-600">04</p>
                    <h4 class="font-bold mt-2">Pantau Dashboard</h4>
                    <p class="text-sm text-slate-500 mt-2">Lihat ringkasan omzet, pengeluaran, laba, transaksi, dan stok.</p>
                </div>

                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100">
                    <p class="text-sm font-bold text-emerald-600">05</p>
                    <h4 class="font-bold mt-2">Baca Laporan</h4>
                    <p class="text-sm text-slate-500 mt-2">Gunakan laporan dan AI Insight untuk memahami kondisi bisnis.</p>
                </div>
            </div>
        </div>

        <!-- Financial Terms -->
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">

            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-12 h-12 rounded-full bg-emerald-500 text-white flex items-center justify-center">
                        <x-lucide-trending-up class="w-6 h-6" />
                    </div>

                    <div>
                        <h3 class="text-xl font-bold">Omzet Kotor</h3>
                        <p class="text-sm text-slate-500">Total nilai penjualan sebelum dikurangi biaya apa pun</p>
                    </div>
                </div>

                <p class="text-sm text-slate-600 leading-relaxed">
                    Omzet kotor menunjukkan seberapa besar nilai transaksi penjualan yang masuk. Angka ini belum
                    memperhitungkan modal produk, biaya platform, dan pengeluaran operasional.
                </p>

                <div class="mt-5 p-4 rounded-2xl bg-emerald-50 border border-emerald-100">
                    <p class="text-sm font-semibold text-emerald-700">Rumus</p>
                    <p class="text-sm text-slate-700 mt-2">
                        Omzet Kotor = Harga Jual × Jumlah Terjual
                    </p>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-12 h-12 rounded-full bg-blue-500 text-white flex items-center justify-center">
                        <x-lucide-package class="w-6 h-6" />
                    </div>

                    <div>
                        <h3 class="text-xl font-bold">HPP Produk</h3>
                        <p class="text-sm text-slate-500">Modal dari produk yang sudah terjual</p>
                    </div>
                </div>

                <p class="text-sm text-slate-600 leading-relaxed">
                    HPP membantu menghitung berapa modal produk yang keluar dari setiap transaksi. Tanpa HPP,
                    laba bisa terlihat lebih besar dari kondisi sebenarnya.
                </p>

                <div class="mt-5 p-4 rounded-2xl bg-blue-50 border border-blue-100">
                    <p class="text-sm font-semibold text-blue-700">Rumus</p>
                    <p class="text-sm text-slate-700 mt-2">
                        HPP = Modal Produk × Jumlah Terjual
                    </p>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-12 h-12 rounded-full bg-amber-500 text-white flex items-center justify-center">
                        <x-lucide-wallet class="w-6 h-6" />
                    </div>

                    <div>
                        <h3 class="text-xl font-bold">Biaya Platform</h3>
                        <p class="text-sm text-slate-500">Potongan marketplace, delivery, atau biaya layanan</p>
                    </div>
                </div>

                <p class="text-sm text-slate-600 leading-relaxed">
                    Biaya platform adalah potongan yang muncul dari channel penjualan seperti marketplace,
                    food delivery, admin transfer, atau layanan pihak ketiga.
                </p>

                <div class="mt-5 p-4 rounded-2xl bg-amber-50 border border-amber-100">
                    <p class="text-sm font-semibold text-amber-700">Contoh</p>
                    <p class="text-sm text-slate-700 mt-2">
                        Fee Shopee, biaya GoFood, biaya GrabFood, admin payment gateway, atau potongan layanan lain.
                    </p>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-12 h-12 rounded-full bg-emerald-500 text-white flex items-center justify-center">
                        <x-lucide-hand-coins class="w-6 h-6" />
                    </div>

                    <div>
                        <h3 class="text-xl font-bold">Uang Bersih</h3>
                        <p class="text-sm text-slate-500">Uang penjualan setelah dikurangi biaya platform</p>
                    </div>
                </div>

                <p class="text-sm text-slate-600 leading-relaxed">
                    Uang bersih bukan laba bersih. Uang bersih hanya menunjukkan hasil penjualan setelah potongan
                    platform, tetapi belum dikurangi HPP dan pengeluaran operasional.
                </p>

                <div class="mt-5 p-4 rounded-2xl bg-emerald-50 border border-emerald-100">
                    <p class="text-sm font-semibold text-emerald-700">Rumus</p>
                    <p class="text-sm text-slate-700 mt-2">
                        Uang Bersih = Omzet Kotor - Biaya Platform
                    </p>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-12 h-12 rounded-full bg-emerald-500 text-white flex items-center justify-center">
                        <x-lucide-circle-dollar-sign class="w-6 h-6" />
                    </div>

                    <div>
                        <h3 class="text-xl font-bold">Estimasi Laba Bersih</h3>
                        <p class="text-sm text-slate-500">Perkiraan keuntungan setelah biaya utama dikurangi</p>
                    </div>
                </div>

                <p class="text-sm text-slate-600 leading-relaxed">
                    Estimasi laba bersih adalah angka yang lebih realistis untuk melihat keuntungan. Angka ini
                    menghitung omzet, modal produk, biaya platform, dan pengeluaran operasional.
                </p>

                <div class="mt-5 p-4 rounded-2xl bg-emerald-50 border border-emerald-100">
                    <p class="text-sm font-semibold text-emerald-700">Rumus</p>
                    <p class="text-sm text-slate-700 mt-2">
                        Estimasi Laba Bersih = Omzet Kotor - HPP - Biaya Platform - Pengeluaran
                    </p>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-12 h-12 rounded-full bg-purple-500 text-white flex items-center justify-center">
                        <x-lucide-percent class="w-6 h-6" />
                    </div>

                    <div>
                        <h3 class="text-xl font-bold">Margin Estimasi</h3>
                        <p class="text-sm text-slate-500">Persentase laba dibandingkan omzet</p>
                    </div>
                </div>

                <p class="text-sm text-slate-600 leading-relaxed">
                    Margin membantu melihat seberapa sehat keuntungan bisnis. Semakin tinggi margin, semakin besar
                    porsi keuntungan dibandingkan omzet.
                </p>

                <div class="mt-5 p-4 rounded-2xl bg-purple-50 border border-purple-100">
                    <p class="text-sm font-semibold text-purple-700">Rumus</p>
                    <p class="text-sm text-slate-700 mt-2">
                        Margin = Estimasi Laba Bersih ÷ Omzet Kotor × 100%
                    </p>
                </div>
            </div>
        </div>

        <!-- Page Guide -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-full bg-slate-900 text-white flex items-center justify-center">
                    <x-lucide-map class="w-6 h-6" />
                </div>

                <div>
                    <h3 class="text-xl font-bold">Penjelasan Fitur Website</h3>
                    <p class="text-sm text-slate-500 mt-1">Ringkasan fungsi setiap halaman di DagangFlow</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4 mt-6">
                <div class="p-5 rounded-2xl bg-slate-50 border border-slate-100">
                    <div class="flex items-center gap-3">
                        <x-lucide-layout-dashboard class="w-5 h-5 text-emerald-600" />
                        <h4 class="font-bold">Dashboard</h4>
                    </div>
                    <p class="text-sm text-slate-500 mt-3 leading-relaxed">
                        Menampilkan ringkasan cepat bisnis seperti omzet, pengeluaran, laba, transaksi,
                        channel terbaik, dan stok hampir habis.
                    </p>
                </div>

                <div class="p-5 rounded-2xl bg-slate-50 border border-slate-100">
                    <div class="flex items-center gap-3">
                        <x-lucide-receipt-text class="w-5 h-5 text-emerald-600" />
                        <h4 class="font-bold">Penjualan</h4>
                    </div>
                    <p class="text-sm text-slate-500 mt-3 leading-relaxed">
                        Digunakan untuk mencatat transaksi. Saat penjualan ditambahkan, stok produk otomatis berkurang.
                    </p>
                </div>

                <div class="p-5 rounded-2xl bg-slate-50 border border-slate-100">
                    <div class="flex items-center gap-3">
                        <x-lucide-package class="w-5 h-5 text-emerald-600" />
                        <h4 class="font-bold">Produk & Stok</h4>
                    </div>
                    <p class="text-sm text-slate-500 mt-3 leading-relaxed">
                        Digunakan untuk mengelola produk, harga jual, modal produk, stok, dan batas stok rendah.
                    </p>
                </div>

                <div class="p-5 rounded-2xl bg-slate-50 border border-slate-100">
                    <div class="flex items-center gap-3">
                        <x-lucide-wallet class="w-5 h-5 text-emerald-600" />
                        <h4 class="font-bold">Pengeluaran</h4>
                    </div>
                    <p class="text-sm text-slate-500 mt-3 leading-relaxed">
                        Digunakan untuk mencatat biaya operasional seperti bahan baku, marketing, packaging, listrik,
                        sewa, atau biaya lain.
                    </p>
                </div>

                <div class="p-5 rounded-2xl bg-slate-50 border border-slate-100">
                    <div class="flex items-center gap-3">
                        <x-lucide-users class="w-5 h-5 text-emerald-600" />
                        <h4 class="font-bold">Customer</h4>
                    </div>
                    <p class="text-sm text-slate-500 mt-3 leading-relaxed">
                        Digunakan untuk menyimpan data pelanggan, asal channel, total order, total belanja,
                        dan catatan customer.
                    </p>
                </div>

                <div class="p-5 rounded-2xl bg-slate-50 border border-slate-100">
                    <div class="flex items-center gap-3">
                        <x-lucide-chart-column-big class="w-5 h-5 text-emerald-600" />
                        <h4 class="font-bold">Laporan</h4>
                    </div>
                    <p class="text-sm text-slate-500 mt-3 leading-relaxed">
                        Digunakan untuk membaca performa bisnis berdasarkan periode, termasuk omzet, HPP, laba,
                        produk terlaris, channel, dan AI Insight.
                    </p>
                </div>
            </div>
        </div>

        <!-- Important Notes -->
        <div class="bg-amber-50 rounded-2xl p-6 border border-amber-100">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 rounded-full bg-amber-500 text-white flex items-center justify-center shrink-0">
                    <x-lucide-triangle-alert class="w-6 h-6" />
                </div>

                <div>
                    <h3 class="text-xl font-bold text-slate-900">Hal yang Perlu Diingat</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                        <div>
                            <p class="font-semibold text-slate-800">Omzet besar belum tentu untung</p>
                            <p class="text-sm text-slate-600 mt-1">
                                Keuntungan baru terlihat setelah HPP, biaya platform, dan pengeluaran dihitung.
                            </p>
                        </div>

                        <div>
                            <p class="font-semibold text-slate-800">Uang bersih bukan laba bersih</p>
                            <p class="text-sm text-slate-600 mt-1">
                                Uang bersih hanya mengurangi biaya platform, belum mengurangi modal produk dan pengeluaran.
                            </p>
                        </div>

                        <div>
                            <p class="font-semibold text-slate-800">Data harus rutin dicatat</p>
                            <p class="text-sm text-slate-600 mt-1">
                                Semakin lengkap data yang dimasukkan, semakin akurat dashboard dan laporan.
                            </p>
                        </div>

                        <div>
                            <p class="font-semibold text-slate-800">AI Insight bersifat rekomendasi</p>
                            <p class="text-sm text-slate-600 mt-1">
                                Gunakan AI Insight sebagai bahan pertimbangan, bukan satu-satunya dasar keputusan bisnis.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection