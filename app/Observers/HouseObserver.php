<?php

namespace App\Observers;

use App\Models\Community;
use App\Models\House;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class HouseObserver {
	public function creating(House $house) {
		$this->setLocation($house);
	}

	/**
	 * Handle the House "created" event.
	 *
	 * @param  \App\Models\House  $house
	 * @return void
	 */
	public function created(House $house) {
		$this->createHouseDefaults($house);
		$this->updateCDR($house);
	}

	/**
	 * Handle the House "updated" event.
	 *
	 * @param  \App\Models\House  $house
	 * @return void
	 */
	public function updated(House $house) {
		//
	}

	/**
	 * Handle the House "deleted" event.
	 *
	 * @param  \App\Models\House  $house
	 * @return void
	 */
	public function deleted(House $house) {
		//
	}

	/**
	 * Handle the House "restored" event.
	 *
	 * @param  \App\Models\House  $house
	 * @return void
	 */
	public function restored(House $house) {
		//
	}

	/**
	 * Handle the House "force deleted" event.
	 *
	 * @param  \App\Models\House  $house
	 * @return void
	 */
	public function forceDeleted(House $house) {
		//
	}

	// - Creates ans stores the new house defaulst (reference, availability).
	protected function createHouseDefaults($house) {
		$acronym = $house->community->acronym;
		$refCounter = $house->community->house_counter + 1;

		$todaysYear = Carbon::now()->year;

		if ($refCounter < 10) {
			$paddedCounter = '000' . $refCounter;
		} elseif ($refCounter < 100) {
			$paddedCounter = '00' . $refCounter;
		} elseif ($refCounter < 1000) {
			$paddedCounter = '0' . $refCounter;
		} else {
			$paddedCounter = $refCounter;
		}

		$newReference = 'V' . $acronym . $todaysYear . $paddedCounter;

		// - Update House with its newly generated reference
		$updateHouse = House::find($house->id);
		$updateHouse->reference = $newReference;
		$updateHouse->available = 1; // - Set to available every newly created house
		$updateHouse->save();

		// - Update Community, with the increased counter
		$updateCommunity = Community::find($house->community->id);
		$updateCommunity->house_counter = $refCounter;
		$updateCommunity->save();
	}

	// - Updates the CDR field (cdr_id) for CDR's users
	protected function updateCDR($house) {

		$user = Auth::user();

		if (!$user->hasPermissionTo('administrator')) {
			// - Only applies to 'is_cdr' users
			// - Update House with the cdr_id of the current user
			$updateHouse = House::find($house->id);
			$updateHouse->cdr_id = $user->cdr_id;
			$updateHouse->save();
		}
	}

	protected function setLocation($house) {
		$response = json_decode(
			\GoogleMaps::load('geocoding')
				->setParam([
					'address' => $house->postcoce . ', ' . $house->town . ', ' . $house->municipality->name . ', ' . $house->province->name . ', ' . 'España',
				])->get()
		);

		if ($response->status == 'OK') {
			$location = $response->results[0]->geometry->location;

			$house->lat = $location->lat;
			$house->lng = $location->lng;
			$house->mapinfo = true;
		} else {
            Log::channel('custom')->info(json_encode($response));
        }

	}
}
