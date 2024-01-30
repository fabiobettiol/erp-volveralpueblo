<?php

namespace App\Nova\Metrics;

use Carbon\Carbon;
use App\Models\Familymember;
use Laravel\Nova\Metrics\Partition;
use Laravel\Nova\Http\Requests\NovaRequest;

class FamilyMemberAgeCDR extends Partition
{
    /**
     * Calculate the value of the metric.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return mixed
     */
    public function calculate(NovaRequest $request)
    {
        $members = Familymember::where('cdr_id', 22)->get();

        $noInfo = 0;
        $adult = 0;
        $minor  = 0;

        foreach ($members as $miembro) {
            if (empty($miembro->dateofbirth)) {
                 $noInfo++;
            } else {
                if (Carbon::parse($miembro->dateofbirth)->age >= 18) {
                    $adult++;
                } else {
                    $minor++;
                }
            }
        }

        return $this->result([
            'Adulto' => $adult,
            'Menor' => $minor,
            'Sin información' => $noInfo,
        ]);
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
        return 'family-member-age-c-d-r';
    }
}
