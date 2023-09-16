<?php

namespace App\Nova\Metrics;

use App\Models\Familymember;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Partition;

class FamilyMemberPerAge extends Partition {

	public $name = 'Miembros familiares por edad';
	/**
	 * Calculate the value of the metric.
	 *
	 * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
	 * @return mixed
	 */
	public function calculate(NovaRequest $request) {
		return $this->count($request, Familymember::class, 'is_child')
			->label(function ($value) {

				switch ($value) {
				case 1:
					return 'Menor';
				case '0':
					return 'Adulto';
				default:
					return 'Sin información';
				}
				return $value ? 'Menor' : 'Adulto';
			});
	}

	/**
	 * Determine for how many minutes the metric should be cached.
	 *
	 * @return  \DateTimeInterface|\DateInterval|float|int
	 */
	public function cacheFor() {
		// return now()->addMinutes(5);
	}

	/**
	 * Get the URI key for the metric.
	 *
	 * @return string
	 */
	public function uriKey() {
		return 'family-member-per-age';
	}
}
