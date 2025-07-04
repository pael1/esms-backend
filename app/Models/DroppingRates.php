<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DroppingRates extends Model
{
    use HasFactory;

    protected $table = 'droppingrates';

    protected $primaryKey = 'iddroppingRateId';

    protected $fillable = [
        'iddroppingRateId',
        'commodity',
        'unit',
        'rate',
        'AccountCode',
    ];

    // public function user(): BelongsTo
    // {
    //     return $this->belongsTo(UserAccount::class, 'SystemUser_EmpId', 'EmpId');
    // }
}
