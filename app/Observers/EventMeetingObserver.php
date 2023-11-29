<?php

namespace App\Observers;

use App\Models\EventMeeting;
use Illuminate\Support\Facades\Auth;

class EventMeetingObserver
{
    /**
     * Handle the EventOther "creating" event.
     */
    public function creating(EventMeeting $eventMeeting): void
    {
        $eventMeeting->user_id = Auth::user()->id;
        (Auth::user()->is_cdr) ? $eventMeeting->cdr_id = Auth::user()->cdr_id: NULL; 
    }

    /**
     * Handle the EventMeeting "created" event.
     */
    public function created(EventMeeting $eventMeeting): void
    {
        //
    }

    /**
     * Handle the EventMeeting "updated" event.
     */
    public function updated(EventMeeting $eventMeeting): void
    {
        //
    }

    /**
     * Handle the EventMeeting "deleted" event.
     */
    public function deleted(EventMeeting $eventMeeting): void
    {
        //
    }

    /**
     * Handle the EventMeeting "restored" event.
     */
    public function restored(EventMeeting $eventMeeting): void
    {
        //
    }

    /**
     * Handle the EventMeeting "force deleted" event.
     */
    public function forceDeleted(EventMeeting $eventMeeting): void
    {
        //
    }
}
