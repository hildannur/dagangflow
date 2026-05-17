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

        $monthlySales = $sales->filter(function ($sale) {
            return $sale->created_at->isSameMonth(now());
        });

        $monthlyExpenses = $expenses->filter(function ($expense) {
            return $expense->created_at->isSameMonth(now());
        });

        $monthlyRevenue = $monthlySales->sum('gross_total');
        $monthlyExpenseTotal = $monthlyExpenses->sum('amount');
        $estimatedProfit = $monthlyRevenue - $monthlyExpenseTotal;
        $totalTransactions = $monthlySales->count();

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
            'estimatedProfit',
            'totalTransactions',
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
}