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
        $user = Auth::user();
        
        $eventMeeting->user_id = $user->id;
        if ($user->is_cdr) {
            $eventMeeting->cdr_id = $user->cdr_id; 
        }   

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
