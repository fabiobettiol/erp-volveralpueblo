<?php

namespace App\Providers;

use App\Models\Job;
use App\Models\Land;
use App\Models\User;
use App\Models\House;
use App\Models\Family;
use App\Models\Business;
use App\Models\Demandant;
use App\Models\EventCall;
use App\Models\EventOther;
use App\Models\EventMeeting;
use App\Models\Familymember;
use App\Models\Familycontact;
use App\Models\Familyfollowup;
use App\Observers\JobObserver;
use App\Observers\LandObserver;
use App\Observers\UserObserver;
use App\Observers\HouseObserver;
use App\Models\Demandantfollowup;
use App\Observers\FamilyObserver;
use App\Models\EventFamilycontact;
use App\Models\EventFamilyfollowup;
use App\Observers\BusinessObserver;
use App\Observers\DemandantObserver;
use App\Observers\EventCallObserver;
use App\Observers\EventOtherObserver;
use Illuminate\Support\Facades\Event;
use App\Models\EventDemandantfollowup;
use Illuminate\Auth\Events\Registered;
use App\Observers\EventMeeintgObserver;
use App\Observers\EventMeetingObserver;
use App\Observers\FamilymemberObserver;
use App\Observers\FamilycontactObserver;
use App\Observers\FamilyfollowupObserver;
use App\Observers\DemandantfollowupObserver;
use App\Observers\EventFamilyContactObserver;
use App\Observers\EventFamilyFollowupObserver;
use App\Observers\EventDemantantFollowupObserver;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

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
		Demandant::observe(DemandantObserver::class);
		Demandantfollowup::observe(DemandantfollowupObserver::class);
		EventCall::observe(EventCallObserver::class);
		EventMeeting::observe(EventMeetingObserver::class);
		EventOther::observe(EventOtherObserver::class);
		EventDemandantfollowup::observe(EventDemantantFollowupObserver::class);
		EventFamilycontact::observe(EventFamilyContactObserver::class);
		EventFamilyfollowup::observe(EventFamilyFollowupObserver::class);
	}
}