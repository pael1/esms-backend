<?php

namespace App\Http\Controllers\Api;

use App\Models\Awardee;
use App\Models\Stallowner;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStallOwnerRequest;
use App\Http\Requests\UpdateStallOwnerRequest;
use App\Interface\Service\AwardeeServiceInterface;
use App\Interface\Service\StallOwnerServiceInterface;

class AwardeeController extends Controller
{
    private $awardeeService;
    private $stallOwnerService;

    public function __construct(AwardeeServiceInterface $awardeeService, StallOwnerServiceInterface $stallOwnerService)
    {
        $this->awardeeService = $awardeeService;
        $this->stallOwnerService = $stallOwnerService;
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
    public function store(StoreStallOwnerRequest $request)
    {
        // //cast as array if no $validated it was object matic
        // $validated = $request->validated();

        // $exists = Stallowner::where('firstname', $request->firstname)
        //     ->where('lastname', $request->lastname)
        //     ->where('midinit', $request->midinit)
        //     ->exists();

        // if ($exists) {
        //     return response()->json([
        //         'message' => 'The full name already exists.'
        //     ], 422);
        // }

        // return $this->awardeeService->create($validated);
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
    public function update(UpdateStallOwnerRequest $request, string $id)
    {
        // //cast as array if no $validated it was object matic
        // $validated = $request->validated();

        // $exists = Stallowner::where('firstname', $request->firstname)
        //     ->where('lastname', $request->lastname)
        //     ->where('midinit', $request->midinit)
        //     ->where('ownerId', '!=', $id) // 👈 exclude current record
        //     ->exists();

        // if ($exists) {
        //     return response()->json([
        //         'message' => 'The full name already exists.'
        //     ], 422);
        // }

        // return $this->stallOwnerService->update($validated, $id);
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
