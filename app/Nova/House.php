<?php

namespace App\Nova;

use Eminiarts\Tabs\Tab;
use Eminiarts\Tabs\Tabs;
use App\Models\Community;
use App\Nova\Filters\ByCdr;
use Illuminate\Support\Str;
use Laravel\Nova\Fields\ID;
use App\Models\Municipality;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Text;
use App\Nova\Filters\BySource;
use Eminiarts\Tabs\TabsOnEdit;
use Laravel\Nova\Fields\Number;
use App\Nova\Filters\ByProvince;
use Laravel\Nova\Fields\Boolean;
use App\Nova\Actions\HouseExport;
use App\Nova\Filters\ByCommunity;
use Laravel\Nova\Fields\FormData;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\BelongsTo;
use App\Nova\Filters\ByAvailability;
use App\Nova\Filters\ByMunicipality;
use Illuminate\Support\Facades\Auth;
use Laravel\Nova\Http\Requests\NovaRequest;
use Ebess\AdvancedNovaMediaLibrary\Fields\Images;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Maatwebsite\LaravelNovaExcel\Actions\DownloadExcel;

class House extends Resource {
	// use TabsOnEdit;

	public static function label() {
		return 'Viviendas';
	}

	/*public static function indexQuery(NovaRequest $request, $query) {

		if ($request->user()->hasPermissionTo('view all houses')) {
			return $query;
		}

		if ($request->user()->hasPermissionTo('view own houses')) {
			$query->where('cdr_id', $request->user()->cdr_id);
		}

		if ($request->user()->hasPermissionTo('view province houses')) {
			$query->orWhere('province_id', $request->user()->cdr->province_id);
		}

		if ($request->user()->hasPermissionTo('view community houses')) {
			$query->orWhere('community_id', $request->user()->cdr->community_id);
		}

		return $query;
	}

	public function authorizedToUpdate(Request $request): bool {

		if ($request->user()->hasPermissionTo('edit houses')) {
			return true;
		}

		if ($request->user()->hasPermissionTo('edit own houses')) {
			return $this->cdr_id == $request->user()->cdr_id;
		}
	}

	public function authorizedToDelete(Request $request): bool {

		if ($request->user()->hasPermissionTo('delete houses')) {
			return true;
		}

		return $request->user()->hasPermissionTo('delete own houses');
	}

	public function authorizedToRestore(Request $request): bool {

		if ($request->user()->hasPermissionTo('restore houses')) {
			return true;
		}

		return $request->user()->hasPermissionTo('restore own houses');
	}*/

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
		'comments'
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
			Boolean::make('Disponible', 'available')
				->hideFromIndex()
				->hideWhenCreating(),
			Text::make('Referencia', 'reference')
				->hideWhenCreating()
				->help('<a target="blank" href="/mapa/' . $this->reference . '">Ver en el mapa</a>')
				->readonly(),	
			BelongsTo::make('CDR', 'cdr', 'App\Nova\Cdr')
				->sortable()
				->canSee(function ($request) {
					return $request->user()->hasPermissionTo('administrator');
				}),
			Date::make('Fecha', 'created_at')
				->onlyOnIndex()
				->filterable()
				->sortable(),

			// - Community: Show the full name when not on index view
			BelongsTo::make('Comunidad', 'community', 'App\Nova\Community')
				->hideFromIndex(),
			
			// - Community: Show acronym on index view
			BelongsTo::make('Comunidad', 'community', 'App\Nova\Community')
				->sortable()
				->display(function ($community) {
					return $community->acronym;
				})->onlyOnIndex(),		

			// - Province: Show full name when not on index view
			BelongsTo::make('Provincia', 'province', 'App\Nova\Province')
				->dependsOn(['community'], function (BelongsTo $field, NovaRequest $request, FormData $formData) {
					$field->relatableQueryUsing(function (NovaRequest $request, Builder $query) use ($formData) {
						$query->when($formData->community, function ($query) use ($formData) {
							$query->where('community_id', $formData->community);
						});
					});
				})->hideFromIndex(),

