<?php

namespace App\Nova\Filters;

use App\Models\Form;
use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;

class ByForm extends Filter
{
    public $name = 'Por régimen';
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
        return $query->where('form_id', $value);
    }

    /**
     * Get the filter's available options.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function options(Request $request)
    {
        return Form::orderBy('name', 'ASC')
            ->get()
            ->pluck('id','name');
    }
}
