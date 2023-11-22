<?php

namespace App\Observers;
use App\Models\Demandantfollowup;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DemandantfollowupObserver {
	public function creating(Demandantfollowup $interaction) {

		// - Interactions will be always associated to a CDR, independly from the user who created it!
		$interaction->cdr_id = Auth::user()->cdr_id;
		$interaction->user_id = Auth::user()->id;
	}
}
