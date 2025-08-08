<?php

namespace App\Http\Controllers\Api;

use App\Models\SyncOp;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSyncOpRequest;
use App\Http\Resources\SyncOpResource;
use App\Interface\Service\SyncOpServiceInterface;

class SyncOpController extends Controller
{

    private $syncService;

    public function __construct(SyncOpServiceInterface $syncService)
    {
        $this->syncService = $syncService;
    }

    // Display a listing of SyncOp records
    public function index(Request $request)
    {
        return $this->syncService->findMany($request);
    }

    //get the arrears months does not sync
    public function arrearsMonth(Request $request)
    {
        return $this->syncService->findArrears($request);
    }

    public function ORNumberById($id)
    {
        return $this->syncService->findManyById($id);
    }

    // Display a specific SyncOp record by ID
    public function show($id)
    {
        $syncOp = SyncOp::findOrFail($id);
        return new SyncOpResource($syncOp);  // Return a single SyncOp as a Resource
    }

    // Store a new SyncOp record
    public function store(StoreSyncOpRequest $request)
    {
        return $this->syncService->create($request);
    }

    // Update an existing SyncOp record
    public function update(Request $request, $id)
    {
        $syncOp = SyncOp::findOrFail($id);
        $syncOp->update($request->all());  // Update the record with new data
        return new SyncOpResource($syncOp);  // Return the updated SyncOp
    }

    // Delete a SyncOp record
    public function destroy($id)
    {
        $syncOp = SyncOp::findOrFail($id);
        $syncOp->delete();  // Delete the record
        return response()->noContent();  // Return a 204 No Content response
    }
}
