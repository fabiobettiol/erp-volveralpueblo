<?php

namespace App\Nova;

use App\Nova\Filters\ByAvailability;
use App\Nova\Filters\ByCdr;
use App\Nova\Filters\ByCommunity;
use App\Nova\Filters\ByMunicipality;
use App\Nova\Filters\ByProvince;
use App\Nova\Filters\BySector;
use Eminiarts\Tabs\Tab;
use Eminiarts\Tabs\Tabs;
use Eminiarts\Tabs\Traits\HasTabs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;

class Job extends Resource {

	use HasTabs;

	public static function label() {
		return 'Trabajos';
	}

	public static function indexQuery(NovaRequest $request, $query) {
		if ($request->user()->is_admin) {
			return $query;
		} else {
			return $query->where('cdr_id', $request->user()->cdr_id);
		}
	}

	public static $group = 'Recursos';

	/**
	 * The model the resource corresponds to.
	 *
	 * @var string
	 */
	public static $model = \App\Models\Job::class;

	/**
	 * The single value that should be used to represent the resource when being displayed.
	 *
	 * @var string
	 */
	public static $title = 'referencia';

	/**
	 * The columns that should be searched.
	 *
	 * @var array
	 */
	public static $search = [
		'reference',
		'town',
		'property_name',
		'position',
	];

	/**
	 * Get the fields displayed by the resource.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return array
	 */
	public function fields(Request $request) {
		return [
			// ID::make(__('ID'), 'id')->sortable(),
			Boolean::make('Disp.', 'available')
				->hideWhenCreating(),
			Text::make('Referencia', 'reference')
				->hideWhenCreating()
				->help('<a target="blank" href="/mapa/' . $this->reference . '">Ver en el mapa</a>')
				->readonly(),
			Boolean::make('Mapa', 'mapinfo')
				->hideWhenCreating()
				->hideFromIndex(),
			BelongsTo::make('CDR', 'cdr', 'App\Nova\Cdr')
				->sortable()
				->canSee(function ($request) {
					return $request->user()->is_admin;
				}),
			Date::make('Fecha', 'created_at')
				->sortable()
				->canSee(function ($request) {
					return $request->user()->is_admin;
				}),

			// NovaBelongsToDepend::make('Comunidad', 'Community', 'App\Nova\Community')
			// 	->options(Community::all())
			// 	->rules('required')
			// 	->hideFromIndex(),

			// NovaBelongsToDepend::make('CCAA', 'Community', 'App\Nova\Community')
			// 	->options(Community::all())
			// 	->rules('required')
			// 	->display(function ($community) {
			// 		return $community->acronym;
			// 	})->onlyOnIndex(),

			// NovaBelongsToDepend::make('Provincia', 'Province', 'App\Nova\Province')
			// 	->optionsResolve(function ($community) {
			// 		return $community->provinces;
			// 	})
			// 	->dependsOn('Community')
			// 	->required()
			// 	->hideFromIndex(),

			// NovaBelongsToDepend::make('Prov', 'Province', 'App\Nova\Province')
			// 	->optionsResolve(function ($community) {
			// 		return $community->provinces;
			// 	})
			// 	->dependsOn('Community')
			// 	->required()
			// 	->display(function ($community) {
			// 		return $community->acronym;
			// 	})->onlyOnIndex(),

			// NovaBelongsToDepend::make('Municipio', 'Municipality', 'App\Nova\Municipality')
			// 	->optionsResolve(function ($province) {
			// 		return Municipality::whereHas('province', function ($q) use ($province) {
			// 			$q->where('province_id', $province->id);
			// 		})->get();
			// 	})
			// 	->dependsOn('Province')
			// 	->rules('required'),

			/*
				            BelongsTo::make('Comunidad', 'community', 'App\Nova\Community')
				                ->help('Comunidad Autónoma de la Oferta')
				                ->sortable(),
				            BelongsTo::make('Provincia', 'province', 'App\Nova\Province')
				                ->sortable(),
				            BelongsTo::make('Municipio', 'municipality', 'App\Nova\Municipality')
				                ->hideFromIndex(),
			*/
			Text::make('Localidad', 'town')
				->hideFromIndex(),
			Text::make('Código Postal', 'postcode')
				->rules('max:5')
				->hideFromIndex(),
			Text::make('Habitantes', 'population')
				->hideFromIndex(),

			Tabs::make('Detalles', [
				Tab::make('Oferta', [
					BelongsTo::make('Titularidad', 'jobownership', 'App\Nova\Jobownership')
						->hideFromIndex(),
					BelongsTo::make('Sector', 'sector', 'App\Nova\Sector'),
					Textarea::make('Descripción', 'description')
						->hideFromIndex()
						->alwaysShow(),
					BelongsTo::make('Régimen laboral', 'jobform', 'App\Nova\Jobform'),
					Text::make('Puesto de trabajo', 'position'),
					Textarea::make('Requerimientos', 'requirements')
						->hideFromIndex()
						->alwaysShow(),
					Date::make('Desde', 'date_from')
						->hideFromIndex(),
					Date::make('Hasta', 'date_to'),
				]),

				Tab::make('Ofertante', [
					Text::make('Nombre', 'bidder_name')
						->hideFromIndex(),
					Text::make('Teléfono', 'bidder_phone')
						->hideFromIndex(),
					Text::make('Email', 'bidder_email')
						->hideFromIndex(),
					Text::make('Email para CV', 'cv_email')
						->hideFromIndex(),
					Textarea::make('Comentarios', 'bidder_comments')
						->hideFromIndex()
						->alwaysShow(),
				]),
				Tab::make('Mapa', [
					Boolean::make('Mapa', 'mapinfo')
						->hideFromIndex()
						->canSee(function ($request) {
							return $request->user()->is_admin;
						})->hideWhenCreating(),
					Text::make('Latitud', 'lat')
						->hideFromIndex()
						->canSee(function ($request) {
							return $request->user()->is_admin;
						})->hideWhenCreating(),
					Text::make('Longitud', 'lng')
						->hideFromIndex()
						->canSee(function ($request) {
							return $request->user()->is_admin;
						})->hideWhenCreating(),
				]),

				Tab::make('Comentarios', [
					Textarea::make('Comentarios', 'comments')
						->alwaysShow(),
				]),
			])->withToolbar()
				->defaultSearch(true),

		];
	}

	/**
	 * Get the cards available for the request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return array
	 */
	public function cards(Request $request) {
		return [];
	}

	/**
	 * Get the filters available for the resource.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return array
	 */
	public function filters(Request $request) {
		$user = Auth::user();

		$retorno = [
			new ByAvailability,
			new ByCommunity,
			new ByProvince,
			new ByMunicipality,
			new BySector,
		];

		if ($user->is_admin) {
			array_unshift($retorno, new ByCdr);
		}

		return $retorno;
	}

	/**
	 * Get the lenses available for the resource.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return array
	 */
	public function lenses(Request $request) {
		return [];
	}

	/**
	 * Get the actions available for the resource.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return array
	 */
	public function actions(Request $request) {
		return [
			// (new DownloadExcel)
			// 	->withHeadings()
			// 	->allFields(),
		];
	}
}
