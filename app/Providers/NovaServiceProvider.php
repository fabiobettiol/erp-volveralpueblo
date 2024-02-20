<?php

namespace App\Providers;
use App\Nova\Cdr;
use App\Nova\Job;
use App\Nova\Form;
use App\Nova\Land;
use App\Nova\User;
use App\Nova\Zone;
use Carbon\Carbon;
use App\Nova\House;
use App\Nova\Stove;
use App\Nova\Water;
use App\Nova\Cdrnew;
use App\Nova\Family;
use App\Nova\Gender;
use App\Nova\Region;
use App\Nova\Sector;
use App\Nova\Status;
use App\Nova\Cdrtype;
use App\Nova\Country;
use App\Nova\Heating;
use App\Nova\Jobform;
use App\Nova\Landuse;
use App\Nova\Business;
use App\Nova\Landtype;
use App\Nova\Locality;
use App\Nova\Province;
use Laravel\Nova\Nova;
use App\Nova\Arearange;
use App\Nova\Community;
use App\Nova\Demandant;
use App\Nova\EventCall;
use App\Nova\Familydoc;
use App\Nova\Ownership;
use App\Nova\EventOther;
use App\Nova\Pricerange;
use App\Nova\Cdragreement;
use App\Nova\Demandantdoc;
use App\Nova\EventMeeting;
use App\Nova\FamilyImpact;
use App\Nova\Familymember;
use App\Nova\Jobownership;
use App\Nova\Municipality;
use App\Nova\Familycontact;
use App\Nova\Familyfollowup;
use App\Nova\Settlementtype;
use App\Nova\FamilyImpactType;
use App\Nova\Metrics\CdrCount;
use App\Nova\Metrics\JobCount;
use App\Nova\Settlementstatus;
use App\Nova\Demandantfollowup;
use App\Nova\FamilyImpactScope;
use App\Nova\Metrics\LandCount;
use Laravel\Nova\Menu\MenuItem;
use App\Nova\EventFamilycontact;
use App\Nova\Metrics\HouseCount;
use Laravel\Nova\Menu\MenuGroup;
use App\Nova\EventFamilyfollowup;
use App\Nova\Metrics\JobPerMonth;
use App\Nova\Metrics\LandPerMonth;
use Laravel\Nova\Menu\MenuSection;
use App\Nova\Metrics\BusinessCount;
use App\Nova\Metrics\HousePerMonth;
use App\Nova\EventDemandantfollowup;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Blade;
use App\Nova\Metrics\BusinessPerMonth;
use Wdelfuego\NovaCalendar\NovaCalendar;
use Laravel\Nova\NovaApplicationServiceProvider;

class NovaServiceProvider extends NovaApplicationServiceProvider {
	/**
	 * Bootstrap any application services.
	 *
	 * @return void
	 */
	public function boot() {

		parent::boot();

		// - FABIO: Custom APP styles 
		Nova::style('admin', public_path('assets/css/admin.css'));

		Nova::withBreadcrumbs();

		Self::menu();

		Self::footer();
	}

	/**
	 * Register the Nova routes.
	 *
	 * @return void
	 */
	protected function routes() {
		Nova::routes()
			->withAuthenticationRoutes()
			->withPasswordResetRoutes()
			->register();
	}

	/**
	 * Register the Nova gate.
	 *
	 * This gate determines who can access Nova in non-local environments.
	 *
	 * @return void
	 */
	protected function gate() {
		// - Prevenir los soft-deletes para usuarios que no son admin
		Gate::define('canSoftDelete', function ($user) {
			return $user->is_admin;
		});

		Gate::define('viewNova', function ($user) {
			return ($user->is_admin || $user->is_cdr);
		});
	}

	/**
	 * Get the cards that should be displayed on the default Nova dashboard.
	 *
	 * @return array
	 */
	protected function cards() {
		return [
			// new Help,
			(new HouseCount)->width('1/4'),
			(new LandCount)->width('1/4'),
			(new BusinessCount)->width('1/4'),
			(new JobCount)->width('1/4'),
			(new CdrCount)->width('2/3'),
			(new HousePerMonth)->width('1/2'),
			(new BusinessPerMonth)->width('1/2'),
			(new LandPerMonth)->width('1/2'),
			(new JobPerMonth)->width('1/2'),
		];
	}

