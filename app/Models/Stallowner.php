<?php

namespace App\Models;

use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Stallowner extends Model
{
    use HasFactory, Filterable;

    public $timestamps = false;

    protected $table = 'stallowner';

    protected $primaryKey = 'stallOwnerId';

    protected $fillable = [
        'stallOwnerId',
        'ownerId',
        'lastname',
        'firstname',
        'midinit',
        'civilStatus',
        'address',
        'spouseLastname',
        'spouseFirstname',
        'spouseMidint',
        'ownerStatus',
        'attachIdPhoto',
        'dateRegister',
        'contactnumber',
    ];

    public function stallRentalDet()
    {
        // return $this->hasOne(Stallrentaldet::class, 'ownerId', 'ownerId');
        return $this->hasOne(Stallrentaldet::class, 'ownerId', 'ownerId')
                ->where('rentalStatus', 'active');
    }

    public function children()
    {
        return $this->hasMany(StallOwnerChild::class, 'STALLOWNER_stallOwnerId', 'stallOwnerId');
    }

    public function employees()
    {
        return $this->hasMany(StallOwnerEmp::class, 'STALLOWNER_stallOwnerId', 'stallOwnerId');
    }

    public function files()
    {
        return $this->hasMany(StallOwnerFiles::class, 'ownerId', 'ownerId');
    }

    public function ledger()
    {
        return $this->hasMany(StallOwnerAccount::class, 'ownerId', 'ownerId');
    }
}
