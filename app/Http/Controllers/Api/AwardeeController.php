<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Interface\Service\AwardeeServiceInterface;
use App\Models\Awardee;
use Illuminate\Http\Request;

class AwardeeController extends Controller
{
    private $awardeeService;

    public function __construct(AwardeeServiceInterface $awardeeService)
    {
        $this->awardeeService = $awardeeService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return $this->awardeeService->findManyAwardee($request);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $ownerID)
    {
        return $this->awardeeService->findAwardeeById($ownerID);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Awardee $request, string $awardee)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Awardee $awardee)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Awardee $awardee)
    {
        //
    }

    //get ledger
    // public function get_ledger(Request $request)
    // {
    //     return $this->awardeeService->findManyLedger($request);
    // }
    //get childrens
    public function get_childrens(Request $request)
    {
        return $this->awardeeService->find_many_childrens($request);
    }

    //get transactions
    public function get_transactions(Request $request)
    {
        return $this->awardeeService->find_many_transactions($request);
    }

    //get files
    public function get_files(Request $request)
    {
        return $this->awardeeService->find_many_files($request);
    }

    //get employees_data
    public function get_employees_data(Request $request)
    {
        return $this->awardeeService->find_many_employees_data($request);
    }

    public function current_billing(Request $request)
    {
        return $this->awardeeService->current_billing($request);
    }
}