	/**
	 * Get the extra dashboards that should be displayed on the Nova dashboard.
	 *
	 * @return array
	 */
	protected function dashboards() {
		return [
			new \App\Nova\Dashboards\Main,
		];
	}

	/**
	 * Get the tools that should be listed in the Nova sidebar.
	 *
	 * @return array
	 */
	public function tools() {
		return [
			new NovaCalendar('mi-calendario'),
		];
	}

	/**
	 * Register any application services.
	 *
	 * @return void
	 */
	public function register() {
		//
	}

	protected function footer () {
	    Nova::footer(function () {
			$year = Carbon::now()->format('Y');
			return '<center><strong> COCEDER &copy  ' . $year . '</strong></center>';			
		});			
	}

	protected function menu () {

		Nova::mainMenu(fn($request) => [

			MenuItem::link(__('Mi Calendario'), NovaCalendar::pathToCalendar('mi-calendario')),

			MenuItem::externalLink('Estadísticas', '/stats/' . \Auth::user()->cdr_id)->openInNewTab(),

			MenuSection::make('Eventos', [
				MenuItem::resource(EventCall::class),
				MenuItem::resource(EventMeeting::class),
				MenuItem::resource(EventDemandantfollowup::class),
				MenuItem::resource(EventFamilycontact::class),
				MenuItem::resource(EventFamilyfollowup::class),				
				MenuItem::resource(EventOther::class),				
			])->icon('calendar')
				->collapsible(),

			MenuSection::make('Demandantes', [
				MenuItem::resource(Demandant::class),
				MenuItem::resource(Demandantfollowup::class),
				/*
					FABIO: Hidden. See the resource definition for info
					MenuItem::resource(Demandantdoc::class),
				 */
			])->icon('user')
				->collapsible(),

			MenuSection::make('Asentados', [
				MenuItem::resource(Family::class),
				MenuItem::resource(Familymember::class),
				MenuItem::resource(Familycontact::class),
				MenuItem::resource(Familyfollowup::class),
				MenuItem::resource(Familydoc::class),
				//MenuItem::resource(FamilyImpact::class),
			])->icon('user-group')
				->collapsible(),

			MenuSection::make('Recursos', [
				MenuItem::resource(House::class),
				MenuItem::resource(Business::class),
				MenuItem::resource(Land::class),
				MenuItem::resource(Job::class),
			])->icon('home')
				->collapsible(),

			MenuSection::make('CDR', [
				MenuItem::resource(Cdr::class)->name('Mi CDR'),
				MenuItem::resource(User::class),
				MenuItem::resource(Cdrnew::class),
				MenuItem::resource(Cdragreement::class),
			])->icon('cog')
				->collapsible(),

			MenuSection::make('Admin', [
				MenuItem::resource(Gender::class),
				MenuGroup::make('Cdrs', [
					MenuItem::resource(Cdr::class),
					MenuItem::resource(Cdrtype::class),
				]),
				MenuGroup::make('Regiones', [
					MenuItem::resource(Country::class),
					MenuItem::resource(Community::class),
					MenuItem::resource(Province::class),
					MenuItem::resource(Municipality::class),
					MenuItem::resource(Locality::class),
					MenuItem::resource(Region::class),
					MenuItem::resource(Zone::class),
				]),
				MenuGroup::make('Asentamiento', [
					MenuItem::resource(Settlementtype::class)->name('Tipos'),
					MenuItem::resource(Settlementstatus::class)->name('Estado'),
				]),
				MenuGroup::make('Recursos', [
					MenuItem::resource(Water::class),
					MenuItem::resource(Heating::class),
					MenuItem::resource(Stove::class),
					MenuItem::resource(Status::class),
					MenuItem::resource(Pricerange::class),
					MenuItem::resource(Arearange::class),
					MenuItem::resource(Form::class),
					MenuItem::resource(Jobform::class),
					MenuItem::resource(Sector::class),
					MenuItem::resource(Landtype::class),
					MenuItem::resource(Landuse::class),
					MenuItem::resource(Ownership::class),
					MenuItem::resource(Jobownership::class),
				]),
				MenuGroup::make('Familias asentadas', [
					MenuItem::resource(FamilyImpactScope::class),
					MenuItem::resource(FamilyImpactType::class),
					MenuItem::resource(FamilyImpact::class),
				]),

			])->icon('adjustments')
				->collapsible(),

		]);

	}

}
