<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StallOwnerAccount extends Model
{
    use HasFactory;

    protected $table = 'stallowneraccount';

    protected $primaryKey = 'stallOwnerAccountId';

    public $timestamps = false;

    protected $fillable = [
        'stallOwnerAccountId',
        'ownerId',
        'month',
        'year',
        'amountBasic',
        'amountSurc',
        'amountInt',
        'ORNum',
        'OPRefId',
        'ORDate',
        'generatedBy',
        'isadded',
        'expdate',
    ];

    // public function ledger()
    // {
    //     return $this->belongsTo(Stallowner::class, 'ownerId', 'ownerId');
    // }
}
