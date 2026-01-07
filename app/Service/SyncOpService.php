<?php

namespace App\Service;

use App\Models\SyncOp;
use Illuminate\Http\Response;
use App\Models\StallOwnerAccount;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\SyncOpResource;
use App\Http\Resources\StallOPResource;
use App\Http\Resources\StallOwnerAccountResource;
use App\Interface\Service\SyncOpServiceInterface;
use App\Interface\Repository\LedgerRepositoryInterface;
use App\Interface\Repository\SyncOpRepositoryInterface;

class SyncOpService implements SyncOpServiceInterface
{
    private $syncOpRepository;
    private $ledgerRepository;

    public function __construct(SyncOpRepositoryInterface $syncOpRepository, LedgerRepositoryInterface $ledgerRepository)
    {
        $this->syncOpRepository = $syncOpRepository;
        $this->ledgerRepository = $ledgerRepository;
    }

    public function findMany(object $payload)
    {
        $data = $this->syncOpRepository->findMany($payload);
        return SyncOpResource::collection($data);
    }

    public function findArrears(object $payload)
    {
        $data = $this->syncOpRepository->findArrears($payload);
        return StallOwnerAccountResource::collection($data);   
    }

    public function findManyById(string $id)
    {
        $data = $this->syncOpRepository->findManyById($id);

        return SyncOpResource::collection($data);
    }

    public function findById(string $id)
    {
        // $op = $this->opRepository->findByFieldIdFieldValue($id);

        // return ParameterResource::collection($op); // for multiple data
        // return new ParameterResource($op); //for only 1 data
    }

    public function create(object $payload)
    {
        try {
            return DB::transaction(function () use ($payload) {
                $data = $this->syncOpRepository->create($payload);
                $this->ledgerRepository->updateSync($payload, 1);
                
                return SyncOpResource::make($data);
            });

        } catch (\Throwable $e) {
            report($e);
            return response()->json([
                'message' => trans('exception.sync_create_error.message'),
            ], 500);
        }
    }

    public function update(object $payload, string $id)
    {
        $syncOp = SyncOp::findOrFail($id);
        $syncOp->update($payload->all());  // Update the record with new data
        return new SyncOpResource($syncOp);  // Return the updated SyncOp
    }

    public function delete(string $id)
    {
        $syncOp = SyncOp::findOrFail($id);
        $this->ledgerRepository->updateSync($syncOp, 0); // Update related ledger records to unsynced
        $syncOp->delete();  // Delete the record
        return SyncOpResource::make($syncOp);
    }

    public function paidManually(string $id)
    {
        $syncOp = SyncOp::findOrFail($id);
        $this->ledgerRepository->paidManually($syncOp);
        $this->syncOpRepository->updateById($syncOp->id, '3'); //3 means paid manually
        return SyncOpResource::make($syncOp);
    }
}
