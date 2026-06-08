@extends('layouts.dashboard', [
    'title' => 'Riwayat Kendala - DagangFlow',
    'pageTitle' => 'Riwayat Kendala',
    'subtitle' => 'Pantau status dan balasan dari tim support DagangFlow untuk kendala yang kamu laporkan'
])

@section('actions')
    <div class="flex items-center gap-2">
        <a href="{{ route('help') }}" class="px-4 py-2 rounded-xl border border-slate-200 bg-white text-slate-700 text-sm font-semibold hover:bg-slate-50 transition">
            Kembali ke Bantuan
        </a>
        <a href="{{ route('owner.support.create') }}" class="px-4 py-2 rounded-xl bg-emerald-500 text-white text-sm font-semibold hover:bg-emerald-600 transition">
            Laporkan Kendala Baru
        </a>
    </div>
@endsection

@section('content')
    <div class="space-y-6">
        @if (session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-5 py-4 rounded-2xl text-sm font-semibold">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
            @if($tickets->isEmpty())
                <div class="p-12 text-center">
                    <div class="w-16 h-16 bg-slate-50 rounded-2xl text-slate-400 flex items-center justify-center mx-auto mb-4">
                        <x-lucide-message-square-off class="w-8 h-8" />
                    </div>
                    <h3 class="text-lg font-black text-slate-900">Belum ada riwayat kendala</h3>
                    <p class="text-sm text-slate-500 mt-1 max-w-sm mx-auto">
                        Semua laporan kendala atau pertanyaan yang kamu kirimkan ke tim support akan muncul di sini.
                    </p>
                    <a href="{{ route('owner.support.create') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-emerald-500 text-white text-sm font-semibold hover:bg-emerald-600 transition mt-5">
                        Buat Laporan Pertama
                    </a>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-100 bg-slate-50/75 text-xs font-bold text-slate-500 uppercase tracking-wider">
                                <th class="py-4 px-6">Tanggal</th>
                                <th class="py-4 px-6">Kategori / Subjek</th>
                                <th class="py-4 px-6">Prioritas</th>
                                <th class="py-4 px-6">Status</th>
                                <th class="py-4 px-6 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-sm">
                            @foreach($tickets as $ticket)
                                <tr class="hover:bg-slate-50/50 transition">
                                    <td class="py-4 px-6 text-slate-600 whitespace-nowrap">
                                        {{ $ticket->created_at->format('d M Y, H:i') }}
                                    </td>
                                    <td class="py-4 px-6">
                                        <span class="inline-block text-xs font-semibold px-2 py-0.5 rounded-md bg-slate-100 text-slate-700 mb-1">
                                            {{ $ticket->category }}
                                        </span>
                                        <div class="font-bold text-slate-900 line-clamp-1">
                                            {{ $ticket->subject }}
                                        </div>
                                    </td>
                                    <td class="py-4 px-6 whitespace-nowrap">
                                        @if($ticket->priority === 'urgent')
                                            <span class="inline-flex items-center gap-1 text-xs font-bold text-red-700 bg-red-50 border border-red-100 px-2.5 py-1 rounded-full">
                                                <span class="w-1.5 h-1.5 rounded-full bg-red-500 animate-pulse"></span> Urgent
                                            </span>
                                        @elseif($ticket->priority === 'high')
                                            <span class="text-xs font-bold text-amber-700 bg-amber-50 border border-amber-100 px-2.5 py-1 rounded-full">
                                                Tinggi
                                            </span>
                                        @elseif($ticket->priority === 'normal')
                                            <span class="text-xs font-bold text-blue-700 bg-blue-50 border border-blue-100 px-2.5 py-1 rounded-full">
                                                Normal
                                            </span>
                                        @else
                                            <span class="text-xs font-bold text-slate-600 bg-slate-100 px-2.5 py-1 rounded-full">
                                                Rendah
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-6 whitespace-nowrap">
                                        @if($ticket->status === 'open')
                                            <span class="text-xs font-bold text-emerald-700 bg-emerald-50 border border-emerald-100 px-2.5 py-1 rounded-full">
                                                Terbuka
                                            </span>
                                        @else
                                            <span class="text-xs font-bold text-slate-500 bg-slate-100 border border-slate-200 px-2.5 py-1 rounded-full">
                                                Selesai
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-6 text-right whitespace-nowrap">
                                        <a href="{{ route('owner.support.show', $ticket->id) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl border border-slate-200 bg-white text-xs font-bold text-slate-700 hover:bg-slate-50 transition shadow-sm">
                                            <x-lucide-eye class="w-3.5 h-3.5" />
                                            Lihat Detail
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if($tickets->hasPages())
                    <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50">
                        {{ $tickets->links() }}
                    </div>
                @endif
            @endif
        </div>
    </div>
@endsection