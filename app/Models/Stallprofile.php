<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Stallprofile extends Model
{
    use HasFactory, Notifiable, HasApiTokens;

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
        'StallAreaExt'
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
