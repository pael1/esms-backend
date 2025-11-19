<?php

namespace App\Http\Controllers\Api;

use App\Models\Stallowner;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStallOwnerRequest;
use App\Http\Requests\UpdateStallOwnerRequest;
use App\Interface\Service\StallOwnerServiceInterface;

class StallOwnerController extends Controller
{
    private $stallOwnerService;

    public function __construct(StallOwnerServiceInterface $stallOwnerService)
    {
        $this->stallOwnerService = $stallOwnerService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return $this->stallOwnerService->findMany($request);
    }

    //get owner details
    public function owner(string $ownerId, string $renalId)
    {
        return $this->stallOwnerService->findOwner($ownerId, $renalId);
    }

    /**
     * get owner names
     */
    public function ownerNames(string $searchName)
    {
        return $this->stallOwnerService->findOwnerName($searchName);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreStallOwnerRequest $request)
    {
        $validated = $request->validated();
        return $this->stallOwnerService->create($validated);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStallOwnerRequest $request, string $id)
    {
        //cast as array if no $validated it was object matic
        $validated = $request->validated();
        return $this->stallOwnerService->update($validated, $id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    //get status details
    public function statusDetails(Request $request)
    {
        return $this->stallOwnerService->checkDetails($request);
    }
}
