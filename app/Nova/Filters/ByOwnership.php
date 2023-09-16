<?php

namespace App\Nova\Filters;

use App\Models\Ownership;
use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;

class ByOwnership extends Filter
{
    public $name = 'Por titularidad';
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
        return $query->where('ownership_id', $value);
    }

    /**
     * Get the filter's available options.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function options(Request $request)
    {
        return Ownership::orderBy('name', 'ASC')
            ->get()
            ->pluck('id','name');
    }
}
