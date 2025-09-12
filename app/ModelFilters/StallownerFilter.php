<?php 

namespace App\ModelFilters;

use EloquentFilter\ModelFilter;

class StallownerFilter extends ModelFilter
{
    /**
    * Related Models that have ModelFilters as well as the method on the ModelFilter
    * As [relationMethod => [input_key1, input_key2]].
    *
    * @var array
    */
    public $relations = [];

    public function name($value)
    {
        $fullname = trim(preg_replace('/\s+/', ' ', $value));
        return $this->whereRaw("CONCAT(firstname, ' ', lastname) LIKE ?", ["%{$fullname}%"]);
    }

    public function status($value)
    {
        return $this->where('ownerStatus', $value);
    }
}
