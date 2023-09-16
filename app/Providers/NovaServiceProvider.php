<?php

namespace App\Providers;
use App\Nova\Metrics\BusinessCount;
use App\Nova\Metrics\BusinessPerMonth;
use App\Nova\Metrics\CdrCount;
use App\Nova\Metrics\HouseCount;
use App\Nova\Metrics\HousePerMonth;
use App\Nova\Metrics\JobCount;
use App\Nova\Metrics\JobPerMonth;
use App\Nova\Metrics\LandCount;
use App\Nova\Metrics\LandPerMonth;
use Illuminate\Support\Facades\Gate;
use Laravel\Nova\Nova;
use Laravel\Nova\NovaApplicationServiceProvider;

class NovaServiceProvider extends NovaApplicationServiceProvider {
	/**
	 * Bootstrap any application services.
	 *
	 * @return void
	 */
	public function boot() {
		parent::boot();

		Nova::withBreadcrumbs();

		Nova::style('admin', public_path('assets/css/admin.css'));
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
			new \App\Nova\Dashboards\Main ,
		];
	}

	/**
	 * Get the tools that should be listed in the Nova sidebar.
	 *
	 * @return array
	 */
	public function tools() {
		return [
			// ...
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
}
