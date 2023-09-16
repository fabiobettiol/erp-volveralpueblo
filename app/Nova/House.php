<?php

namespace App\Nova;

use App\Nova\Filters\ByAvailability;
use App\Nova\Filters\ByBusiness;
use App\Nova\Filters\ByCdr;
use App\Nova\Filters\ByCommunity;
use App\Nova\Filters\ByCuadras;
use App\Nova\Filters\ByForm;
use App\Nova\Filters\ByLand;
use App\Nova\Filters\ByMunicipality;
use App\Nova\Filters\ByPatio;
use App\Nova\Filters\ByPrice;
use App\Nova\Filters\ByProvince;
use App\Nova\Filters\BySource;
use App\Nova\Filters\ByStatus;
use Ebess\AdvancedNovaMediaLibrary\Fields\Images;
use Eminiarts\Tabs\Tab;
use Eminiarts\Tabs\Tabs;
use Eminiarts\Tabs\Traits\HasTabs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;

class House extends Resource {

	use HasTabs;

	public static $tableStyle = 'tight';

	public static function label() {
		return 'Viviendas';
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
	public static $model = \App\Models\House::class;

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
		'contact',
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

			Text::make('Código Postal', 'postcode')
				->rules('max:5')
				->hideFromIndex(),
			Text::make('Localidad', 'town')
				->help('Nombre de la localidad o pueblo'),
			Text::make('Habitantes', 'population')
				->help('Número de habitantes del poblado')
				->hideFromIndex(),
			Textarea::make('Dirección', 'address')
				->alwaysShow(),
			Text::make('Nombre', 'property_name')
				->help('Nombre de la propiedad')
				->hideFromIndex(),

			Tabs::make('Detalles', [
				Tab::make('Distribución', [
					Textarea::make('Descripción', 'description')
						->hideFromIndex()
						->alwaysShow(),
					Number::make('Plantas', 'stories'),
					Text::make('Descripción (Plantas)', 'stories_detail')
						->help('Información adicional sobre Plantas')
						->hideFromIndex(),
					Number::make('Dorm.', 'bedrooms')
						->sortable(),
					Text::make('Descripción', 'bedrooms_detail')
						->help('Información adicional sobre Dormitorios')
						->hideFromIndex(),
					Number::make('Baños', 'bathrooms'),
					Number::make('Estancias', 'total_rooms')
						->help('Número de estacias o habitaciones totales')
						->hideFromIndex(),
				]),
				Tab::make('Estado', [
					BelongsTo::make('Estados', 'status', 'App\Nova\Status')
						->hideFromIndex(),
					Boolean::make('Reparaciones', 'repairs_needed')
						->hideFromIndex(),
					Text::make('Descripción', 'repairs_detail')
						->help('Describir las reparaciones necesarias')
						->hideFromIndex(),
					Boolean::make('Habitable', 'habitable')
						->hideFromIndex(),
					Text::make('Descripción', 'habitable_detail')
						->help('Describir el estado de habitabilidad')
						->hideFromIndex(),
				]),
				Tab::make('Servicios', [
					BelongsTo::make('Agua', 'waters', 'App\Nova\Water')
						->hideFromIndex(),
					Text::make('Descripción', 'water_detail')
						->help('Detalles sobre el servicio de agua')
						->hideFromIndex(),
					BelongsTo::make('Calefacción', 'heating', 'App\Nova\Heating')
						->hideFromIndex(),
					Text::make('Descripción', 'heating_detail')
						->help('Detalles sobre el servicio de calefacción')
						->hideFromIndex(),
					BelongsTo::make('Cocina', 'stove', 'App\Nova\Stove')
						->hideFromIndex(),
					Text::make('Descripción', 'stove_detail')
						->help('Detalles sobre la cocina')
						->hideFromIndex(),
				]),
				Tab::make('Extras', [
					Boolean::make('Patio', 'courtyard')
						->hideFromIndex(),
					Text::make('Descripción', 'courtyard_detail')
						->help('Detalles sobre Patios, Terraza, etc.')
						->hideFromIndex(),
					Boolean::make('Edificaciones auxiliares', 'stables')
						->hideFromIndex(),
					Text::make('Descripción', 'stables_detail')
						->help('Detalles sobre las edificaciones auxiliares')
						->hideFromIndex(),

					Boolean::make('Tierras', 'lands')
						->hideFromIndex(),
					Text::make('Descripción', 'lands_detail')
						->help('Detalles sobre terrenos, etc.')
						->hideFromIndex(),
					Boolean::make('Adaptable para negocios', 'tobusiness')
						->hideFromIndex(),
					Text::make('Descripción', 'tobusiness_detail')
						->help('Detalles sobre la adaptabilidad')
						->hideFromIndex(),

				]),
				Tab::make('Área', [
					BelongsTo::make('Superficie', 'area', 'App\Nova\Arearange')
						->help('Rango de áreas')
						->hideFromIndex(),
					Text::make('Descripción', 'house_area_detail')
						->help('Detalles sobre la superficie')
						->hideFromIndex(),
					Text::make('Área Parcela', 'plot_area')
						->help('Superficie total de la parcela (M2)')
						->hideFromIndex(),
					Text::make('Descripción', 'plot_area_detail')
						->help('Detalles sobre el área de la parcels')
						->hideFromIndex(),
				]),
				Tab::make('Precios', [
					BelongsTo::make('Titularidad', 'ownership', 'App\Nova\Ownership')
						->help('Titularidad de la propiedad'),
					BelongsTo::make('Régimen', 'form', 'App\Nova\Form'),
					BelongsTo::make('Precio', 'pricerange', 'App\Nova\Pricerange'),
					Text::make('Precio Venta', 'price_sale')
						->hideFromIndex(),
					Text::make('Precio Alq.', 'price_rent')
						->hideFromIndex(),
					Text::make('Descripción', 'form_detail')
						->help('Detalles')
						->hideFromIndex(),
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

				Tab::make('Contacto', [
					Text::make('Contacto', 'contact')
						->hideFromIndex(),
					Text::make('Teléfono', 'phone')
						->hideFromIndex(),
					Text::make('Email')
						->hideFromIndex(),
				]),
				Tab::make('Comentarios', [
					Textarea::make('Comentarios', 'comments')
						->alwaysShow(),
				]),
			])->withToolbar()
				->defaultSearch(true),

			Images::make('Fotos')->hideFromIndex(),
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
			new BySource,
			new ByAvailability,
			new ByPatio,
			new ByCuadras,
			new ByCommunity,
			new ByProvince,
			new ByMunicipality,
			new ByForm,
			new ByPrice,
			new ByStatus,
			new ByLand,
			new ByBusiness,
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