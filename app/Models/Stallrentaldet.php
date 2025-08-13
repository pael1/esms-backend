<?php

namespace App\Models;

use App\Models\Stallowner;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Stallrentaldet extends Model
{
    use HasFactory;

    protected $table = 'stallrentaldet';

    protected $primaryKey = 'stallDetailId';

    protected $fillable = [
        'stallDetailId',
        'ownerId ',
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
        'leaseContract ',
        'documentFiles ',
        'busIDStatus',
    ];

    public function stallProfile()
    {
        return $this->hasOne(Stallprofile::class, 'stallNo', 'stallNo');
    }

    public function stallOwner()
    {
        return $this->hasOne(Stallowner::class, 'stallOwnerId', 'STALLOWNER_stallOwnerId');
    }

    //views table
    // public function stallProfileViews()
    // {
    //     return $this->hasOne(StallProfileViews::class, 'stallNo', 'stallNo');
    // }
}
