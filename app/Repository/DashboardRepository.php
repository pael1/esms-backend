<?php

namespace App\Repository;

use Carbon\Carbon;
use App\Models\User;
use App\Models\StallOP;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Interface\Repository\DashboardRepositoryInterface;
use App\Models\OfficeCode;

class DashboardRepository implements DashboardRepositoryInterface
{
    public function getTotalRevenue(object $payload)
    {
        $currentDate = Carbon::now();
        $currentYear = $currentDate->year;

        // Overall total (all-time)
        $overallTotal = StallOP::sum('amountBasic');

        // Current month totals
        //collected total
        $currentMonth = $currentDate->month;
        $collected = StallOP::whereMonth('opDate', $currentMonth)
            ->whereYear('opDate', $currentYear)
            ->whereNotNull('ORNum');

        if ($payload->office != '00') {
            $collected->whereRaw('LEFT(stallNo, 2) = ?', [$payload->office]);
        }
        $monthlyTotalCollected = $collected->sum('amountBasic');
        //end of collected total

        //uncollected
        $uncollected = StallOP::whereMonth('opDate', $currentMonth)
            ->whereYear('opDate', $currentYear)
            ->whereNull('ORNum');

        if ($payload->office != '00') {
            $uncollected->whereRaw('LEFT(stallNo, 2) = ?', [$payload->office]);
        }
        $monthlyTotalUnCollected = $uncollected->sum('amountBasic');
        //end uncollected

        // overall total for current month
        $total = $monthlyTotalCollected + $monthlyTotalUnCollected;

        // Get total amountBasic per month (for current year)
        $monthlyTotalsQuery = StallOP::select(
                DB::raw('MONTH(opDate) as month'),
                DB::raw('SUM(amountBasic) as total')
            )
            ->whereYear('opDate', $currentYear)
            ->whereNotNull('ORNum');

        // Apply the condition only if office is not '00'
        if ($payload->office != '00') {
            $monthlyTotalsQuery->whereRaw('LEFT(stallNo, 2) = ?', [$payload->office]);
        }

        $monthlyTotals = $monthlyTotalsQuery
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
            //over all total for the current month
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
