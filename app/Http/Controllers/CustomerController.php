<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $userId = auth()->id();

        $allCustomers = Customer::where('user_id', $userId)
            ->latest()
            ->get();

        $customerQuery = Customer::where('user_id', $userId);

        if ($request->filled('search')) {
            $search = $request->query('search');

            $customerQuery->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('note', 'like', "%{$search}%");
            });
        }

        if ($request->filled('channel')) {
            $customerQuery->where('channel', $request->query('channel'));
        }

        if ($request->filled('type')) {
            if ($request->query('type') === 'repeat') {
                $customerQuery->where('total_orders', '>', 1);
            }

            if ($request->query('type') === 'new') {
                $customerQuery->whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year);
            }

            if ($request->query('type') === 'has_note') {
                $customerQuery->whereNotNull('note')
                    ->where('note', '!=', '');
            }
        }

        if ($request->query('sort') === 'orders_desc') {
            $customerQuery->orderByDesc('total_orders');
        } elseif ($request->query('sort') === 'spent_desc') {
            $customerQuery->orderByDesc('total_spent');
        } elseif ($request->query('sort') === 'name_asc') {
            $customerQuery->orderBy('name');
        } else {
            $customerQuery->latest();
        }

        $customers = $customerQuery->get();

        $totalCustomers = $allCustomers->count();

        $repeatCustomers = $allCustomers
            ->where('total_orders', '>', 1)
            ->count();

        $newCustomers = $allCustomers
            ->filter(fn ($customer) => $customer->created_at->isSameMonth(now()))
            ->count();

        $topChannel = $allCustomers
            ->filter(fn ($customer) => !empty($customer->channel))
            ->groupBy('channel')
            ->map(fn ($items) => $items->count())
            ->sortDesc()
            ->keys()
            ->first();

        $availableChannels = $allCustomers
            ->pluck('channel')
            ->filter()
            ->unique()
            ->sort()
            ->values();

        $activeFilters = [
            'search' => $request->query('search'),
            'channel' => $request->query('channel'),
            'type' => $request->query('type'),
            'sort' => $request->query('sort', 'latest'),
        ];

        return view('customers', compact(
            'customers',
            'totalCustomers',
            'repeatCustomers',
            'newCustomers',
            'topChannel',
            'availableChannels',
            'activeFilters'
        ));
    }

    public function store(Request $request)
    {
        $request->merge([
            'total_orders' => preg_replace('/\D/', '', $request->total_orders),
            'total_spent' => preg_replace('/\D/', '', $request->total_spent),
        ]);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:255'],
            'channel' => ['nullable', 'string', 'max:255'],
            'total_orders' => ['nullable', 'integer', 'min:0'],
            'total_spent' => ['nullable', 'integer', 'min:0'],
            'last_order_date' => ['nullable', 'date'],
            'note' => ['nullable', 'string'],
        ]);

        Customer::create([
            'user_id' => auth()->id(),
            'name' => $data['name'],
            'phone' => $data['phone'] ?? null,
            'channel' => $data['channel'] ?? null,
            'total_orders' => $data['total_orders'] ?? 0,
            'total_spent' => $data['total_spent'] ?? 0,
            'last_order_date' => $data['last_order_date'] ?? null,
            'note' => $data['note'] ?? null,
        ]);

        return redirect('/customers')->with('success', 'Customer berhasil ditambahkan.');
    }

    public function update(Request $request, Customer $customer)
    {
        abort_if($customer->user_id !== auth()->id(), 403);

        $request->merge([
            'total_orders' => preg_replace('/\D/', '', $request->total_orders),
            'total_spent' => preg_replace('/\D/', '', $request->total_spent),
        ]);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:255'],
            'channel' => ['nullable', 'string', 'max:255'],
            'total_orders' => ['nullable', 'integer', 'min:0'],
            'total_spent' => ['nullable', 'integer', 'min:0'],
            'last_order_date' => ['nullable', 'date'],
            'note' => ['nullable', 'string'],
        ]);

        $customer->update([
            'name' => $data['name'],
            'phone' => $data['phone'] ?? null,
            'channel' => $data['channel'] ?? null,
            'total_orders' => $data['total_orders'] ?? 0,
            'total_spent' => $data['total_spent'] ?? 0,
            'last_order_date' => $data['last_order_date'] ?? null,
            'note' => $data['note'] ?? null,
        ]);

        return redirect('/customers')->with('success', 'Customer berhasil diperbarui.');
    }

    public function destroy(Customer $customer)
    {
        abort_if($customer->user_id !== auth()->id(), 403);

        $customer->delete();

        return redirect('/customers')->with('success', 'Customer berhasil dihapus.');
    }
}