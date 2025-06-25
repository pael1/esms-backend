<?php

namespace App\Models;

use App\Models\Stallowner;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StallOwnerChild extends Model
{
    use HasFactory;

    protected $table = 'stallownerchild';

    protected $primaryKey = 'stallOwnerChildId';

    protected $fillable = [
        'stallOwnerChildId',
        'ownerId',
        'childName',
        'childBDate',
        'STALLOWNER_stallOwnerId',
    ];

    // public function childrens()
    // {
    //     return $this->belongsTo(Stallowner::class, 'stallOwnerId', 'STALLOWNER_stallOwnerId');
    // }
}
