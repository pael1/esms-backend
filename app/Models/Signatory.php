<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Signatory extends Model
{
    use HasFactory;

    protected $table = 'signatory';

    protected $primaryKey = 'signatoryId';

    protected $fillable = [
        'signatoryId',
        'signatoryFullName',
        'signatorydesignation',
        'marketOfficeCode',
        'status',
        'encodedby'
    ];

    // public function user(): BelongsTo 
    // {
    //     return $this->belongsTo(UserAccount::class, 'SystemUser_EmpId', 'EmpId');
    // }
}
