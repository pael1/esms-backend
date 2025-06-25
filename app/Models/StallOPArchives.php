<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StallOPArchives extends Model
{
    use HasFactory;
    
    protected $table = 'stalloparchives';

    protected $primaryKey = 'stallOPId';

    protected $fillable = [
        'stallOPId',
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
        'purpose',
        'ORNumExt',
        'vendorName',
        'vendorAddress',
        'opTN',
        'subcode',
        'datearchived'
    ];

    // public function user(): BelongsTo 
    // {
    //     return $this->belongsTo(UserAccount::class, 'SystemUser_EmpId', 'EmpId');
    // }
}
