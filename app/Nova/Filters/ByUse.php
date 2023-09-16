<?php

namespace App\Nova\Filters;

use App\Models\Landuse;
use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;

class ByUse extends Filter
{
    public $name = 'Por tipo de uso';
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
        return $query->where('use_id', $value);
    }

    /**
     * Get the filter's available options.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function options(Request $request)
    {
        return Landuse::orderBy('use', 'ASC')
            ->get()
            ->pluck('id','use');
    }
}
