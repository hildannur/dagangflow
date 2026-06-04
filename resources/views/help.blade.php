@extends('layouts.dashboard', [
    'title' => 'Bantuan - DagangFlow',
    'pageTitle' => 'Bantuan',
    'subtitle' => 'Panduan cepat, pertanyaan umum, dan bantuan penggunaan DagangFlow'
])

@section('actions')
    <a href="{{ route('owner.support.create') }}" class="px-4 py-2 rounded-xl bg-emerald-500 text-white text-sm font-semibold hover:bg-emerald-600">
        Laporkan Kendala
    </a>
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

        <!-- Hero -->
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

        <!-- Quick Guide -->
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

        <!-- Main Help Content -->
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
            <!-- FAQ -->
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

            <!-- Tips -->
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
                                <p class="text-sm text-slate-500 mt-1">Pilih produk, channel, jumlah terjual, dan biaya platform.</p>
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

        <!-- Need Help -->
        <div class="bg-white rounded-3xl p-6 md:p-8 border border-slate-200 shadow-sm">
            <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 items-center">
                <div class="xl:col-span-7">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-100 text-sm font-semibold">
                        <x-lucide-headphones class="w-4 h-4" />
                        Masih butuh bantuan?
                    </div>

                    <h3 class="text-2xl font-black text-slate-900 mt-4">
                        Tim DagangFlow siap membantu
                    </h3>

                    <p class="text-sm text-slate-500 mt-3 leading-relaxed max-w-2xl">
                        Mengalami kendala saat menggunakan aplikasi? Laporkan ke tim kami untuk penanganan lebih lanjut.
                    </p>
                </div>

                <div class="xl:col-span-5 flex flex-col sm:flex-row xl:justify-end gap-3">
                    <a
                        href="{{ route('owner.support.create') }}"
                        class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-2xl bg-emerald-500 text-white text-sm font-black hover:bg-emerald-600 transition"
                    >
                        <x-lucide-message-circle-warning class="w-4 h-4" />
                        Laporkan Kendala
                    </a>

                    @if ($isPremiumUser)
                        <a
                            href="{{ $whatsappUrl }}"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-2xl border border-slate-200 text-sm font-black hover:bg-slate-50 transition"
                        >
                            <x-lucide-message-circle class="w-4 h-4" />
                            Hubungi via WhatsApp
                        </a>
                    @else
                        <button
                            type="button"
                            onclick="openWhatsappPremiumModal()"
                            class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-2xl border border-slate-200 text-sm font-black hover:bg-slate-50 transition"
                        >
                            <x-lucide-message-circle class="w-4 h-4" />
                            Hubungi via WhatsApp
                        </button>
                    @endif
                </div>
            </div>
        </div>

        <!-- WhatsApp Premium Modal -->
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