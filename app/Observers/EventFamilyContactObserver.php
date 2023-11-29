<?php

namespace App\Observers;

use App\Models\EventFamilycontact;
use Illuminate\Support\Facades\Auth;

class EventFamilyContactObserver
{

    /**
     * Handle the EventOther "creating" event.
     */
    public function creating(EventFamilycontact $eventfamilycontact): void
    {
        $eventfamilycontact->user_id = Auth::user()->id;
        (Auth::user()->is_cdr) ? $eventfamilycontact->cdr_id = Auth::user()->cdr_id: NULL; 
    }

    /**
     * Handle the Familycontact "created" event.
     */
    public function created(EventFamilycontact $eventfamilycontact): void
    {
        //
    }

    /**
     * Handle the Familycontact "updated" event.
     */
    public function updated(EventFamilycontact $eventfamilycontact): void
    {
        //
    }

    /**
     * Handle the Familycontact "deleted" event.
     */
    public function deleted(EventFamilycontact $eventfamilycontact): void
    {
        //
    }

    /**
     * Handle the Familycontact "restored" event.
     */
    public function restored(EventFamilycontact $eventfamilycontact): void
    {
        //
    }

    /**
     * Handle the Familycontact "force deleted" event.
     */
    public function forceDeleted(EventFamilycontact $eventfamilycontact): void
    {
        //
    }
}
