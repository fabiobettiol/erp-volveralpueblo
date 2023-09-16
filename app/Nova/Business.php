<?php

namespace App\Nova;

use App\Nova\Filters\ByAvailability;
use App\Nova\Filters\ByCdr;
use App\Nova\Filters\ByCommunity;
use App\Nova\Filters\ByForm;
use App\Nova\Filters\ByMunicipality;
use App\Nova\Filters\ByOwnership;
use App\Nova\Filters\ByPrice;
use App\Nova\Filters\ByProvince;
use App\Nova\Filters\BySector;
use App\Nova\Filters\BySource;
use Ebess\AdvancedNovaMediaLibrary\Fields\Images;
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

class Business extends Resource {

	use HasTabs;

	public static function label() {
		return 'Negocios';
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
	public static $model = \App\Models\Business::class;

	/**
	 * The single value that should be used to represent the resource when being displayed.
	 *
	 * @var string
	 */
	public static $title = 'reference';

	/**
	 * The columns that should be searched.
	 *
	 * @var array
	 */
	public static $search = [
		'reference',
		'town',
		'property_type',
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
				->hideFromIndex()
				->help('<a target="blank" href="/mapa/' . $this->reference . '">Ver en el mapa</a>')
				->readonly(),
			Boolean::make('Mapa', 'mapinfo')
				->hideWhenCreating(),
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

			Text::make('Localidad', 'town'),
			Text::make('Código Postal', 'postcode')
				->rules('max:5')
				->hideFromIndex(),
			Text::make('Habitantes', 'population')
				->hideFromIndex(),
			Text::make('Tipo de Negocio', 'property_type')
				->help('Subsector'),
			BelongsTo::make('Sector', 'sector', 'App\Nova\Sector'),
			BelongsTo::make('Titularidad', 'ownership', 'App\Nova\Ownership')
				->hideFromIndex(),
			Textarea::make('Dirección del Negocio', 'address')
				->help('Dirección del negocio')
				->hideFromIndex()
				->alwaysShow(),
			Textarea::make('Descripción', 'description')
				->help('Descripción del negocio')
				->hideFromIndex()
				->alwaysShow(),
			Textarea::make('Requisitos', 'terms')
				->hideFromIndex()
				->alwaysShow(),
			Text::make('Plazos', 'deadlines')
				->help('Tiempo estimado para presentar solicitudes'),
			Tabs::make('Detalles', [
				Tab::make('Precios', [
					BelongsTo::make('Régimen', 'form', 'App\Nova\Form'),
					BelongsTo::make('Rango de precios', 'pricerange', 'App\Nova\Pricerange')
						->hideFromIndex(),
					Text::make('Precio Venta', 'price_sale')
						->hideFromIndex(),
					Text::make('Precio Alq.', 'price_rent')
						->hideFromIndex(),
					Text::make('Descripción', 'form_detail')
						->help('Detalles')
						->hideFromIndex(),
				]),
				Tab::make('Ofertante', [
					Text::make('Nombre', 'bidder_name')
						->hideFromIndex(),
					Text::make('Teléfono', 'bidder_phone')
						->hideFromIndex(),
					Text::make('Email', 'bidder_email')
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
			Images::make('Fotos')
				->hideFromIndex(),
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
			new BySource,
			new ByCommunity,
			new ByProvince,
			new ByMunicipality,
			new BySector,
			new ByOwnership,
			new ByPrice,
			new ByForm,
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
