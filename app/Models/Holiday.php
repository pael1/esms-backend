<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
{
    use HasFactory;

    protected $table = 'holiday';

    protected $primaryKey = 'holidayid';

    protected $fillable = [
        'holidayid',
        'DateHoliday',
        'RefDoc',
        'DateTimeCreated',
        'CreatedBy',
        'holidayType',
    ];

    // public function user(): BelongsTo
    // {
    //     return $this->belongsTo(UserAccount::class, 'SystemUser_EmpId', 'EmpId');
    // }
}
