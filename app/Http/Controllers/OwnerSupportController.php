<?php

namespace App\Http\Controllers;

use App\Models\SupportTicket;
use Illuminate\Http\Request;

class OwnerSupportController extends Controller
{
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

        return redirect('/help')->with('success', 'Kendala berhasil dilaporkan. Tim DagangFlow akan meninjau laporan kamu.');
    }
}