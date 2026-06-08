@extends('layouts.dashboard', [
    'title' => 'Bantuan - DagangFlow',
    'pageTitle' => 'Bantuan',
    'subtitle' => 'Panduan cepat, pertanyaan umum, dan bantuan penggunaan DagangFlow'
])

@section('actions')
    <div class="flex items-center gap-2">
        <a href="{{ route('owner.support.index') }}" class="px-4 py-2 rounded-xl border border-slate-200 bg-white text-slate-700 text-sm font-semibold hover:bg-slate-50 transition">
            Riwayat Kendala
        </a>
        <a href="{{ route('owner.support.create') }}" class="px-4 py-2 rounded-xl bg-emerald-500 text-white text-sm font-semibold hover:bg-emerald-600 transition">
            Laporkan Kendala
        </a>
    </div>
@endsection

@section('content')
    @php
        $user = auth()->user();

        $premiumPlans = ['Trial', 'Bulanan', 'Tahunan'];
        $isPremiumUser = in_array($user->plan_name, $premiumPlans);

        $whatsappNumber = '6281234567890';
        $whatsappMessage = rawurlencode(
            'Halo tim DagangFlow, saya membutuhkan bantuan terkait penggunaan aplikasi.'
        );

        $whatsappUrl = "https://wa.me/{$whatsappNumber}?text={$whatsappMessage}";
    @endphp

    <div class="space-y-8">

        @if (session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-5 py-4 rounded-2xl text-sm font-semibold">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-[#0F172A] rounded-3xl p-6 md:p-8 text-white shadow-sm overflow-hidden relative">
            <div class="absolute -top-16 -right-16 w-56 h-56 rounded-full bg-emerald-400/20 blur-3xl"></div>
            <div class="absolute -bottom-20 -left-20 w-64 h-64 rounded-full bg-blue-400/10 blur-3xl"></div>

            <div class="relative grid grid-cols-1 xl:grid-cols-12 gap-8 items-center">
                <div class="xl:col-span-8">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 border border-white/10 text-emerald-300 text-sm font-semibold">
                        <x-lucide-life-buoy class="w-4 h-4" />
                        Pusat Bantuan DagangFlow
                    </div>

                    <h2 class="text-3xl md:text-4xl font-black mt-5 leading-tight tracking-tight">
                        Butuh panduan menggunakan DagangFlow?
                    </h2>

                    <p class="text-sm md:text-base text-slate-300 mt-4 leading-relaxed max-w-3xl">
                        Temukan panduan cepat untuk mengelola produk, mencatat penjualan, memantau pengeluaran,
                        membaca laporan, dan menggunakan fitur AI Insight.
                    </p>
                </div>

                <div class="xl:col-span-4">
                    <div class="bg-white/10 border border-white/10 rounded-3xl p-5 backdrop-blur">
                        <p class="text-sm text-slate-300">Status paket kamu</p>

                        <div class="flex items-center justify-between gap-4 mt-3">
                            <div>
                                <h3 class="text-2xl font-black">
                                    {{ $user->plan_name ?: 'Free' }}
                                </h3>

                                <p class="text-xs text-slate-400 mt-1">
                                    {{ ucfirst($user->subscription_status ?: 'active') }}
                                </p>
                            </div>

                            <div class="w-12 h-12 rounded-2xl bg-emerald-500 text-white flex items-center justify-center">
                                <x-lucide-badge-check class="w-6 h-6" />
                            </div>
                        </div>

                        <p class="text-xs text-slate-400 mt-4 leading-relaxed">
                            Bantuan via WhatsApp tersedia untuk pengguna Premium.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-5">
            <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm">
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <x-lucide-package class="w-6 h-6" />
                </div>

                <h3 class="text-lg font-black text-slate-900 mt-5">
                    Produk & Stok
                </h3>

                <p class="text-sm text-slate-500 mt-2 leading-relaxed">
                    Tambahkan produk, atur harga jual, modal, stok, dan batas stok rendah.
                </p>
            </div>

            <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm">
                <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center">
                    <x-lucide-receipt-text class="w-6 h-6" />
                </div>

                <h3 class="text-lg font-black text-slate-900 mt-5">
                    Penjualan
                </h3>

                <p class="text-sm text-slate-500 mt-2 leading-relaxed">
                    Catat transaksi dari offline, WhatsApp, marketplace, atau food delivery.
                </p>
            </div>

            <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm">
                <div class="w-12 h-12 rounded-2xl bg-red-50 text-red-600 flex items-center justify-center">
                    <x-lucide-wallet class="w-6 h-6" />
                </div>

                <h3 class="text-lg font-black text-slate-900 mt-5">
                    Pengeluaran
                </h3>

                <p class="text-sm text-slate-500 mt-2 leading-relaxed">
                    Catat biaya operasional agar estimasi laba bisnis lebih realistis.
                </p>
            </div>

            <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm">
                <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center">
                    <x-lucide-chart-column-big class="w-6 h-6" />
                </div>

                <h3 class="text-lg font-black text-slate-900 mt-5">
                    Laporan
                </h3>

                <p class="text-sm text-slate-500 mt-2 leading-relaxed">
                    Pantau omzet, HPP, pengeluaran, margin, channel, dan produk terlaris.
                </p>
            </div>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
            <div class="xl:col-span-2 bg-white rounded-3xl p-6 border border-slate-200 shadow-sm">
                <div class="flex items-start justify-between gap-4 mb-6">
                    <div>
                        <h3 class="text-xl font-black text-slate-900">
                            Pertanyaan Umum
                        </h3>

                        <p class="text-sm text-slate-500 mt-1">
                            Beberapa pertanyaan yang sering muncul saat memakai DagangFlow.
                        </p>
                    </div>

                    <div class="w-11 h-11 rounded-2xl bg-slate-100 text-slate-700 flex items-center justify-center shrink-0">
                        <x-lucide-circle-help class="w-5 h-5" />
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="rounded-2xl border border-slate-200 p-5">
                        <h4 class="font-black text-slate-900">
                            Kenapa stok produk berkurang otomatis?
                        </h4>

                        <p class="text-sm text-slate-500 mt-2 leading-relaxed">
                            Setiap penjualan yang dicatat akan mengurangi stok produk sesuai jumlah terjual.
                            Jika transaksi dihapus, stok akan dikembalikan.
                        </p>
                    </div>

                    <div class="rounded-2xl border border-slate-200 p-5">
                        <h4 class="font-black text-slate-900">
                            Apa bedanya omzet, HPP, dan laba?
                        </h4>

                        <p class="text-sm text-slate-500 mt-2 leading-relaxed">
                            Omzet adalah total penjualan kotor. HPP adalah modal produk yang terjual.
                            Laba dihitung dari omzet dikurangi HPP, biaya platform, dan pengeluaran.
                        </p>
                    </div>

                    <div class="rounded-2xl border border-slate-200 p-5">
                        <h4 class="font-black text-slate-900">
                            Kenapa laporan belum muncul?
                        </h4>

                        <p class="text-sm text-slate-500 mt-2 leading-relaxed">
                            Laporan membutuhkan data produk, penjualan, dan pengeluaran. Jika belum ada transaksi,
                            beberapa bagian laporan akan terlihat kosong.
                        </p>
                    </div>

                    <div class="rounded-2xl border border-slate-200 p-5">
                        <h4 class="font-black text-slate-900">
                            Apa fungsi AI Insight?
                        </h4>

                        <p class="text-sm text-slate-500 mt-2 leading-relaxed">
                            AI Insight membantu membaca performa bisnis berdasarkan data laporan dan memberi
                            ringkasan serta rekomendasi aksi yang lebih mudah dipahami.
                        </p>
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm">
                    <h3 class="text-lg font-black text-slate-900">
                        Alur penggunaan yang disarankan
                    </h3>

                    <div class="space-y-5 mt-5">
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-full bg-emerald-500 text-white flex items-center justify-center shrink-0 text-sm font-black">
                                1
                            </div>

                            <div>
                                <p class="font-bold text-slate-900">Tambahkan produk</p>
                                <p class="text-sm text-slate-500 mt-1">Isi harga jual, modal, dan stok awal.</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-full bg-emerald-500 text-white flex items-center justify-center shrink-0 text-sm font-black">
                                2
                            </div>

                            <div>
                                <p class="font-bold text-slate-900">Catat penjualan</p>
                                <p class="text-sm text-slate-500 mt-1">Pilih produk, channel, jumlah terjual, and biaya platform.</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-full bg-emerald-500 text-white flex items-center justify-center shrink-0 text-sm font-black">
                                3
                            </div>

                            <div>
                                <p class="font-bold text-slate-900">Catat pengeluaran</p>
                                <p class="text-sm text-slate-500 mt-1">Masukkan biaya operasional agar laba lebih akurat.</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-full bg-emerald-500 text-white flex items-center justify-center shrink-0 text-sm font-black">
                                4
                            </div>

                            <div>
                                <p class="font-bold text-slate-900">Pantau laporan</p>
                                <p class="text-sm text-slate-500 mt-1">Lihat omzet, laba, margin, channel, dan produk terlaris.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-emerald-50 rounded-3xl p-6 border border-emerald-100">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-500 text-white flex items-center justify-center">
                        <x-lucide-lightbulb class="w-6 h-6" />
                    </div>

                    <h3 class="text-lg font-black text-slate-900 mt-5">
                        Tips
                    </h3>

                    <p class="text-sm text-slate-600 mt-2 leading-relaxed">
                        Catat transaksi setiap hari agar laporan DagangFlow lebih akurat dan mudah dipakai untuk mengambil keputusan bisnis.
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-3xl border border-slate-200 p-6 md:p-8 shadow-sm flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
            
            <div class="space-y-2 max-w-xl">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 text-xs font-bold w-fit">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    Masih butuh bantuan?
                </span>
                <h3 class="text-xl font-black text-slate-900">Tim DagangFlow siap membantu</h3>
                <p class="text-sm text-slate-500 leading-relaxed">
                    Mengalami kendala saat menggunakan aplikasi? Laporkan ke tim kami atau cek riwayat laporan kamu untuk penanganan lebih lanjut.
                </p>
            </div>

            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 w-full lg:w-auto shrink-0">
                
                <a href="{{ route('owner.support.index') }}" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl bg-indigo-600 text-sm font-bold text-white hover:bg-indigo-700 transition shadow-sm whitespace-nowrap">
                    <x-lucide-history class="w-4 h-4 shrink-0" />
                    Riwayat Kendala
                </a>

                <a href="{{ route('owner.support.create') }}" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl bg-emerald-500 text-sm font-bold text-white hover:bg-emerald-600 transition shadow-sm whitespace-nowrap">
                    <x-lucide-plus-circle class="w-4 h-4 shrink-0" />
                    Laporkan Kendala
                </a>

                <a href="https://wa.me/628xxxxxxxxxx" target="_blank" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl bg-[#25D366] text-sm font-bold text-white hover:bg-[#20ba5a] transition shadow-sm whitespace-nowrap">
                    <svg class="w-4 h-4 fill-current text-white shrink-0" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946C.06 5.348 5.4.01 12.008.01c3.202.001 6.212 1.246 8.477 3.513 2.266 2.268 3.507 5.28 3.505 8.484-.004 6.657-5.34 11.997-11.953 11.997-2.005-.001-3.973-.502-5.713-1.457L0 24zm6.59-4.846c1.6.95 3.188 1.449 4.825 1.451 5.436 0 9.86-4.37 9.864-9.799.002-2.63-1.023-5.101-2.885-6.966C16.528 1.975 14.061.951 11.45.951c-5.438 0-9.863 4.374-9.867 9.802-.001 1.736.486 3.431 1.411 4.937l-.983 3.595 3.637-.98z"/>
                    </svg>
                    Hubungi via WhatsApp
                </a>

            </div>
        </div>

        <div id="whatsappPremiumModal" class="fixed inset-0 hidden z-50 bg-slate-900/50 backdrop-blur-sm px-4 py-6">
            <div class="min-h-full flex items-center justify-center">
                <div class="relative w-full max-w-md bg-white rounded-3xl p-6 shadow-2xl">
                    <button
                        type="button"
                        onclick="closeWhatsappPremiumModal()"
                        class="absolute top-4 right-4 w-10 h-10 rounded-2xl bg-slate-100 text-slate-600 hover:bg-slate-200 hover:text-slate-900 transition flex items-center justify-center"
                    >
                        <x-lucide-x class="w-5 h-5" />
                    </button>

                    <div class="w-14 h-14 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                        <x-lucide-message-circle class="w-7 h-7" />
                    </div>

                    <h3 class="text-xl font-black text-slate-900 mt-5 pr-10">
                        Bantuan WhatsApp Premium
                    </h3>

                    <p class="text-sm text-slate-500 mt-3 leading-relaxed">
                        Bantuan via WhatsApp tersedia untuk pengguna Premium.
                    </p>

                    <p class="text-sm text-slate-500 mt-2 leading-relaxed">
                        Tertarik berlangganan? Coba gratis sekarang.
                    </p>
                </div>
            </div>
        </div>

        <script>
            function openWhatsappPremiumModal() {
                const modal = document.getElementById('whatsappPremiumModal');

                if (!modal) {
                    return;
                }

                modal.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
            }

            function closeWhatsappPremiumModal() {
                const modal = document.getElementById('whatsappPremiumModal');

                if (!modal) {
                    return;
                }

                modal.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }

            document.addEventListener('keydown', function (event) {
                if (event.key === 'Escape') {
                    closeWhatsappPremiumModal();
                }
            });
        </script>

    </div>
@endsection