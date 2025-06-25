<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StallOwnerFiles extends Model
{
    use HasFactory;

    protected $table = 'stallownerfiles';

    protected $primaryKey = 'stallOwnerFileId';

    protected $fillable = [
        'stallOwnerFileId',
        'ownerId',
        'attachFileType',
        'filePath',
    ];
}
