<?php

namespace App\Observers;

use App\Models\Familyfollowup;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class FamilyfollowupObserver {
	public function creating(Familyfollowup $interaction) {

		// - Interactions will be always associated to the family's CDR, independly from the user who created it!
		$interaction->cdr_id = $interaction->family->cdr_id;
		$interaction->user_id = Auth::user()->id;
		
	}

	/**
	 * Handle the Familyinteraction "created" event.
	 *
	 * @param  \App\Models\Familyinteraction  $familyinteraction
	 * @return void
	 */
	public function created(Familyfollowup $familyinteraction) {
		//
	}

	/**
	 * Handle the Familyinteraction "updated" event.
	 *
	 * @param  \App\Models\Familyinteraction  $familyinteraction
	 * @return void
	 */
	public function updated(Familyfollowup $familyinteraction) {
		//
	}

	/**
	 * Handle the Familyinteraction "deleted" event.
	 *
	 * @param  \App\Models\Familyinteraction  $familyinteraction
	 * @return void
	 */
	public function deleted(Familyfollowup $familyinteraction) {
		//
	}

	/**
	 * Handle the Familyinteraction "restored" event.
	 *
	 * @param  \App\Models\Familyinteraction  $familyinteraction
	 * @return void
	 */
	public function restored(Familyfollowup $familyinteraction) {
		//
	}

	/**
	 * Handle the Familyinteraction "force deleted" event.
	 *
	 * @param  \App\Models\Familyinteraction  $familyinteraction
	 * @return void
	 */
	public function forceDeleted(Familyfollowup $familyinteraction) {
		//
	}
}
