<?php

namespace App\Http\Controllers\Api;

use App\Models\SyncOp;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\SyncOpResource;
use App\Http\Requests\StoreSyncOpRequest;
use App\Interface\Service\SyncOpServiceInterface;
use App\Interface\Repository\LedgerRepositoryInterface;

class SyncOpController extends Controller
{

    private $syncService;
    private $ledgerRepository;

    public function __construct(SyncOpServiceInterface $syncService, LedgerRepositoryInterface $ledgerRepository)
    {
        $this->syncService = $syncService;
        $this->ledgerRepository = $ledgerRepository;
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
        return $this->syncService->update($request, $id);
    }

    // Delete a SyncOp record
    public function destroy($id)
    {
        return $this->syncService->delete($id);
    }

    //paid manually update
    public function paidManually(Request $request, $id)
    {
        return $this->syncService->paidManually($request, $id);
    }
}
