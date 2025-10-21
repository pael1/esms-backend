<?php

namespace App\Models;

use App\Models\Stallowner;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Stallrentaldet extends Model
{
    use HasFactory, Filterable;

    public $timestamps = false;

    protected $table = 'stallrentaldet';

    protected $primaryKey = 'stallDetailId';

    protected $fillable = [
        'stallDetailId',
        'ownerId',
        'stallNo',
        'rentalStatus',
        'contractStartDate',
        'contractYear',
        'contractEndDate',
        'busID',
        'busPlateNo',
        'busDateStart',
        'capital',
        'lineOfBusiness',
        'STALLOWNER_stallOwnerId',
        'leaseContract',
        'documentFiles',
        'busIDStatus',
    ];

    public function stallProfile()
    {
        return $this->hasOne(Stallprofile::class, 'stallNo', 'stallNo');
    }

    public function stallOwner()
    {
        return $this->belongsTo(Stallowner::class, 'STALLOWNER_stallOwnerId', 'stallOwnerId');
    }

}
