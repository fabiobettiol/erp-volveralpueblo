<?php

namespace App\Nova\Metrics;

use Laravel\Nova\Nova;
use Laravel\Nova\Metrics\Trend;
use App\Models\Demandantfollowup;
use Laravel\Nova\Http\Requests\NovaRequest;

class DemandantFollowUpPerDayCDR extends Trend
{
    public function name()
    {
        return 'Interacciones por día';
    }

    /**
     * Calculate the value of the metric.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return mixed
     */
    public function calculate(NovaRequest $request)
    {
        return $this->countByDays($request, Demandantfollowup::where('cdr_id', $request->user()->cdr_id), 'date');
    }

    /**
     * Get the ranges available for the metric.
     *
     * @return array
     */
    public function ranges()
    {
        return [
            30 => Nova::__('30 Dias'),
            60 => Nova::__('60 Dias'),
            90 => Nova::__('90 Dias'),
            180 => Nova::__('180 Dias'),
            365 => Nova::__('365 Dias'),
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

    /**
     * Get the URI key for the metric.
     *
     * @return string
     */
    public function uriKey()
    {
        return 'demandant-follow-up-per-day-c-d-r';
    }
}
