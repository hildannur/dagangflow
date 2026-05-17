<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::where('user_id', auth()->id())
            ->latest()
            ->get();

        $totalCustomers = $customers->count();

        $repeatCustomers = $customers
            ->where('total_orders', '>', 1)
            ->count();

        $newCustomers = $customers
            ->filter(fn ($customer) => $customer->created_at->isSameMonth(now()))
            ->count();

        $topChannel = $customers
            ->groupBy('channel')
            ->map(fn ($items) => $items->count())
            ->sortDesc()
            ->keys()
            ->first();

        return view('customers', compact(
            'customers',
            'totalCustomers',
            'repeatCustomers',
            'newCustomers',
            'topChannel'
        ));
    }

    public function store(Request $request)
    {
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

    public function destroy(Customer $customer)
    {
        abort_if($customer->user_id !== auth()->id(), 403);

        $customer->delete();

        return redirect('/customers')->with('success', 'Customer berhasil dihapus.');
    }
}