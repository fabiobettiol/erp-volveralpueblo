<?php

namespace App\Nova\Metrics;

use App\Models\House;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Trend;

class HousePerMonth extends Trend
{
    public $name = 'Nuevas Viviendas por Mes';

    /**
     * Calculate the value of the metric.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return mixed
     */
    public function calculate(NovaRequest $request)
    {
        if ($request->user()->is_admin) {
            return $this->countByMonths($request, House::class)
                ->showLatestValue();
        } else {
            return $this->countByMonths($request, House::where('cdr_id', $request->user()->cdr_id))
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
        return 'house-per-month';
    }
}
