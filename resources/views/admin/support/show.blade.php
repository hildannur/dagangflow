@extends('layouts.admin', [
    'title' => 'Detail Support Ticket - DagangFlow',
    'pageTitle' => 'Detail Tiket Support',
    'subtitle' => 'Lihat detail tiket dan berikan balasan',
    'unreadCount' => 0
])

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Ticket Info -->
            <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
                <div class="p-6 border-b border-slate-200">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <h3 class="text-xl font-black text-slate-900">{{ $ticket->subject }}</h3>
                            <p class="text-sm text-slate-500 mt-1">ID Tiket: #{{ $ticket->id }}</p>
                        </div>
                        <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full {{ $ticket->status_class }}">
                            {{ $ticket->status_label }}
                        </span>
                    </div>
                </div>

                <div class="p-6 space-y-4">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div>
                            <p class="text-xs text-slate-500 font-medium uppercase">Kategori</p>
                            <p class="text-sm font-semibold text-slate-900 mt-1">{{ $ticket->category }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500 font-medium uppercase">Prioritas</p>
                            <p class="text-sm font-semibold text-slate-900 mt-1">{{ $ticket->priority_label }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500 font-medium uppercase">Dibuat</p>
                            <p class="text-sm font-semibold text-slate-900 mt-1">{{ $ticket->created_at->format('d M Y') }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500 font-medium uppercase">Pengguna</p>
                            <p class="text-sm font-semibold text-slate-900 mt-1">{{ $ticket->user->name }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Message -->
            <div class="bg-white rounded-2xl border border-slate-200 p-6">
                <h4 class="text-sm font-black text-slate-900 uppercase mb-4">Pesan dari Pengguna</h4>
                <div class="p-4 rounded-xl bg-slate-50 border border-slate-200">
                    <p class="text-sm text-slate-700 leading-relaxed whitespace-pre-wrap">{{ $ticket->message }}</p>
                </div>
            </div>

            <!-- Admin Reply Section -->
            <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
                <div class="p-6 border-b border-slate-200">
                    <h4 class="text-sm font-black text-slate-900 uppercase">
                        {{ $ticket->admin_reply ? 'Balasan Admin' : 'Berikan Balasan' }}
                    </h4>
                </div>

                @if($ticket->admin_reply)
                    <div class="p-6">
                        <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200">
                            <p class="text-sm text-emerald-800 leading-relaxed whitespace-pre-wrap">{{ $ticket->admin_reply }}</p>
                        </div>
                        <p class="text-xs text-slate-500 mt-3">Dibalas pada {{ $ticket->resolved_at?->format('d M Y H:i') ?? $ticket->updated_at->format('d M Y H:i') }}</p>
                    </div>
                @else
                    <form action="{{ route('admin.support.reply', $ticket->id) }}" method="POST" class="p-6 space-y-4">
                        @csrf

                        <div>
                            <label class="text-sm font-bold text-slate-700">Status Perbaikan</label>
                            <select name="status" class="mt-2 w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500" required>
                                <option value="in_progress" selected>Diproses</option>
                                <option value="resolved">Selesai</option>
                            </select>
                        </div>

                        <div>
                            <label class="text-sm font-bold text-slate-700">Balasan</label>
                            <textarea name="admin_reply" rows="5" 
                                placeholder="Tuliskan balasan untuk pengguna..." 
                                class="mt-2 w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500" 
                                required></textarea>
                            <p class="text-xs text-slate-500 mt-2">Minimal 5 karakter</p>
                        </div>

                        <div class="flex gap-3 pt-2">
                            <button type="submit" class="inline-flex items-center gap-2 px-5 py-3 rounded-xl bg-emerald-500 text-white text-sm font-bold hover:bg-emerald-600 transition">
                                <x-lucide-send class="w-4 h-4" />
                                Kirim Balasan
                            </button>
                            <a href="{{ route('admin.support.index') }}" class="inline-flex items-center gap-2 px-5 py-3 rounded-xl border border-slate-200 text-sm font-bold hover:bg-slate-50 transition">
                                Batal
                            </a>
                        </div>
                    </form>
                @endif
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- User Info -->
            <div class="bg-white rounded-2xl border border-slate-200 p-6">
                <h4 class="text-sm font-black text-slate-900 uppercase mb-4">Informasi Pengguna</h4>
                <div class="space-y-4">
                    <div>
                        <p class="text-xs text-slate-500 font-medium">Nama</p>
                        <p class="text-sm font-semibold text-slate-900 mt-1">{{ $ticket->user->name }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 font-medium">Email</p>
                        <p class="text-sm font-semibold text-slate-900 mt-1">{{ $ticket->user->email }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 font-medium">Bisnis</p>
                        <p class="text-sm font-semibold text-slate-900 mt-1">{{ $ticket->user->business_name ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 font-medium">Paket</p>
                        <p class="text-sm font-semibold text-slate-900 mt-1">{{ $ticket->user->plan_name ?? 'Free' }}</p>
                    </div>
                </div>
            </div>

            <!-- Ticket Timeline -->
            <div class="bg-white rounded-2xl border border-slate-200 p-6">
                <h4 class="text-sm font-black text-slate-900 uppercase mb-4">Riwayat</h4>
                <div class="space-y-4">
                    <div class="flex gap-3">
                        <div class="w-8 h-8 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center shrink-0 text-xs font-bold">
                            1
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-slate-900">Tiket Dibuat</p>
                            <p class="text-xs text-slate-500 mt-1">{{ $ticket->created_at->format('d M Y H:i') }}</p>
                        </div>
                    </div>

                    @if($ticket->admin_reply)
                        <div class="flex gap-3">
                            <div class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0 text-xs font-bold">
                                2
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-slate-900">{{ $ticket->status_label }}</p>
                                <p class="text-xs text-slate-500 mt-1">{{ $ticket->resolved_at?->format('d M Y H:i') ?? $ticket->updated_at->format('d M Y H:i') }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection