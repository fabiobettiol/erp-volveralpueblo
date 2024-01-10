<?php

namespace App\Observers;

use App\Models\EventCall;
use Illuminate\Support\Facades\Auth;

class EventCallObserver
{
    /**
     * Handle the EventOther "creating" event.
     */
    public function creating(EventCall $EventCall): void
    {
        $eventCall->user_id = Auth::user()->id;
        (Auth::user()->is_cdr) ? $eventCall->cdr_id = Auth::user()->cdr_id: NULL; 
    }

    /**
     * Handle the EventCall "created" event.
     */
    public function created(EventCall $eventCall): void
    {
        //
    }

    /**
     * Handle the EventCall "updated" event.
     */
    public function updated(EventCall $eventCall): void
    {
        //
    }

    /**
     * Handle the EventCall "deleted" event.
     */
    public function deleted(EventCall $eventCall): void
    {
        //
    }

    /**
     * Handle the EventCall "restored" event.
     */
    public function restored(EventCall $eventCall): void
    {
        //
    }

    /**
     * Handle the EventCall "force deleted" event.
     */
    public function forceDeleted(EventCall $eventCall): void
    {
        //
    }
}
