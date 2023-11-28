<?php

namespace App\Observers;

use App\Models\Demandant;
use Illuminate\Support\Facades\Auth;

class DemandantObserver
{

    /**
     * Handle the Demandant "creating" event.
     */
    public function creating(Demandant $demandant): void
    {
        $demandant->cdr_id = Auth::user()->cdr_id;
    }


    /**
     * Handle the Demandant "created" event.
     */
    public function created(Demandant $demandant): void
    {
        //
    }

    /**
     * Handle the Demandant "updated" event.
     */
    public function updated(Demandant $demandant): void
    {
        //
    }

    /**
     * Handle the Demandant "deleted" event.
     */
    public function deleted(Demandant $demandant): void
    {
        //
    }

    /**
     * Handle the Demandant "restored" event.
     */
    public function restored(Demandant $demandant): void
    {
        //
    }

    /**
     * Handle the Demandant "force deleted" event.
     */
    public function forceDeleted(Demandant $demandant): void
    {
        //
    }
}
