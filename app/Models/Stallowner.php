<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stallowner extends Model
{
    use HasFactory;

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
        return $this->hasOne(Stallrentaldet::class, 'ownerId', 'ownerId');
    }

    public function children()
    {
        return $this->hasMany(StallOwnerChild::class, 'STALLOWNER_stallOwnerId', 'stallOwnerId');
    }

    public function employees()
    {
        return $this->hasMany(StallOwnerEmp::class, 'STALLOWNER_stallOwnerId', 'stallOwnerId');
    }
}
