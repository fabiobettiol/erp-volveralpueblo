<?php

namespace App\Observers;

use App\Models\EventOther;
use Illuminate\Support\Facades\Auth;

class EventOtherObserver
{
    /**
     * Handle the EventOther "creating" event.
     */
    public function creating(EventOther $eventOther): void
    {
        $eventOther->user_id = Auth::user()->id;
        (Auth::user()->is_cdr) ? $eventOther->cdr_id = Auth::user()->cdr_id: NULL; 
    }
    
    /**
     * Handle the EventOther "created" event.
     */
    public function created(EventOther $eventOther): void
    {
        //
    }

    /**
     * Handle the EventOther "updated" event.
     */
    public function updated(EventOther $eventOther): void
    {
        //
    }

    /**
     * Handle the EventOther "deleted" event.
     */
    public function deleted(EventOther $eventOther): void
    {
        //
    }

    /**
     * Handle the EventOther "restored" event.
     */
    public function restored(EventOther $eventOther): void
    {
        //
    }

    /**
     * Handle the EventOther "force deleted" event.
     */
    public function forceDeleted(EventOther $eventOther): void
    {
        //
    }
}
