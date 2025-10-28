<?php

namespace App\Repository;

use App\Models\Stallowner;
use App\Models\Stallprofile;
use App\Models\Stallrentaldet;
use Illuminate\Support\Facades\DB;
use App\Interface\Repository\RentalRepositoryInterface;

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
        return DB::transaction(function () use ($payload) {
            $owner = Stallowner::where('ownerId', $payload['ownerId'])->first();

            if (!$owner) {
                throw new \Exception('The specified stall owner does not exist.');
            }

            // Add the foreign key for stall owner
            $payload['STALLOWNER_stallOwnerId'] = $owner->stallOwnerId;
            $payload['rentalStatus'] = 'active';

            // Create the rental record
            $rental = Stallrentaldet::create($payload);

            // Update the stall status
            Stallprofile::where('stallNo', $payload['stallNo'])
                ->update(['stallStatus' => 'REN']);

            // Update the stall owner's rental status
            $owner->update(['rental_status' => 'rented']);

            return $rental->fresh();
        });
    }
    public function update(string $rentalId, array $payload)
    {
        $rental = Stallrentaldet::findOrFail($rentalId);
        $owner = Stallowner::where('ownerId', $payload['ownerId'])->first();

        // Add the foreign key for stall owner
        $payload['STALLOWNER_stallOwnerId'] = $owner->stallOwnerId;

        //change previous stall status to null if stallNo is changed
        if($rental->stallNo != $payload['stallNo']){
            Stallprofile::where('stallNo', $rental->stallNo)
                ->update(['stallStatus' => null]);
            
            //update new stall status to REN
            Stallprofile::where('stallNo', $payload['stallNo'])
                ->update(['stallStatus' => 'REN']);
        }

        if($rental->ownerId != $payload['ownerId']){
            //update previous owner rental status to null
            $previousOwner =Stallowner::where('ownerId', $rental->ownerId)->first();
            $previousOwner->update(['rental_status' => "available"]);

            //update new owner rental status to rented
            $owner->update(['rental_status' => 'rented']);
        }

        $rental->update($payload);
        
        return $rental->fresh();
    }
    public function delete(string $stallId)
    {
        // Implementation for deleting a stall
    }

    //cancel rental
    public function cancelRental(string $id)
    {
            return DB::transaction(function () use ($id) {
                // Find the rental record
                $rental = Stallrentaldet::findOrFail($id);

                // Update rental status
                $rental->update(['rentalStatus' => 'cancel']);

                // Update corresponding stall status
                Stallprofile::where('stallNo', $rental->stallNo)
                    ->update(['stallStatus' => null]);

                // Return the updated rental with fresh data
                return $rental->fresh();
            });
    }
}