<?php

namespace App\Nova;

use Eminiarts\Tabs\Tab;
use Eminiarts\Tabs\Tabs;
use App\Models\Community;
use App\Nova\Filters\ByCdr;
use Laravel\Nova\Fields\ID;
use App\Models\Municipality;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Text;
use App\Nova\Filters\BySource;
use Eminiarts\Tabs\TabsOnEdit;
use App\Nova\Actions\LandExport;
use App\Nova\Filters\ByProvince;
use Laravel\Nova\Fields\Boolean;
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

class Land extends Resource {

	// use TabsOnEdit;

	public static function label() {
		return 'Tierras';
	}

	public static function indexQuery(NovaRequest $request, $query) {

		if ($request->user()->hasPermissionTo('view all lands')) {
			return $query;
		}

		if ($request->user()->hasPermissionTo('view own lands')) {
			$query->where('cdr_id', $request->user()->cdr_id);
		}

		if ($request->user()->hasPermissionTo('view province lands')) {
			$query->orWhere('province_id', $request->user()->cdr->province_id);
		}

		if ($request->user()->hasPermissionTo('view community lands')) {
			$query->orWhere('community_id', $request->user()->cdr->community_id);
		}

		return $query;
	}

	public function authorizedToUpdate(Request $request): bool {

		if ($request->user()->hasPermissionTo('edit lands')) {
			return true;
		}

		if ($request->user()->hasPermissionTo('edit own lands')) {
			return $this->cdr_id == $request->user()->cdr_id;
		}
	}

	public function authorizedToDelete(Request $request): bool {

		if ($request->user()->hasPermissionTo('delete lands')) {
			return true;
		}

		return $request->user()->hasPermissionTo('delete own lands');
	}

	public function authorizedToRestore(Request $request): bool {

		if ($request->user()->hasPermissionTo('restore lands')) {
			return true;
		}

		return $request->user()->hasPermissionTo('restore own lands');
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
		'bidder_name'
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
				->display(function ($community) {
					return $community->acronym;
				})->sortable()
				->onlyOnIndex(),

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

			// - Municipality: Show abbreviated name when not on index view
			BelongsTo::make('Municipio', 'municipality', 'App\Nova\Municipality')
				->sortable()
				->onlyOnIndex(),
				
			Text::make('Localidad', 'town')
				->sortable()
				->rules('required', 'max:100'),
			Text::make('Código Postal', 'postcode')
				->rules('required', 'min:5', 'max:5')
				->hideFromIndex(),
			Text::make('Nombre del Paraje', 'property_name')
				->sortable()
				->rules('max:255'),
			Text::make('Habitantes', 'population')
				->help('Número de habitantes')
				->hideFromIndex(),
			BelongsTo::make('Titularidad', 'ownership', 'App\Nova\Ownership')
				->filterable()
				->hideFromIndex()
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
			Text::make('Área', 'area')
				->sortable(),
			BelongsTo::make('Rangos de Área', 'arearange', 'App\Nova\Arearange')
				->sortable()
				->filterable(),
			Text::make('Uso de la tierra', 'land')
				->help('Ejemplos: pastos, huerta, monte, etc')
				->filterable()
				->hideFromIndex(),
			BelongsTo::make('Uso de la tierra (Categorías)', 'landuse', 'App\Nova\Landuse')
				->sortable(),
			Text::make('Tipo de Tierra', 'soil')
				->help('Ejemplos: regadío, secano, urbanizable, etc')
				->hideFromIndex(),
			BelongsTo::make('Tipo de tierra (Categorías)', 'landtype', 'App\Nova\Landtype')
				->sortable()
				->filterable(),
			Tabs::make('Detalles', [
				Tab::make('Precios', [
					BelongsTo::make('Régimen', 'form', 'App\Nova\Form')
						->sortable()
						->filterable(),
					BelongsTo::make('Rango de precios', 'pricerange', 'App\Nova\Pricerange')
						->filterable()
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
						->hideFromIndex()
						->canSee(function ($request) {
							return $request->user()->hasPermissionTo('administrator') ||
							$request->user()->cdr_id == $this->cdr_id;
						}),
					Text::make('Teléfono', 'bidder_phone')
						->hideFromIndex()
						->canSee(function ($request) {
							return $request->user()->hasPermissionTo('administrator') ||
							$request->user()->cdr_id == $this->cdr_id;
						}),
					Text::make('Email', 'bidder_email')
						->hideFromIndex()
						->canSee(function ($request) {
							return $request->user()->hasPermissionTo('administrator') ||
							$request->user()->cdr_id == $this->cdr_id;
						}),
					Textarea::make('Comentarios', 'bidder_comments')
						->hideFromIndex()
						->alwaysShow()
						->canSee(function ($request) {
							return $request->user()->hasPermissionTo('administrator') ||
							$request->user()->cdr_id == $this->cdr_id;
						}),
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
		
		return [
			new BySource,
			new ByAvailability,
			new ByCdr,
			new ByCommunity,
			new ByProvince,
			new ByMunicipality
		];
		
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

			(new LandExport),
		];
	}
}
