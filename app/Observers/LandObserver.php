<?php

namespace App\Observers;

use App\Models\Community;
use App\Models\Land;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class LandObserver
{
    public function creating(Land $land)
    {
        $this->setLocation($land);
    }

    /**
     * Handle the Land "created" event.
     *
     * @param  \App\Models\Land  $land
     * @return void
     */
    public function created(Land $land)
    {
        $this->createLandReference($land);
        $this->updateCDR($land);
    }

    /**
     * Handle the Land "updated" event.
     *
     * @param  \App\Models\Land  $land
     * @return void
     */
    public function updated(Land $land)
    {
        //
    }

    /**
     * Handle the Land "deleted" event.
     *
     * @param  \App\Models\Land  $land
     * @return void
     */
    public function deleted(Land $land)
    {
        //
    }

    /**
     * Handle the Land "restored" event.
     *
     * @param  \App\Models\Land  $land
     * @return void
     */
    public function restored(Land $land)
    {
        //
    }

    /**
     * Handle the Land "force deleted" event.
     *
     * @param  \App\Models\Land  $land
     * @return void
     */
    public function forceDeleted(Land $land)
    {
        //
    }

    // - Creates ans stores the new land reference.
    protected function createLandReference($land)
    {
        $acronym = $land->community->acronym;
        $refCounter = $land->community->land_counter + 1;

        $todaysYear = Carbon::now()->year;

        if ($refCounter < 10) {
            $paddedCounter = '00'. $refCounter;
        } elseif ($refCounter < 100) {
            $paddedCounter = '0'. $refCounter;
        } else {
            $paddedCounter = $refCounter;
        }

        $newReference = 'T'. $acronym . $todaysYear . $paddedCounter;

        // - Update Land with its newly generated reference
        $updateLand = Land::find($land->id);
        $updateLand->reference = $newReference;
        $updateLand->available = 1; // - Set to available every newly created land
        $updateLand->save();

        // - Update Community, with the increased counter
        $updateCommunity = Community::find($land->community->id);
        $updateCommunity->land_counter = $refCounter;
        $updateCommunity->save();
    }

    // - Updates the CDR field (cdr_id) for CDR's users
    protected function updateCDR($land) {

        $user = Auth::user();

        if (!$user->is_admin && $user->is_cdr) { // - Only applies to 'is_cdr' users
            // - Update Land with the cdr_id of the current user
            $updateLand = Land::find($land->id);
            $updateLand->cdr_id = $user->cdr_id;
            $updateLand->save();
        }
    }

    protected function setLocation($land) {
        $response = json_decode(
            \GoogleMaps::load('geocoding')
                ->setParam ([
                    'address' => $land->postcoce .', '. $land->town .', '. $land->municipality->name .', '. $land->province->name. ', '. 'España'
                ])->get()
        );

        if ($response->status == 'OK') {
            $location = $response->results[0]->geometry->location;

            $land->lat = $location->lat;
            $land->lng = $location->lng;            
            $land->mapinfo = true;
        }

    }    
}
