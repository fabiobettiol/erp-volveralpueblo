<?php

namespace App\Nova;

use App\Nova\Filters\ByArea;
use App\Nova\Filters\ByAvailability;
use App\Nova\Filters\ByCdr;
use App\Nova\Filters\ByCommunity;
use App\Nova\Filters\ByForm;
use App\Nova\Filters\ByMunicipality;
use App\Nova\Filters\ByOwnership;
use App\Nova\Filters\ByPrice;
use App\Nova\Filters\ByProvince;
use App\Nova\Filters\ByType;
use App\Nova\Filters\ByUse;
use Ebess\AdvancedNovaMediaLibrary\Fields\Images;
use Eminiarts\Tabs\Tab;
use Eminiarts\Tabs\Tabs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;

class Land extends Resource {

	//use TabsOnEdit;

	public static function label() {
		return 'Tierras';
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
	public static $model = \App\Models\Land::class;

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
		'property_name',
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
			Boolean::make('Disp.', 'available'),
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
				                ->sortable(),
				            BelongsTo::make('Provincia', 'province', 'App\Nova\Province')
				                ->sortable()
				                ->hideFromIndex(),
				            BelongsTo::make('Municipio', 'municipality', 'App\Nova\Municipality')
				                ->hideFromIndex(),
			*/
			Text::make('Localidad', 'town'),
			Text::make('Código Postal', 'postcode')
				->rules('max:5')
				->hideFromIndex(),
			Text::make('Nombre del Paraje', 'property_name'),
			Text::make('Habitantes', 'population')
				->help('Número de habitantes')
				->hideFromIndex(),
			BelongsTo::make('Titularidad', 'ownership', 'App\Nova\Ownership')
				->help('Titularidad de la propiedad'),
			Textarea::make('Descripción', 'description')
				->help('Descripción general')
				->hideFromIndex()
				->alwaysShow(),
			Textarea::make('Requisitos', 'terms')
				->hideFromIndex()
				->alwaysShow(),
			Text::make('Polígono', 'polygon')
				->hideFromIndex(),
			Text::make('Parcela', 'plot')
				->hideFromIndex(),
			Text::make('Área', 'area'),
			BelongsTo::make('Rangos de Área', 'arearange', 'App\Nova\Arearange'),
			Text::make('Uso de la tierra', 'land')
				->help('Ejemplos: pastos, huerta, monte, etc')
				->hideFromIndex(),
			BelongsTo::make('Uso de la tierra (Categorías)', 'landuse', 'App\Nova\Landuse'),

			Text::make('Tipo de Tierra', 'soil')
				->help('Ejemplos: regadío, secano, urbanizable, etc')
				->hideFromIndex(),
			BelongsTo::make('Tipo de tierra (Categorías)', 'landtype', 'App\Nova\Landtype'),
			Tabs::make('Detalles', [
				Tab::make('Precios', [
					BelongsTo::make('Régimen', 'form', 'App\Nova\Form'),
					BelongsTo::make('Rango de precios', 'pricerange', 'App\Nova\Pricerange')
						->hideFromIndex(),
					Text::make('Precio Venta)', 'price_sale')
						->hideFromIndex(),
					Text::make('Precio Alq.)', 'price_rent')
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
			new ByCommunity,
			new ByProvince,
			new ByMunicipality,
			new ByUse,
			new ByType,
			new ByOwnership,
			new ByForm,
			new ByPrice,
			new ByArea,
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
