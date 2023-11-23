<?php

namespace App\Observers;

use App\Models\Business;
use App\Models\Community;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class BusinessObserver
{
    public function creating(Business $business)
    {
        $this->setLocation($business);
    }

    /**
     * Handle the Business "created" event.
     *
     * @param  \App\Models\Business  $business
     * @return void
     */
    public function created(Business $business)
    {
        $this->createBusinessReference($business);
        $this->updateCDR($business);
    }

    /**
     * Handle the Business "updated" event.
     *
     * @param  \App\Models\Business  $business
     * @return void
     */
    public function updated(Business $business)
    {
        //
    }

    /**
     * Handle the Business "deleted" event.
     *
     * @param  \App\Models\Business  $business
     * @return void
     */
    public function deleted(Business $business)
    {
        //
    }

    /**
     * Handle the Business "restored" event.
     *
     * @param  \App\Models\Business  $business
     * @return void
     */
    public function restored(Business $business)
    {
        //
    }

    /**
     * Handle the Business "force deleted" event.
     *
     * @param  \App\Models\Business  $business
     * @return void
     */
    public function forceDeleted(Business $business)
    {
        //
    }

    // - Creates ans stores the new business reference.
    protected function createBusinessReference($business)
    {
        $acronym = $business->community->acronym;
        $refCounter = $business->community->business_counter + 1;

        $todaysYear = Carbon::now()->year;

        if ($refCounter < 10) {
            $paddedCounter = '00'. $refCounter;
        } elseif ($refCounter < 100) {
            $paddedCounter = '0'. $refCounter;
        } else {
            $paddedCounter = $refCounter;
        }

        $newReference = 'N'. $acronym . $todaysYear . $paddedCounter;

        // - Update Business with its newly generated reference
        $updateBusiness = Business::find($business->id);
        $updateBusiness->reference = $newReference;
        $updateBusiness->available = 1; // - Set to available every newly created business
        $updateBusiness->save();

        // - Update Community, with the increased counter
        $updateCommunity = Community::find($business->community->id);
        $updateCommunity->business_counter = $refCounter;
        $updateCommunity->save();
    }

    // - Updates the CDR field (cdr_id) for CDR's users
    protected function updateCDR($business) {

        $user = Auth::user();

        if (!$user->is_admin && $user->is_cdr) { // - Only applies to 'is_cdr' users
            // - Update Business with the cdr_id of the current user
            $updateBusiness = Business::find($business->id);
            $updateBusiness->cdr_id = $user->cdr_id;
            $updateBusiness->save();
        }
    }

    protected function setLocation($business) {
        $response = json_decode(
            \GoogleMaps::load('geocoding')
                ->setParam ([
                    'address' => $business->postcoce .', '. $business->town .', '. $business->municipality->name .', '. $business->province->name. ', '. 'España'
                ])->get()
        );

        if ($response->status == 'OK') {
            $location = $response->results[0]->geometry->location;

            $business->lat = $location->lat;
            $business->lng = $location->lng;            
            $business->mapinfo = true;
        } else {
            Log::channel('custom')->info(json_encode($response));
        }

    }
}
