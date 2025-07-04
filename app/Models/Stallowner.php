<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stallowner extends Model
{
    use HasFactory;

    protected $table = 'stallowner';

    protected $primaryKey = 'stallOwnerId';

    protected $fillable = [
        'stallOwnerId',
        'ownerId ',
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

    // public function childrens()
    // {
    //     return $this->hasMany(StallOwnerChild::class, 'STALLOWNER_stallOwnerId', 'stallOwnerId');
    // }

    // public function ledger()
    // {
    //     return $this->hasMany(StallOwnerAccount::class, 'ownerId', 'ownerId');
    // }

    // public function transactions()
    // {
    //     return $this->hasMany(StallOP::class, 'ownerId', 'ownerId');
    // }

    public function stallRentalDet()
    {
        return $this->hasOne(Stallrentaldet::class, 'ownerId', 'ownerId');
    }
}
