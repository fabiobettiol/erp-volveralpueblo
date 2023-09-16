<?php

namespace App\Observers;

use App\Models\Community;
use App\Models\Family;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class FamilyObserver {
	/**
	 * Handle the Family "created" event.
	 *
	 * @param  \App\Models\Family  $family
	 * @return void
	 */
	public function created(Family $family) {

		$this->createFamilyDefaults($family);
	}

	/**
	 * Handle the Family "updated" event.
	 *
	 * @param  \App\Models\Family  $family
	 * @return void
	 */
	public function updated(Family $family) {
		//
	}

	/**
	 * Handle the Family "deleted" event.
	 *
	 * @param  \App\Models\Family  $family
	 * @return void
	 */
	public function deleted(Family $family) {
		//
	}

	/**
	 * Handle the Family "restored" event.
	 *
	 * @param  \App\Models\Family  $family
	 * @return void
	 */
	public function restored(Family $family) {
		//
	}

	/**
	 * Handle the Family "force deleted" event.
	 *
	 * @param  \App\Models\Family  $family
	 * @return void
	 */
	public function forceDeleted(Family $family) {
		//
	}

	// - Creates ans stores the new family defaults.
	protected function createFamilyDefaults($family) {

		$user = Auth::user();

		if (!$user->is_admin && $user->is_cdr) {
			$cdr = $user->cdr->id;
			$community = $user->cdr->community->id;
			$acronym = $user->cdr->community->acronym;
			$refCounter = $user->cdr->community->family_counter + 1;
		} else {
			// - for admins
			$cdr = $family->cdr->id;
			$community = $family->cdr->community->id;
			$acronym = $family->cdr->community->acronym;
			$refCounter = $family->cdr->community->family_counter + 1;
		}

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

		$newReference = 'F' . $acronym . $todaysYear . $paddedCounter;

		// - Update House with its newly generated reference and proper CDR ID
		$updateFamily = Family::find($family->id);
		$updateFamily->reference = $newReference;
		$updateFamily->cdr_id = $cdr;

		$updateFamily->save();

		// - Update Community, with the increased counter
		$updateCommunity = Community::find($community);
		$updateCommunity->family_counter = $refCounter;
		$updateCommunity->save();
	}
}
