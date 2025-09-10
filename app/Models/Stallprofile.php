<?php

namespace App\Models;

use App\Models\Parameter;
use App\Models\Signatory;
use App\Models\OfficeCode;
use App\Models\StallRates;
use App\Models\Stallrentaldet;
use EloquentFilter\Filterable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Stallprofile extends Model
{
    use HasApiTokens, HasFactory, Notifiable, Filterable;

    public $timestamps = false;

    protected $table = 'stallprofile';

    protected $primaryKey = 'stallProfileId';

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
        'section_id ',
        'sub_section_id ',
        'building_id ',
        'stall_id_ext',
        'stall_no_id',
    ];

    public function signatory()
    {
        return $this->hasOne(Signatory::class, 'marketOfficeCode', 'marketCode');
    }

    public function officecode()
    {
        return $this->hasOne(OfficeCode::class, 'marketOfficeCode', 'marketCode');
    }

    public function stallRental()
    {
        return $this->hasOne(Stallrentaldet::class, 'stallNo', 'stallNo');
    }

    //rates computations
    // The attributes that should be cast to native types.
    // We'll use the casts method in Laravel 11 as it's the recommended approach.
    protected $casts = [
        'stallArea' => 'float',
        'StallAreaExt' => 'float',
    ];

    /**
     * Define a relationship to the StallRate model.
     * This is not the direct solution for the complex query but is good practice.
     */
    public function stallRates()
    {
        return $this->hasMany(StallRates::class, 'StallType', 'stallType');
    }

    /**
     * Define a relationship to the Parameter model.
     * This is not the direct solution for the complex query but is good practice.
     */
    public function parameter()
    {
        return $this->belongsTo(Parameter::class, 'CFSI', 'fieldDescription');
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors (Virtual Attributes)
    |--------------------------------------------------------------------------
    | The following methods replicate the logic of your complex SQL query
    | and make the results available as properties on the model instance.
    | We will break down the original query into these smaller, more manageable
    | methods.
    */

    /**
     * Get the stall rate from the `stallrates` table.
     * This method translates the first subquery in your SQL statement.
     */
    public function getStallRateAttribute()
    {
        $stallRate = DB::table('stallrates')
            ->where('StallType', 'LIKE', '%' . $this->stallType . '%')
            ->where('StallClass', '=', substr($this->stallClass, -1))
            ->where(function ($query) {
                // This handles the conditional logic for the SectionCode.
                if ($this->stallType === 'REGULAR' && strlen($this->sectionCode) === 6) {
                    $query->where('SectionCode', '=', substr($this->sectionCode, 2, 2));
                } elseif ($this->stallType === 'REGULAR') {
                    $query->where('SectionCode', '=', $this->sectionCode);
                } else {
                    $query->where('SectionCode', '=', '00');
                }
            })
            ->orderBy('AppliedYear', 'desc')
            ->value('Rates');

        return $stallRate ?? 0;
    }

    /**
     * Get the location influence rate from the `parameter` table.
     * This method translates the second subquery.
     */
    public function getLocationInfluenceRateAttribute()
    {
        $locationInfluenceRate = DB::table('parameter')
            ->where('fieldDescription', '=', $this->CFSI)
            ->value('fieldValue');

        return $locationInfluenceRate ?? 0;
    }

    /**
     * Calculate the total influence rate.
     * This accessor uses the two previous accessors.
     */
    public function getTotalInfluenceRateAttribute()
    {
        // We use the `getAttribute()` method to get the value from other accessors.
        $stallRate = $this->getAttribute('stallRate');
        $locationInfluenceRate = $this->getAttribute('locationInfluenceRate');

        return number_format($stallRate * $locationInfluenceRate, 2);
    }

    /**
     * Calculate the base rates.
     */
    public function getBaseRatesAttribute()
    {
        $stallRate = $this->getAttribute('stallRate');
        $totalInfluenceRate = $this->getAttribute('Total_InfluenceRate');

        return number_format($totalInfluenceRate + $stallRate, 2);
    }

    /**
     * Calculate the extension rate.
     */
    public function getExtensionRateAttribute()
    {
        $baseRates = $this->getAttribute('baseRates');

        return number_format($baseRates * 0.2, 2);
    }

    /**
     * Calculate the total extension rate.
     */
    public function getTotalExtensionRateAttribute()
    {
        $extensionRate = $this->getAttribute('extensionRate');
        
        return number_format($extensionRate * $this->StallAreaExt, 2);
    }

    /**
     * Calculate the total base rate.
     */
    public function getTotalBaseRateAttribute()
    {
        $baseRates = $this->getAttribute('baseRates');

        return number_format($baseRates * $this->stallArea, 2);
    }

    /**
     * Calculate the final rate per day.
     */
    public function getTotalRatePerDayAttribute()
    {
        $totalBaseRate = $this->getAttribute('Total_baseRate');
        $totalExtensionRate = $this->getAttribute('Total_extensionRate');

        return number_format($totalBaseRate + $totalExtensionRate, 2);
    }
}
