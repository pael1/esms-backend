<?php

namespace App\Models;

use App\Models\User;
use App\Models\OfficeCode;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserDetail extends Model
{
    use HasFactory;
    protected $table = 'user_details';

    protected $fillable = [
        'user_id',
        'employee_id',
        'firstname',
        'midinit',
        'lastname',
        'office',
        'position',
        'status',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function officeCode()
    {
        return $this->belongsTo(OfficeCode::class, 'office', 'marketOfficeCode');
    }
}
