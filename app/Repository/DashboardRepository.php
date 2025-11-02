<?php

namespace App\Repository;

use Carbon\Carbon;
use App\Models\User;
use App\Models\StallOP;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use App\Interface\Repository\DashboardRepositoryInterface;

class DashboardRepository implements DashboardRepositoryInterface
{
    public function getTotalRevenue(object $payload)
    {
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        // Overall total (all-time)
        $overallTotal = StallOP::sum('amountBasic');

        // Current month total
        $currentMonth = Carbon::now()->format('Y-m');
        $monthlyTotal = StallOP::whereMonth('opDate', $currentMonth)
            ->whereYear('opDate', $currentYear)
            ->sum('amountBasic');

        return response()->json([
            'overall_total' => $overallTotal,
            'current_month' => $currentMonth,
            'current_month_total' => $monthlyTotal,
        ]);
    }
}
