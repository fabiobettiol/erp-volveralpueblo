<?php

namespace App\Nova\Metrics;

use Laravel\Nova\Nova;
use App\Models\Demandant;
use Laravel\Nova\Metrics\Value;
use App\Models\Demandantfollowup;
use Laravel\Nova\Http\Requests\NovaRequest;

class Demandants extends Value
{
    /**
     * Calculate the value of the metric.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return mixed
     */
    public function calculate(NovaRequest $request)
    {
        return $this->count($request, Demandantfollowup::where('cdr_id', $request->user()->cdr_id));
    }

    /**
     * Get the ranges available for the metric.
     *
     * @return array
     */
    public function ranges()
    {
        return [
            'YTD' => Nova::__('Este año'),
            'QTD' => Nova::__('Este trimestre'),
            'MTD' => Nova::__('Este mes'),
            'ALL' => Nova::__('Totales'),
        ];
    }

    /**
     * Determine the amount of time the results of the metric should be cached.
     *
     * @return \DateTimeInterface|\DateInterval|float|int|null
     */
    public function cacheFor()
    {
        // return now()->addMinutes(5);
    }

    public function name()
    {
        return 'Solicitantes';
    }    
    
}
