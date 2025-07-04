<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StallOP extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'stallop';

    protected $primaryKey = 'stallOPId';

    protected $fillable = [
        'OPRefId',
        'opDate',
        'ownerId',
        'stallNo',
        'opPeriodFrom',
        'opPeriodTo',
        'accountCode',
        'amountBasic',
        'amountInt',
        'amountSurc',
        'postDateTime',
        'postBy',
        'ORNum',
        'ORDate',
        'signatoryid',
        'ORNumExt',
        'purpose',
        'opTN',
        'subcode',
        'fk',
        'isinterest',
        'vendorName',
        'vendorAddress',
        'isarchived',
    ];

    // public function stallowner()
    // {
    //     return $this->belongsTo(Stallowner::class, 'ownerId', 'ownerId');
    // }
}
