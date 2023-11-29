<?php

namespace App\Observers;

use App\Models\EventDemandantfollowup;
use Illuminate\Support\Facades\Auth;

class EventDemantantFollowupObserver
{
    /**
     * Handle the EventOther "creating" event.
     */
    public function creating(EventDemandantfollowup $eventdemandantfollowup): void
    {
        $eventdemandantfollowup->user_id = Auth::user()->id;
        (Auth::user()->is_cdr) ? $eventdemandantfollowup->cdr_id = Auth::user()->cdr_id: NULL; 
    }

    /**
     * Handle the eventdemandantfollowup "created" event.
     */
    public function created(EventDemandantfollowup $eventdemandantfollowup): void
    {
        //
    }

    /**
     * Handle the eventdemandantfollowup "updated" event.
     */
    public function updated(EventDemandantfollowup $eventdemandantfollowup): void
    {
        //
    }

    /**
     * Handle the eventdemandantfollowup "deleted" event.
     */
    public function deleted(EventDemandantfollowup $eventdemandantfollowup): void
    {
        //
    }

    /**
     * Handle the eventdemandantfollowup "restored" event.
     */
    public function restored(EventDemandantfollowup $eventdemandantfollowup): void
    {
        //
    }

    /**
     * Handle the eventdemandantfollowup "force deleted" event.
     */
    public function forceDeleted(EventDemandantfollowup $eventdemandantfollowup): void
    {
        //
    }
}
