<?php

namespace App\Nova\Metrics;

use App\Models\Business;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Trend;

class BusinessPerMonth extends Trend
{
    public $name = 'Nuevos Negocios por Mes';

    /**
     * Calculate the value of the metric.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return mixed
     */
    public function calculate(NovaRequest $request)
    {
        if ($request->user()->is_admin) {
            return $this->countByMonths($request, Business::class)
                ->showLatestValue();
        } else {
            return $this->countByMonths($request, Business::where('cdr_id', $request->user()->cdr_id))
                ->showLatestValue();
        }
    }

    /**
     * Get the ranges available for the metric.
     *
     * @return array
     */
    public function ranges()
    {
        return [
            3 => __('3 Months'),
            6 => __('6 Months'),
            12 => __('12 Months'),
            24 => __('24 Months'),
        ];
    }

    /**
     * Determine for how many minutes the metric should be cached.
     *
     * @return  \DateTimeInterface|\DateInterval|float|int
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
        return 'business-per-month';
    }
}
