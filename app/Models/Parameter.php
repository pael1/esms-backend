<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Parameter extends Model
{
    use HasFactory;

    protected $table = 'parameter';

    protected $primaryKey = 'parameterId';

    protected $fillable = [
        'parameterId',
        'fieldId',
        'fieldValue',
        'fieldDescription',
        'dateTimeEncoded',
        'encodedBy'
    ];

    // public function user(): BelongsTo 
    // {
    //     return $this->belongsTo(UserAccount::class, 'SystemUser_EmpId', 'EmpId');
    // }
}
