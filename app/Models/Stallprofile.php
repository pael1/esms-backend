<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Stallprofile extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'stallprofile';

    protected $primaryKey = 'stallProfileId ';

    protected $fillable = [
        'stallProfileId',
        'stallNo',
        'stallType',
        'sectionCode',
        'marketCode',
        'stallDescription',
        'picPath',
        'stallArea',
        'stallClass',
        'CFSI',
        'stallStatus',
        'fixRatePerSqm',
        'ratePerDay ',
        'ratePerMonth ',
        'stallNoId ',
        'StallAreaExt',
    ];

    public function signatory()
    {
        return $this->hasOne(Signatory::class, 'marketOfficeCode', 'marketCode');
    }

    public function officecode()
    {
        return $this->hasOne(OfficeCode::class, 'marketOfficeCode', 'marketCode');
    }
}
