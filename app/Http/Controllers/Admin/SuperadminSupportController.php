<?php

namespace App\Http\Controllers\Admin;

use App\Models\SupportTicket;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SuperadminSupportController extends Controller
{
    /**
     * 1. Menampilkan semua tiket support dengan pagination
     */
    public function index()
    {
        // Ambil semua tiket, urutkan berdasarkan status dan waktu terbaru
        $tickets = SupportTicket::with('user')
            ->orderByRaw("CASE 
                WHEN status = 'open' THEN 1 
                WHEN status = 'in_progress' THEN 2 
                ELSE 3 
            END")
            ->latest()
            ->paginate(15);

        // Hitung tiket belum diproses (open)
        $unreadCount = SupportTicket::where('status', 'open')->count();

        return view('admin.support.index', compact('tickets', 'unreadCount'));
    }

    /**
     * 2. Menampilkan detail tiket untuk reply
     */
    public function show($id)
    {
        $ticket = SupportTicket::findOrFail($id);
        
        // Hitung tiket belum diproses (open) untuk badge sidebar
        $unreadCount = SupportTicket::where('status', 'open')->count();

        return view('admin.support.show', compact('ticket', 'unreadCount'));
    }

    /**
     * 3. Membalas dan update status tiket
     */
    public function reply(Request $request, $id)
    {
        $ticket = SupportTicket::findOrFail($id);

        $data = $request->validate([
            'status' => ['required', 'in:in_progress,resolved'],
            'admin_reply' => ['required', 'string', 'min:5'],
        ], [
            'status.required' => 'Status wajib dipilih.',
            'status.in' => 'Status hanya bisa "Diproses" atau "Selesai".',
            'admin_reply.required' => 'Balasan admin wajib diisi.',
            'admin_reply.min' => 'Balasan minimal 5 karakter.',
        ]);

        $ticket->update([
            'status' => $data['status'],
            'admin_reply' => $data['admin_reply'],
            'resolved_at' => $data['status'] === 'resolved' ? now() : null,
        ]);

        return redirect()->route('admin.support.index')->with('success', 'Tiket berhasil diupdate dan owner telah diberitahu.');
    }
}
