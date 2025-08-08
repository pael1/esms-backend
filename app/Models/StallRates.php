<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StallRates extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'stallrates';

    protected $primaryKey = 'stallrateid';

    protected $fillable = [
        'stallrateid',
        'AppliedYear',
        'SectionCode',
        'StallType',
        'StallClass',
        'Rates',
    ];
}
