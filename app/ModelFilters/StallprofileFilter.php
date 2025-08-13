<?php 

namespace App\ModelFilters;

use EloquentFilter\ModelFilter;

class StallprofileFilter extends ModelFilter
{
    /**
    * Related Models that have ModelFilters as well as the method on the ModelFilter
    * As [relationMethod => [input_key1, input_key2]].
    *
    * @var array
    */
    public $relations = [];

    public function marketcode($marketcode)
    {
        return $this->where('stallprofile.marketcode', $marketcode);
    }

    public function section($section)
    {
        return $this->whereRaw('SUBSTRING(stallprofile.sectionCode, 3, 2) = ?', [$section]);
    }

    public function name($name)
    {
        $fullname = trim(preg_replace('/\s+/', ' ', $name));

        return $this->whereHas('stallRental.stallOwner', function ($query) use ($fullname) {
            $query->whereRaw("CONCAT(firstname, ' ', lastname) LIKE ?", ["%{$fullname}%"]);
        });
    }
}
