@extends('layouts.admin', [
    'title' => 'Support - Superadmin DagangFlow',
    'pageTitle' => 'Support Center',
    'subtitle' => 'Superadmin / Support',
])

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-5">
        <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm">
            <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center mb-5">
                <x-lucide-inbox class="w-6 h-6" />
            </div>
            <p class="text-sm text-slate-500">Total Ticket</p>
            <h3 class="text-4xl font-black mt-3">{{ $stats['total'] }}</h3>
        </div>

        <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm">
            <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center mb-5">
                <x-lucide-circle-alert class="w-6 h-6" />
            </div>
            <p class="text-sm text-slate-500">Open</p>
            <h3 class="text-4xl font-black mt-3 text-amber-600">{{ $stats['open'] }}</h3>
        </div>

        <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm">
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center mb-5">
                <x-lucide-check-circle class="w-6 h-6" />
            </div>
            <p class="text-sm text-slate-500">Resolved</p>
            <h3 class="text-4xl font-black mt-3 text-emerald-600">{{ $stats['resolved'] }}</h3>
        </div>

        <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm">
            <div class="w-12 h-12 rounded-2xl bg-red-50 text-red-600 flex items-center justify-center mb-5">
                <x-lucide-flame class="w-6 h-6" />
            </div>
            <p class="text-sm text-slate-500">High Priority</p>
            <h3 class="text-4xl font-black mt-3 text-red-600">{{ $stats['high_priority'] }}</h3>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        <div class="xl:col-span-2 bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden flex flex-col justify-between">
            <div>
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

                <div class="p-6 border-b border-slate-100 bg-slate-50/50">
                    <form method="GET" action="{{ route('admin.support.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="text-sm font-bold text-slate-700">Cari Ticket</label>
                            <input
                                type="text"
                                name="search"
                                value="{{ request('search') }}"
                                placeholder="Cari subject atau nama user..."
                                class="mt-2 w-full px-4 py-3 rounded-2xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500"
                                onchange="this.form.submit()"
                            >
                        </div>

                        <div>
                            <label class="text-sm font-bold text-slate-700">Status</label>
                            <select
                                name="status"
                                class="mt-2 w-full px-4 py-3 rounded-2xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500"
                                onchange="this.form.submit()"
                            >
                                <option value="">Semua Status</option>
                                <option value="open" {{ request('status') === 'open' ? 'selected' : '' }}>Open</option>
                                <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="resolved" {{ request('status') === 'resolved' ? 'selected' : '' }}>Resolved</option>
                                <option value="closed" {{ request('status') === 'closed' ? 'selected' : '' }}>Closed</option>
                            </select>
                        </div>

                        <div>
                            <label class="text-sm font-bold text-slate-700">Kategori</label>
                            <select
                                name="category"
                                class="mt-2 w-full px-4 py-3 rounded-2xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500"
                                onchange="this.form.submit()"
                            >
                                <option value="">Semua Kategori</option>
                                @foreach(['Bug', 'Billing', 'Akun/Login', 'Import/Export', 'AI Insight', 'Request Fitur', 'Lainnya'] as $cat)
                                    <option value="{{ $cat }}" {{ request('category') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                                @endforeach
                            </select>
                        </div>
                    </form>
                </div>

                <div class="divide-y divide-slate-100">
                    @forelse($tickets as $ticket)
                        <div class="p-6 hover:bg-slate-50/80 transition flex items-center justify-between gap-4">
                            <div class="space-y-1">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold uppercase
                                        {{ $ticket->status === 'open' ? 'bg-amber-50 text-amber-700 border border-amber-200' : '' }}
                                        {{ $ticket->status === 'in_progress' ? 'bg-blue-50 text-blue-700 border border-blue-200' : '' }}
                                        {{ $ticket->status === 'resolved' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : '' }}
                                        {{ $ticket->status === 'closed' ? 'bg-slate-100 text-slate-600' : '' }}
                                    ">
                                        {{ $ticket->status }}
                                    </span>
                                    
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-slate-100 text-slate-600">
                                        {{ $ticket->category }}
                                    </span>

                                    @if($ticket->priority === 'high')
                                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-red-50 text-red-600 border border-red-100 capitalize">
                                            {{ $ticket->priority }} Priority
                                        </span>
                                    @endif
                                </div>
                                
                                <a href="{{ route('admin.support.show', $ticket->id) }}" class="block font-black text-slate-900 text-base hover:text-emerald-600 transition pt-1">
                                    {{ $ticket->subject }}
                                </a>
                                
                                <p class="text-xs text-slate-500">
                                    Oleh: <span class="font-semibold text-slate-700">{{ $ticket->user->name }}</span> &bull; {{ $ticket->created_at->diffForHumans() }}
                                </p>
                            </div>
                            
                            <a href="{{ route('admin.support.show', $ticket->id) }}" class="p-2.5 rounded-xl bg-slate-50 hover:bg-emerald-50 text-slate-400 hover:text-emerald-600 transition shrink-0">
                                <x-lucide-chevron-right class="w-5 h-5" />
                            </a>
                        </div>
                    @empty
                        <div class="p-10 text-center">
                            <div class="w-16 h-16 mx-auto rounded-3xl bg-slate-100 text-slate-500 flex items-center justify-center">
                                <x-lucide-headphones class="w-8 h-8" />
                            </div>
                            <h3 class="text-xl font-black text-slate-900 mt-5">Belum ada ticket support</h3>
                            <p class="text-sm text-slate-500 mt-2 max-w-md mx-auto leading-relaxed">
                                Nanti laporan dari owner seperti bug, kendala export, request fitur, masalah akun, atau pertanyaan penggunaan akan muncul di halaman ini.
                            </p>
                        </div>
                    @endforelse
                </div>
            </div>

            @if($tickets->hasPages())
                <div class="p-6 border-t border-slate-100">
                    {{ $tickets->links() }}
                </div>
            @endif
        </div>

        <div class="bg-[#0F172A] rounded-3xl p-6 text-white shadow-sm h-fit">
            <div class="w-12 h-12 rounded-2xl bg-emerald-500 flex items-center justify-center mb-5">
                <x-lucide-message-circle-question class="w-6 h-6" />
            </div>

            <p class="text-sm text-emerald-300 font-bold">Support Overview</p>
            <h3 class="text-2xl font-black mt-3">Pusat bantuan internal DagangFlow</h3>
            <p class="text-sm text-slate-300 mt-4 leading-relaxed">
                Halaman ini disiapkan untuk memantau kebutuhan user, laporan bug, pertanyaan fitur, dan feedback yang masuk dari owner.
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
                                Bug, Billing, Akun/Login, Import/Export, AI Insight, Request Fitur, Lainnya
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
                                open, in_progress, resolved, closed
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden mt-6">
        <div class="p-6 border-b border-slate-200 flex items-start justify-between gap-4">
            <div>
                <h3 class="text-xl font-black">Roadmap Support System</h3>
                <p class="text-sm text-slate-500 mt-1">Tahapan pengembangan fitur support untuk DagangFlow.</p>
            </div>
            <div class="w-11 h-11 rounded-2xl bg-slate-100 text-slate-700 flex items-center justify-center shrink-0">
                <x-lucide-map class="w-5 h-5" />
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 p-6">
            <div class="rounded-2xl bg-slate-50 border border-slate-100 p-5">
                <div class="w-10 h-10 rounded-2xl bg-emerald-500 text-white flex items-center justify-center mb-4">
                    <x-lucide-check class="w-4 h-4" />
                </div>
                <h4 class="font-black text-slate-900">UI Support Center</h4>
                <p class="text-sm text-slate-500 mt-2 leading-relaxed">Halaman dashboard support superadmin dan filter data real-time berhasil diaktifkan.</p>
            </div>

            <div class="rounded-2xl bg-slate-50 border border-slate-100 p-5">
                <div class="w-10 h-10 rounded-2xl bg-emerald-500 text-white flex items-center justify-center mb-4">
                    <x-lucide-check class="w-4 h-4" />
                </div>
                <h4 class="font-black text-slate-900">Database Ticket</h4>
                <p class="text-sm text-slate-500 mt-2 leading-relaxed">Tabel `support_tickets` telah menggunakan struktur migrasi kustom milikmu yang dioptimasi dengan indexing.</p>
            </div>

            <div class="rounded-2xl bg-slate-50 border border-slate-100 p-5">
                <div class="w-10 h-10 rounded-2xl bg-blue-500 text-white flex items-center justify-center mb-4">
                    <span class="text-sm font-black">3</span>
                </div>
                <h4 class="font-black text-slate-900">Form Bantuan Owner</h4>
                <p class="text-sm text-slate-500 mt-2 leading-relaxed">Langkah selanjutnya tinggal menghubungkan halaman ini dengan form input dari sisi owner.</p>
            </div>
        </div>
    </div>
@endsection