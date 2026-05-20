<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use App\Models\Expense;
use App\Models\Customer;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        $sales = Sale::with('product')
            ->where('user_id', $userId)
            ->latest()
            ->get();

        $products = Product::where('user_id', $userId)
            ->latest()
            ->get();

        $expenses = Expense::where('user_id', $userId)
            ->latest()
            ->get();

        $customers = Customer::where('user_id', $userId)
            ->latest()
            ->get();

        $currentMonthStart = now()->startOfMonth();
        $currentMonthEnd = now()->endOfMonth();

        $previousMonthStart = now()->subMonth()->startOfMonth();
        $previousMonthEnd = now()->subMonth()->endOfMonth();

        $monthlySales = $sales->filter(function ($sale) use ($currentMonthStart, $currentMonthEnd) {
            $saleDate = $sale->sale_date
                ? Carbon::parse($sale->sale_date)
                : $sale->created_at;

            return $saleDate->between($currentMonthStart, $currentMonthEnd);
        });

        $monthlyExpenses = $expenses->filter(function ($expense) use ($currentMonthStart, $currentMonthEnd) {
            $expenseDate = $expense->expense_date
                ? Carbon::parse($expense->expense_date)
                : $expense->created_at;

            return $expenseDate->between($currentMonthStart, $currentMonthEnd);
        });

        $previousMonthSales = $sales->filter(function ($sale) use ($previousMonthStart, $previousMonthEnd) {
            $saleDate = $sale->sale_date
                ? Carbon::parse($sale->sale_date)
                : $sale->created_at;

            return $saleDate->between($previousMonthStart, $previousMonthEnd);
        });

        $previousMonthExpenses = $expenses->filter(function ($expense) use ($previousMonthStart, $previousMonthEnd) {
            $expenseDate = $expense->expense_date
                ? Carbon::parse($expense->expense_date)
                : $expense->created_at;

            return $expenseDate->between($previousMonthStart, $previousMonthEnd);
        });

        $monthlyRevenue = $monthlySales->sum('gross_total');
        $monthlyExpenseTotal = $monthlyExpenses->sum('amount');
        $monthlyPlatformFees = $monthlySales->sum('platform_fee');

        $monthlyCOGS = $monthlySales->sum(function ($sale) {
            return ($sale->product->cost_price ?? 0) * $sale->quantity;
        });

        $estimatedProfit = $monthlyRevenue - $monthlyCOGS - $monthlyPlatformFees - $monthlyExpenseTotal;
        $totalTransactions = $monthlySales->count();

        $previousRevenue = $previousMonthSales->sum('gross_total');
        $previousExpenseTotal = $previousMonthExpenses->sum('amount');
        $previousPlatformFees = $previousMonthSales->sum('platform_fee');

        $previousCOGS = $previousMonthSales->sum(function ($sale) {
            return ($sale->product->cost_price ?? 0) * $sale->quantity;
        });

        $previousProfit = $previousRevenue - $previousCOGS - $previousPlatformFees - $previousExpenseTotal;
        $previousTransactions = $previousMonthSales->count();

        $revenueTrend = $this->calculateTrend($monthlyRevenue, $previousRevenue);
        $expenseTrend = $this->calculateTrend($monthlyExpenseTotal, $previousExpenseTotal);
        $profitTrend = $this->calculateTrend($estimatedProfit, $previousProfit);
        $transactionTrend = $this->calculateTrend($totalTransactions, $previousTransactions);

        $lowStockProducts = $products->filter(function ($product) {
            return $product->stock <= $product->low_stock_limit;
        });

        $recentSales = $sales->take(5);

        $channelSummary = $monthlySales
            ->groupBy('channel')
            ->map(function ($items) {
                return [
                    'total' => $items->sum('gross_total'),
                    'count' => $items->count(),
                ];
            })
            ->sortByDesc('total');

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

        return view('dashboard', compact(
            'monthlyRevenue',
            'monthlyExpenseTotal',
            'monthlyPlatformFees',
            'monthlyCOGS',
            'estimatedProfit',
            'totalTransactions',
            'revenueTrend',
            'expenseTrend',
            'profitTrend',
            'transactionTrend',
            'lowStockProducts',
            'recentSales',
            'channelSummary',
            'sevenDaysSales',
            'maxDailySales',
            'products',
            'sales',
            'expenses',
            'customers'
        ));
    }

    private function calculateTrend(int|float $current, int|float $previous): array
    {
        if ($previous == 0 && $current == 0) {
            return [
                'status' => 'flat',
                'percent' => 0,
                'text' => 'Stabil dari bulan lalu',
            ];
        }

        if ($previous == 0 && $current > 0) {
            return [
                'status' => 'up',
                'percent' => 100,
                'text' => '100% dari bulan lalu',
            ];
        }

        $percent = round((($current - $previous) / max(abs($previous), 1)) * 100);

        if ($percent > 0) {
            return [
                'status' => 'up',
                'percent' => $percent,
                'text' => "{$percent}% dari bulan lalu",
            ];
        }

        if ($percent < 0) {
            return [
                'status' => 'down',
                'percent' => abs($percent),
                'text' => abs($percent) . "% dari bulan lalu",
            ];
        }

        return [
            'status' => 'flat',
            'percent' => 0,
            'text' => 'Stabil dari bulan lalu',
        ];
    }
}