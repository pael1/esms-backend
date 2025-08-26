<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StallOwnerChild extends Model
{
    use HasFactory;

    public $timestamps = false;

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
