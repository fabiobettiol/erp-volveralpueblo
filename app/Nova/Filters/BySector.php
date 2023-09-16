<?php

namespace App\Nova\Filters;

use App\Models\Sector;
use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;

class BySector extends Filter
{
    public $name = 'Por sector';
    /**
     * The filter's component.
     *
     * @var string 
     */
    public $component = 'select-filter';

    /**
     * Apply the filter to the given query.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  mixed  $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function apply(Request $request, $query, $value)
    {
        return $query->where('sector_id', $value);
    }

    /**
     * Get the filter's available options.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function options(Request $request)
    {
        return Sector::orderBy('name', 'ASC')
            ->get()
            ->pluck('id','name');
    }
}
