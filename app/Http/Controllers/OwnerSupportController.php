<?php

namespace App\Http\Controllers;

use App\Models\SupportTicket;
use Illuminate\Http\Request;

class OwnerSupportController extends Controller
{
    // TAMBAHAN: Menampilkan riwayat kendala milik owner yang login
    public function index()
    {
        $tickets = SupportTicket::where('user_id', auth()->id())
            ->latest()
            ->paginate(10); // Kita batasi 10 data per halaman sesuai pagination di view

        return view('support.index', compact('tickets'));
    }

    public function create()
    {
        $user = auth()->user();
        
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

        // Tentukan prioritas otomatis berdasarkan paket
        $premiumPlans = ['Trial', 'Bulanan', 'Tahunan'];
        $autoPriority = in_array($user->plan_name, $premiumPlans) ? 'high' : 'normal';

        return view('support.create', compact(
            'categories',
            'autoPriority'
        ));
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        
        // Tentukan prioritas yang valid berdasarkan paket pengguna
        $premiumPlans = ['Trial', 'Bulanan', 'Tahunan'];
        $validPriority = in_array($user->plan_name, $premiumPlans) ? 'high' : 'normal';
        
        $data = $request->validate([
            'subject' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'min:10'],
        ], [
            'subject.required' => 'Subjek kendala wajib diisi.',
            'category.required' => 'Kategori kendala wajib dipilih.',
            'message.required' => 'Detail kendala wajib diisi.',
            'message.min' => 'Detail kendala minimal 10 karakter.',
        ]);

        SupportTicket::create([
            'user_id' => auth()->id(),
            'subject' => $data['subject'],
            'category' => $data['category'],
            'priority' => $validPriority, // Gunakan prioritas yang sudah ditentukan, bukan dari request
            'status' => 'open',
            'message' => $data['message'],
        ]);

        // Mengarahkan ke riwayat kendala setelah sukses membuat laporan
        return redirect()->route('owner.support.index')->with('success', 'Kendala berhasil dilaporkan. Tim DagangFlow akan meninjau laporan kamu.');
    }

    // TAMBAHAN: Menampilkan detail kendala dan balasan admin
    public function show($id)
    {
        // Pastikan owner hanya bisa melihat tiket miliknya sendiri demi keamanan
        $ticket = SupportTicket::where('user_id', auth()->id())->findOrFail($id);

        return view('support.show', compact('ticket'));
    }
}
