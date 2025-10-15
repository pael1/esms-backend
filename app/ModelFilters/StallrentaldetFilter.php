<?php 

namespace App\ModelFilters;

use EloquentFilter\ModelFilter;

class StallrentaldetFilter extends ModelFilter
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
        return $this->whereHas('stallProfile', function ($q) use ($marketcode) {
            $q->where('marketCode', $marketcode);
        });
    }

    public function section($section)
    {
        return $this->whereHas('stallProfile', function ($q) use ($section) {
            $q->whereRaw('SUBSTRING(sectionCode, 3, 2) = ?', [$section]);
        });
    }

    public function name($name)
    {
        $fullname = trim(preg_replace('/\s+/', ' ', $name));

        return $this->whereHas('stallOwner', function ($q) use ($fullname) {
            $q->where('lastname', 'LIKE', "%{$fullname}%")
            ->orWhere('firstname', 'LIKE', "%{$fullname}%")
            ->orWhereRaw("CONCAT(firstname, ' ', lastname) LIKE ?", ["%{$fullname}%"]);
        });
    }

    public function status($value)
    {
        return $this->where('rentalStatus', $value);
    }
}
