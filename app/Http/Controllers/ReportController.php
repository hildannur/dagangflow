<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Expense;
use Illuminate\Support\Carbon;

class ReportController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        $sales = Sale::with('product')
            ->where('user_id', $userId)
            ->latest()
            ->get();

        $expenses = Expense::where('user_id', $userId)
            ->latest()
            ->get();

        $monthlySales = $sales->filter(function ($sale) {
            $saleDate = $sale->sale_date
                ? Carbon::parse($sale->sale_date)
                : $sale->created_at;

            return $saleDate->isSameMonth(now());
        });

        $monthlyExpenses = $expenses->filter(function ($expense) {
            $expenseDate = $expense->expense_date
                ? Carbon::parse($expense->expense_date)
                : $expense->created_at;

            return $expenseDate->isSameMonth(now());
        });

        $grossRevenue = $monthlySales->sum('gross_total');
        $totalExpenses = $monthlyExpenses->sum('amount');
        $platformFees = $monthlySales->sum('platform_fee');
        $estimatedProfit = $grossRevenue - $totalExpenses;

        $profitMargin = $grossRevenue > 0
            ? round(($estimatedProfit / $grossRevenue) * 100)
            : 0;

        $sevenDaysSales = collect();

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);

            $total = $sales
                ->filter(function ($sale) use ($date) {
                    $saleDate = $sale->sale_date
                        ? Carbon::parse($sale->sale_date)
                        : $sale->created_at;

                    return $saleDate->isSameDay($date);
                })
                ->sum('gross_total');

            $sevenDaysSales->push([
                'day' => $date->translatedFormat('D'),
                'date' => $date->format('Y-m-d'),
                'total' => $total,
            ]);
        }

        $maxDailySales = max($sevenDaysSales->max('total'), 1);

        $channelPerformance = $monthlySales
            ->groupBy('channel')
            ->map(function ($items) {
                return [
                    'total' => $items->sum('gross_total'),
                    'count' => $items->count(),
                ];
            })
            ->sortByDesc('total');

        $topProducts = $monthlySales
            ->groupBy('product_id')
            ->map(function ($items) {
                $firstSale = $items->first();

                return [
                    'name' => $firstSale->product->name ?? 'Produk terhapus',
                    'sold' => $items->sum('quantity'),
                    'revenue' => $items->sum('gross_total'),
                ];
            })
            ->sortByDesc('sold')
            ->take(5);

        $expenseBreakdown = $monthlyExpenses
            ->groupBy('category')
            ->map(fn ($items) => $items->sum('amount'))
            ->sortDesc();

        return view('reports', compact(
            'grossRevenue',
            'totalExpenses',
            'platformFees',
            'estimatedProfit',
            'profitMargin',
            'sevenDaysSales',
            'maxDailySales',
            'channelPerformance',
            'topProducts',
            'expenseBreakdown'
        ));
    }
}