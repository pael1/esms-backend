<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class UserAccount extends Model
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    protected $guard_name = 'api';

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
        'SystemUser_EmpId',
    ];

    public function user_detail(): HasOne
    {
        return $this->hasOne(SystemUser::class, 'EmpId', 'SystemUser_EmpId');
    }
}
