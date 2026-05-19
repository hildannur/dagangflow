<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function index()
    {
        $expenses = Expense::where('user_id', auth()->id())
            ->latest()
            ->get();

        $monthExpenses = $expenses
            ->filter(fn ($expense) => $expense->created_at->isSameMonth(now()))
            ->sum('amount');

        $rawMaterialExpenses = $expenses
            ->filter(fn ($expense) => $expense->created_at->isSameMonth(now()))
            ->where('category', 'Bahan Baku')
            ->sum('amount');

        $platformExpenses = $expenses
            ->filter(fn ($expense) => $expense->created_at->isSameMonth(now()))
            ->where('category', 'Platform')
            ->sum('amount');

        $dailyAverage = now()->day > 0
            ? round($monthExpenses / now()->day)
            : 0;

        return view('expenses', compact(
            'expenses',
            'monthExpenses',
            'rawMaterialExpenses',
            'platformExpenses',
            'dailyAverage'
        ));
    }

    public function store(Request $request)
    {
        // Bersihkan format ribuan sebelum validasi.
        // Contoh: 150.000 menjadi 150000
        $request->merge([
            'amount' => preg_replace('/\D/', '', $request->amount),
        ]);

        $data = $request->validate([
            'category' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'integer', 'min:0'],
            'payment_method' => ['nullable', 'string', 'max:255'],
            'note' => ['nullable', 'string'],
            'expense_date' => ['nullable', 'date'],
        ]);

        Expense::create([
            'user_id' => auth()->id(),
            'category' => $data['category'],
            'amount' => $data['amount'],
            'payment_method' => $data['payment_method'] ?? null,
            'note' => $data['note'] ?? null,
            'expense_date' => $data['expense_date'] ?? now()->toDateString(),
        ]);

        return redirect('/expenses')->with('success', 'Pengeluaran berhasil dicatat.');
    }
    
    public function update(Request $request, Expense $expense)
    {
        abort_if($expense->user_id !== auth()->id(), 403);
    
        $request->merge([
            'amount' => preg_replace('/\D/', '', $request->amount),
        ]);
    
        $data = $request->validate([
            'category' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'integer', 'min:0'],
            'payment_method' => ['nullable', 'string', 'max:255'],
            'note' => ['nullable', 'string'],
            'expense_date' => ['nullable', 'date'],
        ]);
    
        $expense->update([
            'category' => $data['category'],
            'amount' => $data['amount'],
            'payment_method' => $data['payment_method'] ?? null,
            'note' => $data['note'] ?? null,
            'expense_date' => $data['expense_date'] ?? now()->toDateString(),
        ]);
    
        return redirect('/expenses')->with('success', 'Pengeluaran berhasil diperbarui.');
    }

    public function destroy(Expense $expense)
    {
        abort_if($expense->user_id !== auth()->id(), 403);

        $expense->delete();

        return redirect('/expenses')->with('success', 'Pengeluaran berhasil dihapus.');
    }
}