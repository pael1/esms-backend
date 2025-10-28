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

    //section code filter with building and sub section format
    public function sectioncode($section)
    {
        return $this->where('stallprofile.sectionCode', [$section]);
    }

    public function name($name)
    {
        // return $this->where('stallNoId', 'LIKE', "%{$name}%");
        return $this->where('stallNoId', $name);
    }
}
