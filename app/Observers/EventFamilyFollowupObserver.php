<?php

namespace App\Observers;

use App\Models\EventFamilyfollowup;
use Illuminate\Support\Facades\Auth;

class EventFamilyFollowupObserver
{
    /**
     * Handle the EventOther "creating" event.
     */
    public function creating(EventFamilyfollowup $eventfamilyfollowup): void
    {
        $eventfamilyfollowup->user_id = Auth::user()->id;
        (Auth::user()->is_cdr) ? $eventfamilyfollowup->cdr_id = Auth::user()->cdr_id: NULL; 
    }    
    
    /**
     * Handle the Familyfollowup "created" event.
     */
    public function created(EventFamilyfollowup $eventfamilyfollowup): void
    {
        //
    }

    /**
     * Handle the Familyfollowup "updated" event.
     */
    public function updated(EventFamilyfollowup $eventfamilyfollowup): void
    {
        //
    }

    /**
     * Handle the Familyfollowup "deleted" event.
     */
    public function deleted(EventFamilyfollowup $eventfamilyfollowup): void
    {
        //
    }

    /**
     * Handle the Familyfollowup "restored" event.
     */
    public function restored(EventFamilyfollowup $eventfamilyfollowup): void
    {
        //
    }

    /**
     * Handle the Familyfollowup "force deleted" event.
     */
    public function forceDeleted(EventFamilyfollowup $eventfamilyfollowup): void
    {
        //
    }
}
