<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SupportTicket;
use Illuminate\Http\Request;

class SuperadminSupportController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $status = $request->query('status');
        $category = $request->query('category');
        $priority = $request->query('priority');

        $totalTickets = SupportTicket::count();
        $openTickets = SupportTicket::where('status', 'open')->count();
        $inProgressTickets = SupportTicket::where('status', 'in_progress')->count();
        $resolvedTickets = SupportTicket::where('status', 'resolved')->count();

        $tickets = SupportTicket::with('user')
            ->when($search, function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('subject', 'like', "%{$search}%")
                        ->orWhere('category', 'like', "%{$search}%")
                        ->orWhere('message', 'like', "%{$search}%")
                        ->orWhereHas('user', function ($userQuery) use ($search) {
                            $userQuery->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%")
                                ->orWhere('business_name', 'like', "%{$search}%");
                        });
                });
            })
            ->when($status, function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->when($category, function ($query) use ($category) {
                $query->where('category', $category);
            })
            ->when($priority, function ($query) use ($priority) {
                $query->where('priority', $priority);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $categories = SupportTicket::query()
            ->select('category')
            ->whereNotNull('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        return view('admin.support.index', compact(
            'tickets',
            'totalTickets',
            'openTickets',
            'inProgressTickets',
            'resolvedTickets',
            'categories',
            'search',
            'status',
            'category',
            'priority'
        ));
    }

    public function show(SupportTicket $supportTicket)
    {
        $supportTicket->load('user');

        return view('admin.support.show', compact('supportTicket'));
    }

    public function update(Request $request, SupportTicket $supportTicket)
    {
        $data = $request->validate([
            'status' => ['required', 'in:open,in_progress,resolved,closed'],
            'priority' => ['required', 'in:low,normal,high,urgent'],
            'admin_reply' => ['nullable', 'string'],
        ], [
            'status.required' => 'Status wajib dipilih.',
            'priority.required' => 'Prioritas wajib dipilih.',
        ]);

        $supportTicket->update([
            'status' => $data['status'],
            'priority' => $data['priority'],
            'admin_reply' => $data['admin_reply'] ?? null,
            'resolved_at' => $data['status'] === 'resolved' ? now() : null,
        ]);

        return redirect()
            ->route('admin.support.show', $supportTicket)
            ->with('success', 'Laporan kendala berhasil diperbarui.');
    }
}