<?php

namespace App\Nova\Metrics;

use App\Models\Family;
use Laravel\Nova\Nova;
use Laravel\Nova\Metrics\Value;
use Illuminate\Support\Facades\Auth;
use Laravel\Nova\Http\Requests\NovaRequest;

class FamilyCDR extends Value
{
    public function name()
    {
        return 'Familias';
    }

    /**
     * Calculate the value of the metric.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return mixed
     */
    public function calculate(NovaRequest $request)
    {
        return $this->count($request, Family::where('cdr_id', Auth::user()->cdr_id), 'settlementdate');
    }

    /**
     * Get the ranges available for the metric.
     *
     * @return array
     */
    public function ranges()
    {
        return [
            'TODAY' => Nova::__('Hoy'),
            'MTD' => Nova::__('Este mes'),
            'QTD' => Nova::__('Este trimestre'),
            'YTD' => Nova::__('Este año'),
            30 => Nova::__('Últimos 30 dias'),
            60 => Nova::__('Últimos 60 dias'),
            90 => Nova::__('Últimos 90 dias'),
            180 => Nova::__('Últimos 180 dias'),
            'ALL' => 'Todas'
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
}
