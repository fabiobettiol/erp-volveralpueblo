<?php

namespace App\Observers;
use App\Models\Familycontact;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class FamilycontactObserver
{
	public function creating(Familycontact $contact) {
		// - Contacts will be always associated to a CDR, independly from the user who created it!
		$contact->cdr_id = Auth::user()->cdr_id;
		$contact->user_id = Auth::user()->id;
		if (empty($contact->date)) {
            		// $contact->date = Carbon::now();
        	}
    }
}
