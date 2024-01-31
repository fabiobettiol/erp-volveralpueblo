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
        $EventCall->user_id = Auth::user()->id;
        if(Auth::user()->is_cdr) {
            $EventCall->cdr_id = Auth::user()->cdr_id;
        }
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
