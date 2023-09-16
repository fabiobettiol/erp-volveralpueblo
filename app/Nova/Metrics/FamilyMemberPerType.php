<?php

namespace App\Nova\Metrics;

use App\Models\Familymember;
use App\Models\Gender;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Partition;

class FamilyMemberPerType extends Partition {

	public $name = 'Miembros familiares por género';

	protected $genders;

	/**
	 * Calculate the value of the metric.
	 *
	 * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
	 * @return mixed
	 */
	public function calculate(NovaRequest $request) {

		$this->genders = Gender::all()->pluck('name', 'id');

		return $this->count($request, Familymember::class, 'gender_id')
			->label(function ($value) {
				return $this->genders[$value];
			})
			->colors([
				'Femenino' => 'red',
				'Masculino' => '#39A0FB',
				'Sin información' => '#DFDFDF',
			]);
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
		return 'family-member-per-type';
	}
}
