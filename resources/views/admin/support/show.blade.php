@extends('layouts.admin', [
    'title' => 'Detail Ticket - Superadmin DagangFlow',
    'pageTitle' => 'Ticket #' . $ticket->id,
    'subtitle' => 'Superadmin / Support / Detail',
])

@section('content')
    <div class="mb-6">
        <a href="{{ route('admin.support.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-slate-600 hover:text-emerald-600 transition">
            <x-lucide-arrow-left class="w-4 h-4" /> Kembali ke Daftar Ticket
        </a>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        <div class="xl:col-span-2 space-y-6">
            <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-slate-200 bg-slate-50/50 flex items-center justify-between gap-4">
                    <div>
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold uppercase
                            {{ $ticket->status === 'open' ? 'bg-amber-50 text-amber-700 border border-amber-200' : '' }}
                            {{ $ticket->status === 'in_progress' ? 'bg-blue-50 text-blue-700 border border-blue-200' : '' }}
                            {{ $ticket->status === 'resolved' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : '' }}
                            {{ $ticket->status === 'closed' ? 'bg-slate-100 text-slate-600' : '' }}
                        ">
                            {{ $ticket->status }}
                        </span>
                        <h3 class="text-xl font-black text-slate-900 mt-2">{{ $ticket->subject }}</h3>
                    </div>
                    <div class="text-right text-xs text-slate-500 shrink-0">
                        {{ $ticket->created_at->format('d M Y, H:i') }}
                    </div>
                </div>

                <div class="p-6 space-y-4">
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 rounded-full bg-slate-100 text-slate-600 flex items-center justify-center font-bold shrink-0">
                            {{ strtoupper(substr($ticket->user->name, 0, 2)) }}
                        </div>
                        <div class="bg-slate-100 rounded-2xl rounded-tl-none p-4 text-slate-800 text-sm max-w-full whitespace-pre-line leading-relaxed">
                            {{ $ticket->message }}
                        </div>
                    </div>

                    @if($ticket->admin_reply)
                        <div class="flex items-start gap-3 justify-end">
                            <div class="bg-emerald-500 rounded-2xl rounded-tr-none p-4 text-white text-sm max-w-full whitespace-pre-line leading-relaxed">
                                <p class="font-bold text-xs text-emerald-100 mb-1">Balasan Anda:</p>
                                {{ $ticket->admin_reply }}
                            </div>
                            <div class="w-10 h-10 rounded-full bg-emerald-600 text-white flex items-center justify-center font-bold shrink-0">
                                SA
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6">
                <h3 class="text-lg font-black text-slate-900 mb-4">Berikan Tanggapan</h3>
                
                <form action="{{ route('admin.support.reply', $ticket->id) }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="text-sm font-bold text-slate-700">Pesan Balasan</label>
                        <textarea 
                            name="admin_reply" 
                            rows="5" 
                            placeholder="Tulis solusi atau jawaban untuk owner bisnis di sini..."
                            class="mt-2 w-full px-4 py-3 rounded-2xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 @error('admin_reply') border-red-500 @enderror"
                            required
                        >{{ old('admin_reply', $ticket->admin_reply) }}</textarea>
                        @error('admin_reply')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm font-bold text-slate-700">Update Status Tiket</label>
                            <select 
                                name="status" 
                                class="mt-2 w-full px-4 py-3 rounded-2xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500"
                            >
                                <option value="open" {{ $ticket->status === 'open' ? 'selected' : '' }}>Open (Belum Selesai)</option>
                                <option value="in_progress" {{ $ticket->status === 'in_progress' ? 'selected' : '' }}>In Progress (Sedang Diproses)</option>
                                <option value="resolved" {{ $ticket->status === 'resolved' ? 'selected' : '' }}>Resolved (Selesai/Tuntas)</option>
                                <option value="closed" {{ $ticket->status === 'closed' ? 'selected' : '' }}>Closed (Ditutup)</option>
                            </select>
                        </div>

                        <div class="flex items-end">
                            <button type="submit" class="w-full bg-emerald-500 hover:bg-emerald-600 text-white font-bold text-sm px-6 py-3.5 rounded-2xl shadow-sm hover:shadow transition flex items-center justify-center gap-2">
                                <x-lucide-send class="w-4 h-4" /> Kirim & Update Tiket
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-[#0F172A] rounded-3xl p-6 text-white shadow-sm">
                <h3 class="text-lg font-black mb-4 flex items-center gap-2">
                    <x-lucide-info class="w-5 h-5 text-emerald-400" /> Informasi Tiket
                </h3>
                
                <div class="space-y-4 text-sm">
                    <div class="border-b border-white/10 pb-3">
                        <p class="text-xs text-slate-400">Pengirim (Owner)</p>
                        <p class="font-bold text-white mt-0.5">{{ $ticket->user->name }}</p>
                        <p class="text-xs text-slate-400 mt-0.5">{{ $ticket->user->email }}</p>
                    </div>

                    <div class="border-b border-white/10 pb-3">
                        <p class="text-xs text-slate-400">Kategori Kendala</p>
                        <p class="font-bold text-emerald-300 mt-0.5 capitalize">{{ $ticket->category }}</p>
                    </div>

                    <div class="border-b border-white/10 pb-3">
                        <p class="text-xs text-slate-400">Tingkat Prioritas</p>
                        <p class="font-bold mt-0.5 capitalize {{ $ticket->priority === 'high' ? 'text-red-400' : 'text-slate-200' }}">
                            {{ $ticket->priority }} Priority
                        </p>
                    </div>

                    @if($ticket->resolved_at)
                        <div>
                            <p class="text-xs text-slate-400">Diselesaikan Pada</p>
                            <p class="font-bold text-emerald-400 mt-0.5">{{ $ticket->resolved_at->format('d M Y, H:i') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection