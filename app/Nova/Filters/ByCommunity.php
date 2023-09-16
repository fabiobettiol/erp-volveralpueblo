<?php

namespace App\Nova\Filters;

use App\Models\Community;
use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;

class ByCommunity extends Filter
{
    public $name = 'Por Comunidad Autónoma';
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
        return $query->where('community_id', $value);
    }

    /**
     * Get the filter's available options.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function options(Request $request)
    {
        return Community::orderBy('name', 'ASC')
            ->get()
            ->pluck('id','name');
    }
}
