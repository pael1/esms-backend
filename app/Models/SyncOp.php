<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SyncOp extends Model
{
    use HasFactory;

    protected $casts = [
        'months' => 'array',
    ];

    protected $fillable = ['ornumber', 'ownerid', 'months', 'is_processed', 'paid_manually_by',
    'paid_manually_at','reason'];
}
