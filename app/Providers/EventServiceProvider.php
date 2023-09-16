<?php

namespace App\Providers;

use App\Models\Business;
use App\Models\Demandantfollowup;
use App\Models\Family;
use App\Models\Familyfollowup;
use App\Models\Familycontact;
use App\Models\Familymember;
use App\Models\House;
use App\Models\Job;
use App\Models\Land;
use App\Models\User;
use App\Observers\BusinessObserver;
use App\Observers\DemandantfollowupObserver;
use App\Observers\FamilycontactObserver;
use App\Observers\FamilyfollowupObserver;
use App\Observers\FamilymemberObserver;
use App\Observers\FamilyObserver;
use App\Observers\HouseObserver;
use App\Observers\JobObserver;
use App\Observers\LandObserver;
use App\Observers\UserObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider {
	/**
	 * The event listener mappings for the application.
	 *
	 * @var array
	 */
	protected $listen = [
		Registered::class => [
			SendEmailVerificationNotification::class,
		],
	];

	/**
	 * Register any events for your application.
	 *
	 * @return void
	 */
	public function boot() {
		House::observe(HouseObserver::class);
		Land::observe(LandObserver::class);
		Business::observe(BusinessObserver::class);
		Job::observe(JobObserver::class);
		User::observe(UserObserver::class);
		Family::observe(FamilyObserver::class);
		Familymember::observe(FamilymemberObserver::class);
		Familyfollowup::observe(FamilyfollowupObserver::class);
		Familycontact::observe(FamilycontactObserver::class);
		Demandantfollowup::observe(DemandantfollowupObserver::class);
	}
}
