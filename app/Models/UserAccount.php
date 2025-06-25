<?php

namespace App\Models;

use App\Models\SystemUser;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserAccount extends Model
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $table = 'useraccount';

    protected $primaryKey = 'UserId';
    
    protected $fillable = [
        'UserId',
        'Password',
        'DateTimeCreated',
        'CreatedBy',
        'NextReset',
        'LastReset',
        'ResetBy',
        'AccountGroup',
        'DefWorkDayPermit',
        'DefWorkTimePermitStart',
        'DefWorkTimePermitEnd',
        'SysAdmin',
        'Supervisor',
        'DistCode',
        'AccntStat',
        'AccntStatDate',
        'LastLogIn',
        'LastLogOut',
        'LastLogIPAddress',
        'SystemUser_EmpId'
    ];

    public function user_detail(): HasOne
    {
        return $this->hasOne(SystemUser::class, 'EmpId', 'SystemUser_EmpId');
    }
}
