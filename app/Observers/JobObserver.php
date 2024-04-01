<?php

namespace App\Observers;

use Carbon\Carbon;
use App\Models\Job;
use App\Models\Community;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class JobObserver
{
    public function creating(Job $job)
    {
        $this->setLocation($job);
    }    

    /**
     * Handle the Job "created" event.
     *
     * @param  \App\Models\Job  $job
     * @return void
     */
    public function created(Job $job)
    {
        $this->createJobReference($job);
        $this->updateCDR($job);
    }

    /**
     * Handle the Job "updated" event.
     *
     * @param  \App\Models\Job  $job
     * @return void
     */
    public function updated(Job $job)
    {
        //
    }

    /**
     * Handle the Job "deleted" event.
     *
     * @param  \App\Models\Job  $job
     * @return void
     */
    public function deleted(Job $job)
    {
        //
    }

    /**
     * Handle the Job "restored" event.
     *
     * @param  \App\Models\Job  $job
     * @return void
     */
    public function restored(Job $job)
    {
        //
    }

    /**
     * Handle the Job "force deleted" event.
     *
     * @param  \App\Models\Job  $job
     * @return void
     */
    public function forceDeleted(Job $job)
    {
        //
    }

    // - Creates and stores the new job reference.
    protected function createJobReference($job)
    {
        $acronym = $job->community->acronym;
        $refCounter = $job->community->job_counter + 1;

        $todaysYear = Carbon::now()->year;

        if ($refCounter < 10) {
            $paddedCounter = '00'. $refCounter;
        } elseif ($refCounter < 100) {
            $paddedCounter = '0'. $refCounter;
        } else {
            $paddedCounter = $refCounter;
        }

        $newReference = 'E'. $acronym . $todaysYear . $paddedCounter;

        // - Update Job with its newly generated reference
        $updateJob = Job::find($job->id);
        $updateJob->reference = $newReference;
        $updateJob->available = 1; // - Set to available every newly created job
        $updateJob->save();

        // - Update Community, with the increased counter
        $updateCommunity = Community::find($job->community->id);
        $updateCommunity->job_counter = $refCounter;
        $updateCommunity->save();
    }

    // - Updates the CDR field (cdr_id) for CDR's users
    protected function updateCDR($job) {

        $user = Auth::user();

        if (!$request->user()->hasPermissionTo('administrator') && $user->is_cdr) { // - Only applies to 'is_cdr' users
            // - Update Job with the cdr_id of the current user
            $updateJob = Job::find($job->id);
            $updateJob->cdr_id = $user->cdr_id;
            $updateJob->save();
        }
    }

    protected function setLocation($job) {
        $response = json_decode(
            \GoogleMaps::load('geocoding')
                ->setParam ([
                    'address' => $job->postcoce .', '. $job->town .', '. $job->municipality->name .', '. $job->province->name. ', '. 'España'
                ])->get()
        );

        if ($response->status == 'OK') {
            $location = $response->results[0]->geometry->location;

            $job->lat = $location->lat;
            $job->lng = $location->lng;            
            $job->mapinfo = true;
        } else {
            Log::channel('custom')->info(json_encode($response));
        }

    }    
}
