<?php

namespace App\Nova\Metrics;

use App\Models\Gender;
use App\Models\Familymember;
use Laravel\Nova\Metrics\Partition;
use Illuminate\Support\Facades\Auth;
use Laravel\Nova\Http\Requests\NovaRequest;

class FamilyMemberGenderCDR extends Partition
{
    public function name()
    {
        return 'Familiares por género';
    }
    /**
     * Calculate the value of the metric.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return mixed
     */
    public function calculate(NovaRequest $request)
    {
        return $this->count($request, Familymember::where('cdr_id', Auth::user()->cdr_id), 'gender_id')
            ->label(function ($value) {
                return Gender::find($value)->name;
            });
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
        return 'family-member-gender-c-d-r';
    }
}
