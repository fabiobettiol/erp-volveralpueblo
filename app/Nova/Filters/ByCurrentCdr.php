<?php

namespace App\Nova\Filters;

use App\Models\Cdr;
use Laravel\Nova\Filters\Filter;
use Illuminate\Support\Facades\Auth;
use Laravel\Nova\Http\Requests\NovaRequest;

class ByCurrentCdr extends Filter
{
    /* FABIO:
     * This a special filter that allows to set only 2 options:
     * User's own CDR or All of the CDRs.
     * Also, the deafult filter value is set the the user's CDR
    */

    public $name = 'Por CDR';

    /**
     * The filter's component.
     *
     * @var string
     */
    public $component = 'select-filter';

    /**
     * Apply the filter to the given query.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  mixed  $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function apply(NovaRequest $request, $query, $value)
    {
        return $query->where('cdr_id', $value);   
    }

    public function default()
    {
        if (Auth::user()->is_cdr && Auth::user()->cdr_id) {
            return Auth::user()->cdr_id;
        }
    }

    /**
     * Get the filter's available options.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function options(NovaRequest $request)
    {
        return Cdr::where('id', $request->user()->cdr_id) 
            ->get()
            ->pluck('id','name');
    }
}
