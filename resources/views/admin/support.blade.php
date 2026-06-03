@extends('layouts.admin', [
    'title' => 'Support - Superadmin DagangFlow',
    'pageTitle' => 'Support Center',
    'subtitle' => 'Superadmin / Support',
])

@section('content')
    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-5">
        <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm">
            <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center mb-5">
                <x-lucide-inbox class="w-6 h-6" />
            </div>

            <p class="text-sm text-slate-500">Total Ticket</p>
            <h3 class="text-4xl font-black mt-3">0</h3>
        </div>

        <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm">
            <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center mb-5">
                <x-lucide-circle-alert class="w-6 h-6" />
            </div>

            <p class="text-sm text-slate-500">Open</p>
            <h3 class="text-4xl font-black mt-3 text-amber-600">0</h3>
        </div>

        <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm">
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center mb-5">
                <x-lucide-check-circle class="w-6 h-6" />
            </div>

            <p class="text-sm text-slate-500">Resolved</p>
            <h3 class="text-4xl font-black mt-3 text-emerald-600">0</h3>
        </div>

        <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm">
            <div class="w-12 h-12 rounded-2xl bg-red-50 text-red-600 flex items-center justify-center mb-5">
                <x-lucide-flame class="w-6 h-6" />
            </div>

            <p class="text-sm text-slate-500">High Priority</p>
            <h3 class="text-4xl font-black mt-3 text-red-600">0</h3>
        </div>
    </div>

    <!-- Main Content -->
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        <!-- Ticket List -->
        <div class="xl:col-span-2 bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-slate-200 flex items-start justify-between gap-4">
                <div>
                    <h3 class="text-xl font-black">Daftar Ticket Support</h3>
                    <p class="text-sm text-slate-500 mt-1">
                        Kelola laporan bug, pertanyaan, request fitur, dan masalah user.
                    </p>
                </div>

                <div class="w-11 h-11 rounded-2xl bg-slate-100 text-slate-700 flex items-center justify-center shrink-0">
                    <x-lucide-list class="w-5 h-5" />
                </div>
            </div>

            <!-- Filter Dummy -->
            <div class="p-6 border-b border-slate-100">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="text-sm font-bold text-slate-700">Cari Ticket</label>
                        <input
                            type="text"
                            placeholder="Cari subject, user, atau email"
                            class="mt-2 w-full px-4 py-3 rounded-2xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500"
                            disabled
                        >
                    </div>

                    <div>
                        <label class="text-sm font-bold text-slate-700">Status</label>
                        <select
                            class="mt-2 w-full px-4 py-3 rounded-2xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500"
                            disabled
                        >
                            <option>Semua Status</option>
                            <option>Open</option>
                            <option>In Progress</option>
                            <option>Resolved</option>
                            <option>Closed</option>
                        </select>
                    </div>

                    <div>
                        <label class="text-sm font-bold text-slate-700">Kategori</label>
                        <select
                            class="mt-2 w-full px-4 py-3 rounded-2xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500"
                            disabled
                        >
                            <option>Semua Kategori</option>
                            <option>Bug</option>
                            <option>Billing</option>
                            <option>Akun/Login</option>
                            <option>Import/Export</option>
                            <option>AI Insight</option>
                            <option>Request Fitur</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Empty State -->
            <div class="p-10 text-center">
                <div class="w-16 h-16 mx-auto rounded-3xl bg-slate-100 text-slate-500 flex items-center justify-center">
                    <x-lucide-headphones class="w-8 h-8" />
                </div>

                <h3 class="text-xl font-black text-slate-900 mt-5">
                    Belum ada ticket support
                </h3>

                <p class="text-sm text-slate-500 mt-2 max-w-md mx-auto leading-relaxed">
                    Nanti laporan dari owner seperti bug, kendala export, request fitur,
                    masalah akun, atau pertanyaan penggunaan akan muncul di halaman ini.
                </p>

                <div class="mt-6 inline-flex items-center gap-2 px-4 py-2 rounded-full bg-slate-50 border border-slate-200 text-sm font-semibold text-slate-600">
                    <x-lucide-info class="w-4 h-4" />
                    Fitur ticket dinamis akan dibuat di tahap berikutnya.
                </div>
            </div>
        </div>

        <!-- Overview -->
        <div class="bg-[#0F172A] rounded-3xl p-6 text-white shadow-sm">
            <div class="w-12 h-12 rounded-2xl bg-emerald-500 flex items-center justify-center mb-5">
                <x-lucide-message-circle-question class="w-6 h-6" />
            </div>

            <p class="text-sm text-emerald-300 font-bold">Support Overview</p>

            <h3 class="text-2xl font-black mt-3">
                Pusat bantuan internal DagangFlow
            </h3>

            <p class="text-sm text-slate-300 mt-4 leading-relaxed">
                Halaman ini disiapkan untuk memantau kebutuhan user, laporan bug,
                pertanyaan fitur, dan feedback yang masuk dari owner.
            </p>

            <div class="mt-6 space-y-3">
                <div class="p-4 rounded-2xl bg-white/10 border border-white/10">
                    <div class="flex items-start gap-3">
                        <div class="w-9 h-9 rounded-xl bg-white/10 text-emerald-300 flex items-center justify-center shrink-0">
                            <x-lucide-tags class="w-5 h-5" />
                        </div>

                        <div>
                            <p class="text-xs text-slate-400">Kategori Ticket</p>
                            <p class="text-sm font-bold mt-1 leading-relaxed">
                                Bug, Billing, Akun, Import/Export, AI Insight, Request Fitur
                            </p>
                        </div>
                    </div>
                </div>

                <div class="p-4 rounded-2xl bg-white/10 border border-white/10">
                    <div class="flex items-start gap-3">
                        <div class="w-9 h-9 rounded-xl bg-white/10 text-emerald-300 flex items-center justify-center shrink-0">
                            <x-lucide-list-checks class="w-5 h-5" />
                        </div>

                        <div>
                            <p class="text-xs text-slate-400">Status</p>
                            <p class="text-sm font-bold mt-1 leading-relaxed">
                                Open, In Progress, Resolved, Closed
                            </p>
                        </div>
                    </div>
                </div>

                <div class="p-4 rounded-2xl bg-white/10 border border-white/10">
                    <div class="flex items-start gap-3">
                        <div class="w-9 h-9 rounded-xl bg-white/10 text-emerald-300 flex items-center justify-center shrink-0">
                            <x-lucide-rocket class="w-5 h-5" />
                        </div>

                        <div>
                            <p class="text-xs text-slate-400">Next Development</p>
                            <p class="text-sm font-bold mt-1 leading-relaxed">
                                Buat form bantuan dari halaman owner, lalu simpan ticket ke database.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Roadmap -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-200 flex items-start justify-between gap-4">
            <div>
                <h3 class="text-xl font-black">Roadmap Support System</h3>
                <p class="text-sm text-slate-500 mt-1">
                    Tahapan pengembangan fitur support untuk DagangFlow.
                </p>
            </div>

            <div class="w-11 h-11 rounded-2xl bg-slate-100 text-slate-700 flex items-center justify-center shrink-0">
                <x-lucide-map class="w-5 h-5" />
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 p-6">
            <div class="rounded-2xl bg-slate-50 border border-slate-100 p-5">
                <div class="w-10 h-10 rounded-2xl bg-emerald-500 text-white flex items-center justify-center mb-4">
                    <span class="text-sm font-black">1</span>
                </div>

                <h4 class="font-black text-slate-900">UI Support Center</h4>
                <p class="text-sm text-slate-500 mt-2 leading-relaxed">
                    Membuat halaman support statis agar menu Support sudah siap digunakan di superadmin.
                </p>
            </div>

            <div class="rounded-2xl bg-slate-50 border border-slate-100 p-5">
                <div class="w-10 h-10 rounded-2xl bg-blue-500 text-white flex items-center justify-center mb-4">
                    <span class="text-sm font-black">2</span>
                </div>

                <h4 class="font-black text-slate-900">Database Ticket</h4>
                <p class="text-sm text-slate-500 mt-2 leading-relaxed">
                    Menambahkan tabel support_tickets untuk menyimpan laporan bug, pertanyaan, dan request fitur.
                </p>
            </div>

            <div class="rounded-2xl bg-slate-50 border border-slate-100 p-5">
                <div class="w-10 h-10 rounded-2xl bg-purple-500 text-white flex items-center justify-center mb-4">
                    <span class="text-sm font-black">3</span>
                </div>

                <h4 class="font-black text-slate-900">Form Bantuan Owner</h4>
                <p class="text-sm text-slate-500 mt-2 leading-relaxed">
                    Owner bisa mengirim laporan dari halaman bantuan, lalu superadmin bisa memproses ticket.
                </p>
            </div>
        </div>
    </div>
@endsection