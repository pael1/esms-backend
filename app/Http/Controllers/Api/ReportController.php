<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Interface\Service\ReportServiceInterface;

class ReportController extends Controller
{
    private $reportService;

    public function __construct(ReportServiceInterface $reportService)
    {
        $this->reportService = $reportService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return $this->reportService->masterlist($request);
    }

    public function masterlist_print(Request $request)
    {
        return $this->reportService->masterlist_print($request);
    }
}
