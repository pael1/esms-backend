<?php

namespace App\Repository;

use Carbon\Carbon;
use App\Models\User;
use App\Models\StallOP;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Interface\Repository\DashboardRepositoryInterface;

class DashboardRepository implements DashboardRepositoryInterface
{
    public function getTotalRevenue(object $payload)
    {
        $currentDate = Carbon::now();
        $currentYear = $currentDate->year;

        // Overall total (all-time)
        $overallTotal = StallOP::sum('amountBasic');

        // Current month totals
        $currentMonth = $currentDate->month;
        $monthlyTotalCollected = StallOP::whereMonth('opDate', $currentMonth)
            ->whereYear('opDate', $currentYear)
            ->whereNotNull('ORNum')
            ->sum('amountBasic');

        $monthlyTotalUnCollected = StallOP::whereMonth('opDate', $currentMonth)
            ->whereYear('opDate', $currentYear)
            ->whereNull('ORNum')
            ->sum('amountBasic');

        // overall total for current month
        $total = $monthlyTotalCollected + $monthlyTotalUnCollected;

        // Get total amountBasic per month (for current year)
        $monthlyTotals = StallOP::select(
                DB::raw('MONTH(opDate) as month'),
                DB::raw('SUM(amountBasic) as total')
            )
            ->whereYear('opDate', $currentYear)
            ->whereNotNull('ORNum')
            ->groupBy(DB::raw('MONTH(opDate)'))
            ->orderBy(DB::raw('MONTH(opDate)'))
            ->pluck('total', 'month')
            ->toArray();

        // Prepare chart data (default 0 for months with no data)
        $categories = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        $seriesData = [];

        foreach (range(1, 12) as $month) {
            $seriesData[] = isset($monthlyTotals[$month]) ? (float)$monthlyTotals[$month] : 0;
        }

        return response()->json([
            'overall_total' => number_format($overallTotal, 2),
            'current_month' => $currentDate->format('F Y'),
            'monthly_total_collected' => number_format($monthlyTotalCollected, 2),
            'monthly_total_uncollected' => number_format($monthlyTotalUnCollected, 2),
            'overall_total_m' => number_format($total, 2),
            'categories' => $categories,
            'series' => [
                [
                    'name' => 'Total Revenue',
                    'data' => $seriesData,
                ],
            ],
        ]);
    }
}
