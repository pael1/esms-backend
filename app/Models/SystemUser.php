<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SystemUser extends Model
{
    use HasFactory;

    protected $table = 'systemuser';

    protected $primaryKey = 'EmpId';

    protected $fillable = [
        'EmpId',
        'UserLastName',
        'UserFirstName',
        'UserMidInit',
        'OfficeCode',
        'PositionTitle',
        'ItemNo',
        'Designation',
        'DateTimeCreated',
        'CreatedBy',
        'MarketCode',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(UserAccount::class, 'SystemUser_EmpId', 'EmpId');
    }
}
