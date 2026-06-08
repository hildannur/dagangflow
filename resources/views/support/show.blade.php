@extends('layouts.dashboard', [
    'title' => 'Detail Kendala - DagangFlow',
    'pageTitle' => 'Detail Kendala',
    'subtitle' => 'Lihat detail laporan kendala dan tanggapan resmi dari Tim Support DagangFlow'
])

@section('actions')
    <a href="{{ route('owner.support.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl border border-slate-200 bg-white text-slate-700 text-sm font-semibold hover:bg-slate-50 transition">
        <x-lucide-arrow-left class="w-4 h-4" />
        Kembali ke Riwayat
    </a>
@endsection

@section('content')
    <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 items-start">
        
        <!-- Kolom Kiri: Konten Percakapan / Isi Tiket -->
        <div class="xl:col-span-8 space-y-6">
            
            <!-- Pesan dari Owner (Tiket Awal) -->
            <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-sm">
                            {{ strtoupper(substr(auth()->user()->name ?? 'O', 0, 2)) }} </div>
                        <div>
                            <h4 class="font-black text-slate-900">Kamu (Laporan Awal)</h4>
                            <p class="text-xs text-slate-500 mt-0.5">
                                Ditulis pada {{ $ticket->created_at->format('d M Y - H:i') }} WIB
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="p-6 space-y-4">
                    <div>
                        <span class="text-xs font-semibold px-2.5 py-1 rounded-md bg-slate-100 text-slate-700">
                            Subjek: {{ $ticket->subject }}
                        </span>
                    </div>
                    <div class="text-sm text-slate-700 leading-relaxed whitespace-pre-line bg-slate-50 p-5 rounded-2xl border border-slate-100">
                        {{ $ticket->message }}
                    </div>
                </div>
            </div>

            <!-- Tanggapan / Balasan Admin -->
            @if($ticket->admin_reply)
                <div class="bg-white rounded-3xl border border-blue-100 shadow-sm overflow-hidden relative">
                    <!-- Aksen dekoratif penanda balasan admin -->
                    <div class="absolute top-0 left-0 right-0 h-1 bg-blue-500"></div>
                    
                    <div class="p-6 border-b border-slate-100 bg-blue-50/50 flex items-center justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-blue-500 text-white flex items-center justify-center">
                                <x-lucide-shield-check class="w-5 h-5" />
                            </div>
                            <div>
                                <h4 class="font-black text-slate-900 flex items-center gap-1.5">
                                    Tim Support DagangFlow
                                    <span class="text-[10px] bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full font-bold uppercase tracking-wider">Official</span>
                                </h4>
                                <p class="text-xs text-slate-500 mt-0.5">
                                    Dibalas pada {{ $ticket->updated_at->format('d M Y - H:i') }} WIB
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="p-6">
                        <div class="text-sm text-slate-700 leading-relaxed whitespace-pre-line bg-blue-50/20 p-5 rounded-2xl border border-blue-50/50">
                            {{ $ticket->admin_reply }}
                        </div>
                    </div>
                </div>
            @else
                <!-- Kondisi Belum Ada Balasan -->
                <div class="bg-amber-50 border border-amber-200 rounded-3xl p-6 text-center">
                    <div class="w-12 h-12 bg-amber-100 text-amber-700 flex items-center justify-center rounded-2xl mx-auto mb-3">
                        <x-lucide-clock class="w-6 h-6 animate-spin" style="animation-duration: 3s;" />
                    </div>
                    <h4 class="font-black text-amber-900">Menunggu Tanggapan</h4>
                    <p class="text-sm text-amber-700 mt-1 max-w-md mx-auto leading-relaxed">
                        Laporan kamu sudah masuk ke sistem internal kami. Tim support DagangFlow sedang memeriksa kendala ini. Mohon ditunggu ya!
                    </p>
                </div>
            @endif

        </div>

        <!-- Kolom Kanan: Meta Data / Ringkasan Tiket -->
        <div class="xl:col-span-4 space-y-6">
            <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 space-y-5">
                <h3 class="font-black text-slate-900 text-base border-b border-slate-100 pb-3">
                    Informasi Tiket
                </h3>

                <div class="space-y-4 text-sm">
                    <div>
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">ID Tiket</p>
                        <p class="font-mono font-bold text-slate-800 mt-1">#DF-{{ str_pad($ticket->id, 5, '0', STR_PAD_LEFT) }}</p>
                    </div>

                    <div>
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Kategori</p>
                        <p class="font-bold text-slate-800 mt-1">{{ $ticket->category }}</p>
                    </div>

                    <div>
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Tingkat Prioritas</p>
                        <div class="mt-1">
                            @if($ticket->priority === 'urgent')
                                <span class="inline-flex items-center gap-1.5 text-xs font-bold text-red-700 bg-red-50 border border-red-100 px-2.5 py-1 rounded-full">
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
                        </div>
                    </div>

                    <div>
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Status Saat Ini</p>
                        <div class="mt-1">
                            @if($ticket->status === 'open')
                                <span class="text-xs font-bold text-emerald-700 bg-emerald-50 border border-emerald-100 px-2.5 py-1 rounded-full">
                                    Terbuka (Diproses)
                                </span>
                            @else
                                <span class="text-xs font-bold text-slate-500 bg-slate-100 border border-slate-200 px-2.5 py-1 rounded-full">
                                    Selesai (Closed)
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Tip Box Ringan -->
            <div class="bg-slate-900 text-slate-300 rounded-3xl p-5 relative overflow-hidden shadow-sm">
                <div class="absolute -right-6 -bottom-6 text-white/5 pointer-events-none">
                    <x-lucide-life-buoy class="w-24 h-24" />
                </div>
                <h5 class="text-white font-bold text-sm flex items-center gap-1.5">
                    <x-lucide-lightbulb class="w-4 h-4 text-amber-400" />
                    Catatan Penting
                </h5>
                <p class="text-xs text-slate-400 mt-2 leading-relaxed">
                    Jika kendala kamu sudah selesai dijawab dan tuntas, tim admin akan mengubah status tiket menjadi <span class="text-white font-semibold">Selesai</span>. Kamu tidak perlu membuat laporan baru untuk kendala yang sama.
                </p>
            </div>
        </div>

    </div>
@endsection