<?php

namespace App\Observers;

use App\Models\Familymember;

class FamilymemberObserver {
	public function creating(Familymember $member) {

		$member->cdr_id = $member->family->cdr_id;

	}

	/**
	 * Handle the Familymember "created" event.
	 *
	 * @param  \App\Models\Familymember  $familymember
	 * @return void
	 */
	public function created(Familymember $familymember) {
		//
	}

	/**
	 * Handle the Familymember "updated" event.
	 *
	 * @param  \App\Models\Familymember  $familymember
	 * @return void
	 */
	public function updated(Familymember $familymember) {
		//
	}

	/**
	 * Handle the Familymember "deleted" event.
	 *
	 * @param  \App\Models\Familymember  $familymember
	 * @return void
	 */
	public function deleted(Familymember $familymember) {
		//
	}

	/**
	 * Handle the Familymember "restored" event.
	 *
	 * @param  \App\Models\Familymember  $familymember
	 * @return void
	 */
	public function restored(Familymember $familymember) {
		//
	}

	/**
	 * Handle the Familymember "force deleted" event.
	 *
	 * @param  \App\Models\Familymember  $familymember
	 * @return void
	 */
	public function forceDeleted(Familymember $familymember) {
		//
	}
}
