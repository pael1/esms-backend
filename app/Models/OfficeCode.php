<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfficeCode extends Model
{
    use HasFactory;

    protected $table = 'officecode';

    protected $primaryKey = 'officecodeid';

    protected $fillable = [
        'officecodeid',
        'officeCode',
        'marketOfficeCode',
        'officeName',
    ];

    // public function user(): BelongsTo
    // {
    //     return $this->belongsTo(UserAccount::class, 'SystemUser_EmpId', 'EmpId');
    // }
}
