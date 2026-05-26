<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        $userId = auth()->id();

        $startDate = $request->query('start_date')
            ? Carbon::parse($request->query('start_date'))->startOfDay()
            : now()->startOfMonth();

        $endDate = $request->query('end_date')
            ? Carbon::parse($request->query('end_date'))->endOfDay()
            : now()->endOfMonth();

        $selectedPeriod = [
            'start_date' => $startDate->toDateString(),
            'end_date' => $endDate->toDateString(),
        ];

        $allExpenses = Expense::where('user_id', $userId)
            ->latest()
            ->get();

        $expenses = $allExpenses->filter(function ($expense) use ($startDate, $endDate) {
            $expenseDate = $expense->expense_date
                ? Carbon::parse($expense->expense_date)
                : $expense->created_at;

            return $expenseDate->between($startDate, $endDate);
        });

        $monthExpenses = $expenses->sum('amount');

        $rawMaterialExpenses = $expenses
            ->where('category', 'Bahan Baku')
            ->sum('amount');

        $platformExpenses = $expenses
            ->where('category', 'Platform')
            ->sum('amount');

        $daysCount = max($startDate->diffInDays($endDate) + 1, 1);

        $dailyAverage = round($monthExpenses / $daysCount);

        return view('expenses', compact(
            'expenses',
            'monthExpenses',
            'rawMaterialExpenses',
            'platformExpenses',
            'dailyAverage',
            'selectedPeriod'
        ));
    }

    public function store(Request $request)
    {
        $request->merge([
            'amount' => preg_replace('/\D/', '', (string) $request->amount),
        ]);

        $data = $request->validate([
            'category' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'regex:/^\d+$/', 'digits_between:1,12'],
            'payment_method' => ['nullable', 'string', 'max:255'],
            'note' => ['nullable', 'string'],
            'expense_date' => ['nullable', 'date'],
        ], [
            'category.required' => 'Kategori pengeluaran wajib diisi.',
            'amount.required' => 'Nominal pengeluaran wajib diisi.',
            'amount.regex' => 'Nominal pengeluaran harus berupa angka.',
            'amount.digits_between' => 'Nilai terlalu banyak atau melampaui batas maksimal.',
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
            'amount' => preg_replace('/\D/', '', (string) $request->amount),
        ]);

        $data = $request->validate([
            'category' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'regex:/^\d+$/', 'digits_between:1,12'],
            'payment_method' => ['nullable', 'string', 'max:255'],
            'note' => ['nullable', 'string'],
            'expense_date' => ['nullable', 'date'],
        ], [
            'category.required' => 'Kategori pengeluaran wajib diisi.',
            'amount.required' => 'Nominal pengeluaran wajib diisi.',
            'amount.regex' => 'Nominal pengeluaran harus berupa angka.',
            'amount.digits_between' => 'Nilai terlalu banyak atau melampaui batas maksimal.',
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