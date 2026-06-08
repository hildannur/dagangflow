<?php

namespace App\Http\Controllers;

use App\Models\SupportTicket;
use Illuminate\Http\Request;

class OwnerSupportController extends Controller
{
    /**
     * 1. Menampilkan semua riwayat tiket milik Owner yang sedang login
     */
    public function index()
    {
        // Mengambil tiket khusus milik user yang login saat ini
        $tickets = SupportTicket::where('user_id', auth()->id())
            ->latest()
            ->paginate(10); // Menggunakan pagination agar rapi kalau tiketnya sudah banyak

        return view('support.index', compact('tickets'));
    }

    /**
     * 2. Menampilkan form pembuatan tiket baru
     */
    public function create()
    {
        $categories = [
            'Akun',
            'Subscription',
            'Pembayaran',
            'Bug Aplikasi',
            'Produk & Stok',
            'Penjualan',
            'Pengeluaran',
            'Laporan',
            'AI Insight',
            'Export Data',
            'Lainnya',
        ];

        $priorities = [
            'low' => 'Rendah',
            'normal' => 'Normal',
            'high' => 'Tinggi',
            'urgent' => 'Urgent',
        ];

        return view('support.create', compact(
            'categories',
            'priorities'
        ));
    }

    /**
     * 3. Menyimpan tiket baru ke dalam database
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'subject' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:255'],
            'priority' => ['required', 'in:low,normal,high,urgent'],
            'message' => ['required', 'string', 'min:10'],
        ], [
            'subject.required' => 'Subjek kendala wajib diisi.',
            'category.required' => 'Kategori kendala wajib dipilih.',
            'priority.required' => 'Prioritas kendala wajib dipilih.',
            'message.required' => 'Detail kendala wajib diisi.',
            'message.min' => 'Detail kendala minimal 10 karakter.',
        ]);

        SupportTicket::create([
            'user_id' => auth()->id(),
            'subject' => $data['subject'],
            'category' => $data['category'],
            'priority' => $data['priority'],
            'status' => 'open',
            'message' => $data['message'],
        ]);

        // Dialihkan langsung ke halaman riwayat tiket agar owner bisa melihat tiket yang baru dibuat
        return redirect()->route('owner.support.index')->with('success', 'Kendala berhasil dilaporkan. Tim DagangFlow akan meninjau laporan kamu.');
    }

    /**
     * 4. Melihat detail satu tiket beserta balasan (admin_reply) dari Superadmin
     */
    public function show($id)
    {
        // Fitur keamanan penting: Kunci query dengan user_id milik user yang sedang login.
        // Ini mencegah Owner jahil mengganti ID di URL untuk mengintip tiket milik Owner lain.
        $ticket = SupportTicket::where('user_id', auth()->id())->findOrFail($id);

        return view('support.show', compact('ticket'));
    }
}