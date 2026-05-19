<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;

class ReportController extends Controller
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

        $sales = Sale::with('product')
            ->where('user_id', $userId)
            ->latest()
            ->get();

        $expenses = Expense::where('user_id', $userId)
            ->latest()
            ->get();

        $monthlySales = $sales->filter(function ($sale) use ($startDate, $endDate) {
            $saleDate = $sale->sale_date
                ? Carbon::parse($sale->sale_date)
                : $sale->created_at;

            return $saleDate->between($startDate, $endDate);
        });

        $monthlyExpenses = $expenses->filter(function ($expense) use ($startDate, $endDate) {
            $expenseDate = $expense->expense_date
                ? Carbon::parse($expense->expense_date)
                : $expense->created_at;

            return $expenseDate->between($startDate, $endDate);
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
            'expenseBreakdown',
            'selectedPeriod'
        ));
    }

    public function generateAiInsight(Request $request)
    {
        $userId = auth()->id();

        $startDate = $request->input('start_date')
            ? Carbon::parse($request->input('start_date'))->startOfDay()
            : now()->startOfMonth();

        $endDate = $request->input('end_date')
            ? Carbon::parse($request->input('end_date'))->endOfDay()
            : now()->endOfMonth();

        $selectedPeriod = [
            'start_date' => $startDate->toDateString(),
            'end_date' => $endDate->toDateString(),
        ];

        $sales = Sale::with('product')
            ->where('user_id', $userId)
            ->latest()
            ->get();

        $expenses = Expense::where('user_id', $userId)
            ->latest()
            ->get();

        $monthlySales = $sales->filter(function ($sale) use ($startDate, $endDate) {
            $saleDate = $sale->sale_date
                ? Carbon::parse($sale->sale_date)
                : $sale->created_at;

            return $saleDate->between($startDate, $endDate);
        });

        $monthlyExpenses = $expenses->filter(function ($expense) use ($startDate, $endDate) {
            $expenseDate = $expense->expense_date
                ? Carbon::parse($expense->expense_date)
                : $expense->created_at;

            return $expenseDate->between($startDate, $endDate);
        });

        $grossRevenue = $monthlySales->sum('gross_total');
        $totalExpenses = $monthlyExpenses->sum('amount');
        $platformFees = $monthlySales->sum('platform_fee');
        $estimatedProfit = $grossRevenue - $totalExpenses;

        $profitMargin = $grossRevenue > 0
            ? round(($estimatedProfit / $grossRevenue) * 100)
            : 0;

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

        $channelText = $channelPerformance->count() > 0
            ? $channelPerformance->map(function ($item, $channel) {
                return "- {$channel}: Rp" . number_format($item['total'], 0, ',', '.') . " ({$item['count']} transaksi)";
            })->implode("\n")
            : "- Belum ada data channel pada periode ini.";

        $topProductText = $topProducts->count() > 0
            ? $topProducts->map(function ($item) {
                return "- {$item['name']}: {$item['sold']} terjual, omzet Rp" . number_format($item['revenue'], 0, ',', '.');
            })->implode("\n")
            : "- Belum ada produk terjual pada periode ini.";

        $expenseText = $expenseBreakdown->count() > 0
            ? $expenseBreakdown->map(function ($amount, $category) {
                return "- {$category}: Rp" . number_format($amount, 0, ',', '.');
            })->implode("\n")
            : "- Belum ada pengeluaran pada periode ini.";

        $periodText = Carbon::parse($selectedPeriod['start_date'])->format('d M Y')
            . " sampai "
            . Carbon::parse($selectedPeriod['end_date'])->format('d M Y');

        $prompt = "
Kamu adalah analis bisnis UMKM untuk aplikasi DagangFlow.

Buat ringkasan insight penjualan dan keuangan dalam Bahasa Indonesia yang singkat, jelas, dan praktis.

Data periode {$periodText}:
- Omzet kotor: Rp" . number_format($grossRevenue, 0, ',', '.') . "
- Total pengeluaran: Rp" . number_format($totalExpenses, 0, ',', '.') . "
- Biaya platform: Rp" . number_format($platformFees, 0, ',', '.') . "
- Estimasi laba: Rp" . number_format($estimatedProfit, 0, ',', '.') . "
- Margin estimasi: {$profitMargin}%

Performa channel:
{$channelText}

Produk terlaris:
{$topProductText}

Pengeluaran terbesar:
{$expenseText}

Format jawaban:
1. Ringkasan kondisi bisnis periode ini
2. Masalah utama yang perlu diperhatikan
3. Rekomendasi aksi minggu ini
4. Saran channel/produk yang perlu difokuskan

Gunakan bahasa yang mudah dipahami UMKM.
Jangan terlalu panjang.
Maksimal 5 paragraf pendek.
Gunakan format markdown seperlunya.
";

        $apiKey = config('services.gemini.api_key');
        $model = config('services.gemini.model', 'gemini-2.5-flash-lite');

        if (!$apiKey) {
            $fallbackInsight = $this->buildFallbackInsight(
                $grossRevenue,
                $totalExpenses,
                $platformFees,
                $estimatedProfit,
                $profitMargin,
                $channelPerformance,
                $topProducts,
                $expenseBreakdown,
                $selectedPeriod['start_date'],
                $selectedPeriod['end_date']
            );

            return back()
                ->with('aiInsight', $fallbackInsight)
                ->with('aiNotice', 'GEMINI_API_KEY belum diatur. DagangFlow menampilkan insight otomatis berdasarkan data laporan.');
        }

        try {
            $response = Http::timeout(60)->post(
                "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}",
                [
                    'contents' => [
                        [
                            'role' => 'user',
                            'parts' => [
                                [
                                    'text' => $prompt,
                                ],
                            ],
                        ],
                    ],
                    'generationConfig' => [
                        'temperature' => 0.6,
                        'maxOutputTokens' => 700,
                    ],
                ]
            );

            if (!$response->successful()) {
                $fallbackInsight = $this->buildFallbackInsight(
                    $grossRevenue,
                    $totalExpenses,
                    $platformFees,
                    $estimatedProfit,
                    $profitMargin,
                    $channelPerformance,
                    $topProducts,
                    $expenseBreakdown,
                    $selectedPeriod['start_date'],
                    $selectedPeriod['end_date']
                );

                return back()
                    ->with('aiInsight', $fallbackInsight)
                    ->with('aiNotice', 'Gemini sedang sibuk atau tidak tersedia. DagangFlow menampilkan insight otomatis berdasarkan data laporan.');
            }

            $aiInsight = data_get($response->json(), 'candidates.0.content.parts.0.text');

            if (!$aiInsight) {
                $fallbackInsight = $this->buildFallbackInsight(
                    $grossRevenue,
                    $totalExpenses,
                    $platformFees,
                    $estimatedProfit,
                    $profitMargin,
                    $channelPerformance,
                    $topProducts,
                    $expenseBreakdown,
                    $selectedPeriod['start_date'],
                    $selectedPeriod['end_date']
                );

                return back()
                    ->with('aiInsight', $fallbackInsight)
                    ->with('aiNotice', 'Gemini tidak mengembalikan hasil. DagangFlow menampilkan insight otomatis berdasarkan data laporan.');
            }

            return back()->with('aiInsight', $aiInsight);

        } catch (\Exception $e) {
            $fallbackInsight = $this->buildFallbackInsight(
                $grossRevenue,
                $totalExpenses,
                $platformFees,
                $estimatedProfit,
                $profitMargin,
                $channelPerformance,
                $topProducts,
                $expenseBreakdown,
                $selectedPeriod['start_date'],
                $selectedPeriod['end_date']
            );

            return back()
                ->with('aiInsight', $fallbackInsight)
                ->with('aiNotice', 'Gemini tidak dapat dihubungi. DagangFlow menampilkan insight otomatis berdasarkan data laporan.');
        }
    }

    private function buildFallbackInsight(
        int $grossRevenue,
        int $totalExpenses,
        int $platformFees,
        int $estimatedProfit,
        int $profitMargin,
        $channelPerformance,
        $topProducts,
        $expenseBreakdown,
        string $startDate,
        string $endDate
    ): string {
        $periodText = Carbon::parse($startDate)->format('d M Y') . ' sampai ' . Carbon::parse($endDate)->format('d M Y');

        if ($grossRevenue <= 0 && $totalExpenses <= 0) {
            return "1. Ringkasan kondisi bisnis periode {$periodText}\n"
                . "Belum ada data penjualan maupun pengeluaran pada periode ini. Artinya laporan belum bisa menunjukkan performa bisnis yang aktif.\n\n"
                . "2. Masalah utama yang perlu diperhatikan\n"
                . "Data masih kosong, jadi DagangFlow belum bisa membaca omzet, laba, produk terlaris, atau channel terbaik.\n\n"
                . "3. Rekomendasi aksi minggu ini\n"
                . "Mulai catat transaksi penjualan dan pengeluaran secara rutin agar laporan bisa membantu membaca kondisi bisnis.\n\n"
                . "4. Saran channel/produk yang perlu difokuskan\n"
                . "Belum ada channel atau produk yang bisa direkomendasikan. Setelah ada transaksi, fokus bisa diarahkan ke produk terlaris dan channel dengan omzet paling besar.";
        }

        if ($grossRevenue <= 0 && $totalExpenses > 0) {
            $topExpenseCategory = $expenseBreakdown->keys()->first() ?? 'pengeluaran';
            $topExpenseAmount = $expenseBreakdown->first() ?? 0;

            return "1. Ringkasan kondisi bisnis periode {$periodText}\n"
                . "Pada periode ini belum ada penjualan yang tercatat, tetapi sudah ada pengeluaran sebesar Rp" . number_format($totalExpenses, 0, ',', '.') . ".\n\n"
                . "2. Masalah utama yang perlu diperhatikan\n"
                . "Pengeluaran sudah berjalan tanpa pemasukan, sehingga estimasi laba masih negatif sebesar Rp" . number_format(abs($estimatedProfit), 0, ',', '.') . ". Pengeluaran terbesar ada pada kategori {$topExpenseCategory} sebesar Rp" . number_format($topExpenseAmount, 0, ',', '.') . ".\n\n"
                . "3. Rekomendasi aksi minggu ini\n"
                . "Prioritaskan aktivitas penjualan terlebih dahulu sebelum menambah biaya baru. Cek kembali apakah pengeluaran pada periode ini benar-benar mendukung penjualan.\n\n"
                . "4. Saran channel/produk yang perlu difokuskan\n"
                . "Belum ada channel atau produk yang terbaca menghasilkan omzet. Mulai catat penjualan dari channel utama seperti WhatsApp, Shopee, atau offline.";
        }

        $topChannel = $channelPerformance->keys()->first();
        $topChannelTotal = $topChannel ? ($channelPerformance->first()['total'] ?? 0) : 0;

        $topProduct = $topProducts->first();
        $topProductName = $topProduct['name'] ?? 'belum ada produk dominan';
        $topProductSold = $topProduct['sold'] ?? 0;

        $topExpenseCategory = $expenseBreakdown->keys()->first();
        $topExpenseAmount = $expenseBreakdown->first() ?? 0;

        $profitStatus = $estimatedProfit >= 0 ? 'masih positif' : 'masih negatif';

        $recommendation = $profitMargin >= 30
            ? 'Margin masih cukup sehat. Pertahankan produk dan channel yang paling menghasilkan, sambil tetap mengontrol biaya.'
            : 'Margin masih perlu dipantau. Cek kembali harga jual, modal produk, biaya platform, dan pengeluaran terbesar.';

        return "1. Ringkasan kondisi bisnis periode {$periodText}\n"
            . "Omzet kotor tercatat Rp" . number_format($grossRevenue, 0, ',', '.') . " dengan total pengeluaran Rp" . number_format($totalExpenses, 0, ',', '.') . ". Estimasi laba {$profitStatus} sebesar Rp" . number_format($estimatedProfit, 0, ',', '.') . " dengan margin sekitar {$profitMargin}%.\n\n"
            . "2. Masalah utama yang perlu diperhatikan\n"
            . ($topExpenseCategory
                ? "Pengeluaran terbesar berasal dari kategori {$topExpenseCategory} sebesar Rp" . number_format($topExpenseAmount, 0, ',', '.') . ". Biaya platform tercatat Rp" . number_format($platformFees, 0, ',', '.') . ".\n\n"
                : "Belum ada pengeluaran besar yang terbaca pada periode ini. Tetap pantau biaya agar laba tidak turun.\n\n")
            . "3. Rekomendasi aksi minggu ini\n"
            . $recommendation . "\n\n"
            . "4. Saran channel/produk yang perlu difokuskan\n"
            . ($topChannel
                ? "Channel paling kuat adalah {$topChannel} dengan omzet Rp" . number_format($topChannelTotal, 0, ',', '.') . ". Produk yang perlu diperhatikan adalah {$topProductName} dengan {$topProductSold} terjual."
                : "Belum ada channel dominan yang terbaca. Tambahkan lebih banyak transaksi agar rekomendasi channel dan produk lebih akurat.");
    }
}