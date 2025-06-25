<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StallProfileViews extends Model
{
    use HasFactory, Notifiable, HasApiTokens;

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
        'RatePerDay'
    ];
}