			// - Province: Show abbreviated name on index view
			BelongsTo::make('Provincia', 'province', 'App\Nova\Province')
				->display(function ($province) {
					return ( strlen($province->name) <= 10 ) ? $province->name : substr($province->name,0,10).'...';
				})->sortable()
				->onlyOnIndex(),

			// - Municipality: Show full name when not on index view
			BelongsTo::make('Municipio', 'municipality', 'App\Nova\Municipality')
				->dependsOn(['province'], function (BelongsTo $field, NovaRequest $request, FormData $formData) {
					$field->relatableQueryUsing(function (NovaRequest $request, Builder $query) use ($formData) {
						$query->when($formData->province, function ($query) use ($formData) {
							$query->where('province_id', $formData->province);
						});
					});
				})->hideFromIndex(),

			// - Locality: Show full name when not on index view
			BelongsTo::make('Localidad', 'locality', 'App\Nova\Locality')
				->dependsOn(['municipality'], function (BelongsTo $field, NovaRequest $request, FormData $formData) {
					$field->relatableQueryUsing(function (NovaRequest $request, Builder $query) use ($formData) {
						$query->when($formData->municipality, function ($query) use ($formData) {
							$query->where('municipality_id', $formData->municipality);
						});
					});
				})->hideFromIndex(),

