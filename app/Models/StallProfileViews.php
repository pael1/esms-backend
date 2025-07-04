<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class StallProfileViews extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'vtlstallrates';
    // protected $primaryKey = 'stallProfileId ';

    protected $fillable = [
        'stallNo',
        'stallArea',
        'stallRate',
        'locationInfluenceRate',
        'Total_InfluenceRate',
        'baseRates',
        'extensionRate',
        'stallAreaExt',
        'Total_extensionRate',
        'Total_baseRate',
        'RatePerDay',
    ];
}
