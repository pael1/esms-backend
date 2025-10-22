<?php

namespace App\Repository;

use App\Interface\Repository\RentalRepositoryInterface;
use App\Models\Stallowner;
use App\Models\Stallprofile;
use App\Models\Stallrentaldet;

class RentalRepository implements RentalRepositoryInterface
{
    public function findMany(object $payload)
    {
        $query = Stallrentaldet::with(['stallProfile', 'stallOwner'])
                ->filter($payload->all())
                ->orderBy('stallDetailId', 'desc');

        return $query->paginate(10);
    }
    public function findById(string $id)
    {
        $rental = Stallrentaldet::findOrFail($id);
        return $rental;
    }
    public function create(array $payload)
    {
        // Find the Stall Owner based on the ownerId from payload
        $owner = Stallowner::where('ownerId', $payload['ownerId'])->first();

        if (!$owner) {
            return response()->json([
                'message' => 'The specified stall owner does not exist.',
                'errors' => [
                    'ownerId' => ['Invalid or missing owner ID.']
                ]
            ], 422);
        }

        // Add the foreign key for stall owner
        $payload['STALLOWNER_stallOwnerId'] = $owner->stallOwnerId;
        $payload['rentalStatus'] = "active";

        // Create the rental record
        $rental = Stallrentaldet::create($payload);

        return $rental->fresh();
    }
    public function update(string $rentalId, array $payload)
    {
        $rental = Stallrentaldet::findOrFail($rentalId);
        $rental->update($payload);
        
        return $rental->fresh();
    }
    public function delete(string $stallId)
    {
        // Implementation for deleting a stall
    }
}