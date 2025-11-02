<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Interface\Service\DashboardServiceInterface;

class DashboardController extends Controller
{
    private $dashboardService;

    public function __construct(DashboardServiceInterface $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    public function allTotal(Request $request)
    {
        return $this->dashboardService->getTotalRevenue($request);
    }
}
