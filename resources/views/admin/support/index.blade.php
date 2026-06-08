@extends('layouts.admin', [
    'title' => 'Support Tickets - DagangFlow',
    'pageTitle' => 'Support Tickets',
    'subtitle' => 'Kelola laporan kendala dari pengguna',
    'unreadCount' => $unreadCount ?? 0
])

@section('content')
    <div class="space-y-6">
        <!-- Filter & Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-white rounded-2xl border border-slate-200 p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-slate-500 font-medium">Total Tiket</p>
                        <p class="text-3xl font-black text-slate-900 mt-2">{{ $tickets->total() }}</p>
                    </div>
                    <x-lucide-ticket class="w-8 h-8 text-slate-300" />
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-slate-200 p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-slate-500 font-medium">Baru</p>
                        <p class="text-3xl font-black text-amber-600 mt-2">{{ \App\Models\SupportTicket::where('status', 'open')->count() }}</p>
                    </div>
                    <x-lucide-alert-circle class="w-8 h-8 text-amber-300" />
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-slate-200 p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-slate-500 font-medium">Diproses</p>
                        <p class="text-3xl font-black text-blue-600 mt-2">{{ \App\Models\SupportTicket::where('status', 'in_progress')->count() }}</p>
                    </div>
                    <x-lucide-clock-circle class="w-8 h-8 text-blue-300" />
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-slate-200 p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-slate-500 font-medium">Selesai</p>
                        <p class="text-3xl font-black text-emerald-600 mt-2">{{ \App\Models\SupportTicket::where('status', 'resolved')->count() }}</p>
                    </div>
                    <x-lucide-check-circle class="w-8 h-8 text-emerald-300" />
                </div>
            </div>
        </div>

        <!-- Tickets Table -->
        <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
            <div class="p-6 border-b border-slate-200">
                <h3 class="text-lg font-black text-slate-900">Daftar Tiket Support</h3>
            </div>

            @if($tickets->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-slate-50 border-b border-slate-200">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-bold text-slate-600 uppercase">Pengguna</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-slate-600 uppercase">Subjek</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-slate-600 uppercase">Kategori</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-slate-600 uppercase">Prioritas</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-slate-600 uppercase">Status</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-slate-600 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                            @foreach($tickets as $ticket)
                                <tr class="hover:bg-slate-50 transition">
                                    <td class="px-6 py-4">
                                        <div>
                                            <p class="font-semibold text-slate-900">{{ $ticket->user->name }}</p>
                                            <p class="text-xs text-slate-500">{{ $ticket->user->email }}</p>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <p class="text-sm font-medium text-slate-900 truncate max-w-xs">{{ $ticket->subject }}</p>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-slate-100 text-slate-700">
                                            {{ $ticket->category }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full {{ 
                                            $ticket->priority === 'normal' ? 'bg-blue-50 text-blue-700' :
                                            ($ticket->priority === 'high' ? 'bg-amber-50 text-amber-700' :
                                            ($ticket->priority === 'urgent' ? 'bg-red-50 text-red-700' :
                                            'bg-slate-100 text-slate-700'))
                                        }}">
                                            {{ $ticket->priority_label }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full {{ $ticket->status_class }}">
                                            {{ $ticket->status_label }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <a href="{{ route('admin.support.show', $ticket->id) }}" 
                                            class="inline-flex items-center gap-2 px-3 py-2 text-xs font-bold text-emerald-600 hover:bg-emerald-50 rounded-lg transition">
                                            <x-lucide-eye class="w-4 h-4" />
                                            Lihat
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="px-6 py-4 border-t border-slate-200">
                    {{ $tickets->links() }}
                </div>
            @else
                <div class="p-12 text-center">
                    <x-lucide-inbox class="w-16 h-16 text-slate-300 mx-auto mb-4" />
                    <p class="text-slate-500 font-medium">Tidak ada tiket support</p>
                </div>
            @endif
        </div>
    </div>
@endsection