			// - Municipality: Show abbreviated name when not on index view
			BelongsTo::make('Municipio', 'municipality', 'App\Nova\Municipality')
				->display(function ($municipality) {
					return ( strlen($municipality->name) <= 10 ) ? $municipality->name : Str::substr($municipality->name, 0, 10);
				})->sortable()
				->onlyOnIndex(),
			Text::make('Código Postal', 'postcode')
				->rules('required', 'min:5', 'max:5')
				->hideFromIndex(),
			Text::make('Localidad', 'town')
				->sortable()
				->rules('required', 'max:100')
				->help('Nombre de la localidad o pueblo'),
			Text::make('Habitantes', 'population')
				->help('Número de habitantes del poblado')
				->hideFromIndex(),
			Textarea::make('Dirección', 'address')
				->rules('required')
				->alwaysShow(),
			Text::make('Nombre', 'property_name')
				->rules('max:100')
				->help('Nombre de la propiedad')
				->hideFromIndex(),
			Tabs::make('Detalles', [
				Tab::make('Distribución', [
					Textarea::make('Descripción', 'description')
						->hideFromIndex()
						->alwaysShow(),
					Number::make('Plantas', 'stories')
						->sortable(),
					Text::make('Descripción (Plantas)', 'stories_detail')
						->help('Información adicional sobre Plantas')
						->hideFromIndex(),
					Number::make('Dorm.', 'bedrooms')
						->sortable(),
					Text::make('Descripción', 'bedrooms_detail')
						->help('Información adicional sobre Dormitorios')
						->hideFromIndex(),
					Number::make('Baños', 'bathrooms')
						->sortable(),
					Number::make('Estancias', 'total_rooms')
						->help('Número de estacias o habitaciones totales')
						->hideFromIndex(),
				]),
				Tab::make('Estado', [
					BelongsTo::make('Estado', 'status', 'App\Nova\Status')
						->filterable()
						->hideFromIndex(),
					Boolean::make('Reparaciones', 'repairs_needed')
						->hideFromIndex(),
					Textarea::make('Descripción', 'repairs_detail')
						->rules('max:255')
						->rows(2)
						->help('Describir las reparaciones necesarias')
						->hideFromIndex(),
					Boolean::make('Habitable', 'habitable')
						->hideFromIndex(),
					Textarea::make('Descripción', 'habitable_detail')
						->rules('max:255')
						->rows(2)					
						->help('Describir el estado de habitabilidad')
						->hideFromIndex(),
				]),
				Tab::make('Servicios', [
					BelongsTo::make('Agua', 'waters', 'App\Nova\Water')
						->hideFromIndex(),
					Textarea::make('Descripción', 'water_detail')
						->rules('max:255')
						->rows(2)						
						->help('Detalles sobre el servicio de agua')
						->hideFromIndex(),
					BelongsTo::make('Calefacción', 'heating', 'App\Nova\Heating')
						->hideFromIndex(),
					Textarea::make('Descripción', 'heating_detail')
						->rules('max:255')
						->rows(2)						
						->help('Detalles sobre el servicio de calefacción')
						->hideFromIndex(),
					BelongsTo::make('Cocina', 'stove', 'App\Nova\Stove')
						->hideFromIndex(),
					Textarea::make('Descripción', 'stove_detail')
						->rules('max:255')
						->rows(2)						
						->help('Detalles sobre la cocina')
						->hideFromIndex(),
				]),
				Tab::make('Extras', [
					Boolean::make('Patio', 'courtyard')
						->filterable()
						->hideFromIndex(),
					Text::make('Descripción', 'courtyard_detail')
						->help('Detalles sobre Patios, Terraza, etc.')
						->hideFromIndex(),
					Boolean::make('Edificaciones auxiliares', 'stables')
						->filterable()
						->hideFromIndex(),
					Text::make('Descripción', 'stables_detail')
						->help('Detalles sobre las edificaciones auxiliares')
						->hideFromIndex(),
					Boolean::make('Tierras', 'lands')
						->filterable()
						->hideFromIndex(),
					Text::make('Descripción', 'lands_detail')
						->help('Detalles sobre terrenos, etc.')
						->hideFromIndex(),
					Boolean::make('Adaptable para negocios', 'tobusiness')
						->filterable()
						->hideFromIndex(),
					Text::make('Descripción', 'tobusiness_detail')
						->help('Detalles sobre la adaptabilidad')
						->hideFromIndex(),

				]),
				Tab::make('Área', [
					BelongsTo::make('Superficie', 'area', 'App\Nova\Arearange')
						->help('Rango de áreas')
						->hideFromIndex(),
					Textarea::make('Descripción', 'house_area_detail')
						->rules('max:255')
						->rows(2)						
						->help('Detalles sobre la superficie')
						->hideFromIndex(),
					Text::make('Área Parcela', 'plot_area')
						->help('Superficie total de la parcela (M2)')
						->hideFromIndex(),
					Textarea::make('Descripción', 'plot_area_detail')
						->rules('max:255')
						->rows(2)						
						->help('Detalles sobre el área de la parcela')
						->hideFromIndex(),
				]),
				Tab::make('Precios', [
					BelongsTo::make('Titularidad', 'ownership', 'App\Nova\Ownership')
						->sortable()
						->help('Titularidad de la propiedad'),
					BelongsTo::make('Régimen', 'form', 'App\Nova\Form')
						->sortable()
						->filterable(),
					BelongsTo::make('Rango de precios', 'pricerange', 'App\Nova\Pricerange')
						->sortable()
						->filterable(),
					Text::make('Precio Venta', 'price_sale')
						->hideFromIndex(),
					Text::make('Precio Alq.', 'price_rent')
						->hideFromIndex(),
					Textarea::make('Descripción', 'form_detail')
						->rules('max:255')
						->rows(2)						
						->help('Detalles')
						->hideFromIndex(),
				]),
				Tab::make('Mapa', [
					Boolean::make('Mapa', 'mapinfo')
						->hideFromIndex()
						->hideWhenCreating(),
					Text::make('Latitud', 'lat')
						->hideFromIndex()
						->hideWhenCreating(),
					Text::make('Longitud', 'lng')
						->hideFromIndex()
						->hideWhenCreating(),
				]),

				Tab::make('Contacto', [
					Text::make('Contacto', 'contact')
						->hideFromIndex()
						->canSee(function ($request) {
							return $request->user()->hasPermissionTo('administrator') ||
							$request->user()->cdr_id == $this->cdr_id;
						}),
					Text::make('Teléfono', 'phone')
						->hideFromIndex()
						->canSee(function ($request) {
							return $request->user()->hasPermissionTo('administrator') ||
							$request->user()->cdr_id == $this->cdr_id;
						}),
					Text::make('Email')
						->hideFromIndex()
						->canSee(function ($request) {
							return $request->user()->hasPermissionTo('administrator') ||
							$request->user()->cdr_id == $this->cdr_id;
						}),
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
			new ByCdr,
			new ByCommunity,
			new ByProvince,
			new ByMunicipality
		];

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
			(new DownloadExcel)
				->withHeadings()
				->allFields(),

			(new HouseExport),
		];
	}
}
