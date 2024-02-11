<?php

namespace App\Nova\Filters;

use App\Models\Province;
use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;

class ByProvince extends Filter
{
    public $name = 'Por provincia';
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
        return $query->where('province_id', $value);
    }

    /**
     * Get the filter's available options.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function options(Request $request)
    {
        return Province::orderBy('name', 'ASC')
            ->get()
            ->pluck('id','name');
    }
}
