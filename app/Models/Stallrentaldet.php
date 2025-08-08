<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'STALLOWNER_stallOwnerId ',
        'leaseContract ',
        'documentFiles ',
        'busIDStatus',
    ];

    public function stallProfile()
    {
        return $this->hasOne(Stallprofile::class, 'stallNo', 'stallNo');
    }

    //views table
    // public function stallProfileViews()
    // {
    //     return $this->hasOne(StallProfileViews::class, 'stallNo', 'stallNo');
    // }
}
