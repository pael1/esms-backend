<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StallOwnerEmp extends Model
{
    use HasFactory;

    protected $table = 'stallowneremp';

    protected $primaryKey = 'stallOwnerEmpId';

    protected $fillable = [
        'stallOwnerEmpId',
        'ownerId',
        'employeeName',
        'dateOfBirth',
        'STALLOWNER_stallOwnerId ',
        'age',
        'address',
    ];
}
