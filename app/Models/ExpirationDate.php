<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpirationDate extends Model
{
    use HasFactory;

    protected $table = 'expirationdate';

    protected $primaryKey = 'idexpirationdate';

    protected $fillable = [
        'idexpirationdate ',
        'date',
        'description',
        'ispenaltiesgenerated',
        'marketcode',
    ];

    // public function user(): BelongsTo
    // {
    //     return $this->belongsTo(UserAccount::class, 'SystemUser_EmpId', 'EmpId');
    // }
}
