<?php

namespace App\Nova\Filters;

use App\Models\Arearange;
use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;

class ByArea extends Filter
{
    public $name = 'Por rangos de área';
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
         return $query->where('area_id', $value);
    }

    /**
     * Get the filter's available options.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function options(Request $request)
    {
        return Arearange::orderBy('id', 'ASC')
            ->get()
            ->pluck('id','range');
    }
}
