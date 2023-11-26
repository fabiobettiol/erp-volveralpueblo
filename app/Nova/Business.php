<?php

namespace App\Nova;

use Eminiarts\Tabs\Tab;
use Eminiarts\Tabs\Tabs;
use App\Models\Community;
use Laravel\Nova\Fields\ID;
use App\Models\Municipality;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Text;
use App\Nova\Filters\BySource;
use Eminiarts\Tabs\TabsOnEdit;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\FormData;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\BelongsTo;
use Illuminate\Support\Facades\Auth;
use App\Nova\Actions\BusinessExport;
use Laravel\Nova\Http\Requests\NovaRequest;
use Ebess\AdvancedNovaMediaLibrary\Fields\Images;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Maatwebsite\LaravelNovaExcel\Actions\DownloadExcel;

class Business extends Resource {
	// use TabsOnEdit;

	public static function label() {
		return 'Negocios';
	}

	public static function indexQuery(NovaRequest $request, $query) {

		if ($request->user()->is_admin) {
			return $query;
		} else {
			$query->where('cdr_id', $request->user()->cdr_id);

			if ($request->user()->is_collaborator) {
				$query->orWhere('province_id', $request->user()->cdr->province->id);
			}

			return $query;
		}
	}

	public function authorizedToUpdate(Request $request): bool {

		if (!$request->user()->is_admin && $this->cdr_id != $request->user()->cdr_id) {
			return false;
		} else {
			return true;
		}
	}

	public function authorizedToDelete(Request $request): bool {

		if (!$request->user()->is_admin && $this->cdr_id != $request->user()->cdr_id) {
			return false;
		} else {
			return true;
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
		'description',
		'bidder_name',
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
				->filterable()
				->hideFromIndex()	
				->hideWhenCreating(),
			Text::make('Referencia', 'reference')
				->hideWhenCreating()
				->help('<a target="blank" href="/mapa/' . $this->reference . '">Ver en el mapa</a>')
				->readonly(),
			BelongsTo::make('CDR', 'cdr', 'App\Nova\Cdr')
				->filterable()
				->sortable()
				->canSee(function ($request) {
					return $request->user()->is_admin;
				}),
			Date::make('Fecha', 'created_at')
				->sortable()
				->canSee(function ($request) {
					return $request->user()->is_admin;
				}),

			// - Community: Show the full name when not on index view
			BelongsTo::make('Comunidad', 'community', 'App\Nova\Community')
				->filterable()
				->hideFromIndex(),
			
			// - Community: Show acronym on index view
			BelongsTo::make('Comunidad', 'community', 'App\Nova\Community')
				->filterable()
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
				})->filterable()
				->hideFromIndex(),

			// - Province: Show abbreviated name on index view
			BelongsTo::make('Provincia', 'province', 'App\Nova\Province')
				->filterable()
				->display(function ($province) {
					return ( strlen($province->name) <= 10 ) ? $province->name : substr($province->name,0,10).'...';
				})->filterable()
				->onlyOnIndex(),

			// - Municipality: Show full name when not on index view
			BelongsTo::make('Municipio', 'municipality', 'App\Nova\Municipality')
				->dependsOn(['province'], function (BelongsTo $field, NovaRequest $request, FormData $formData) {
					$field->relatableQueryUsing(function (NovaRequest $request, Builder $query) use ($formData) {
						$query->when($formData->province, function ($query) use ($formData) {
							$query->where('province_id', $formData->province);
						});
					});
				})->filterable()
				->hideFromIndex(),

			// - Municipality: Show abbreviated name when not on index view
			BelongsTo::make('Municipio', 'municipality', 'App\Nova\Municipality')
				->filterable()
				->display(function ($municipality) {
					return ( strlen($municipality->name) <= 10 ) ? $municipality->name : htmlspecialchars(substr($municipality->name,0,10)).'...';
				})->onlyOnIndex(),

			Text::make('Localidad', 'town')
				->rules('required', 'max:100'),
			Text::make('Código Postal', 'postcode')
				->rules('required', 'min:5','max:5')
				->hideFromIndex(),
			Text::make('Habitantes', 'population')
				->hideFromIndex(),
			BelongsTo::make('Sector', 'sector', 'App\Nova\Sector')
				->rules('required', 'max:5')
				->filterable(),
			Textarea::make('Tipo de Negocio', 'property_type')
				->rows(2)
				->rules('required', 'max:255')
				->help('Subsector'),				
			BelongsTo::make('Titularidad', 'ownership', 'App\Nova\Ownership')
				->filterable()
				->hideFromIndex(),
			Textarea::make('Dirección del Negocio', 'address')
				->rules('required')
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
				->rules('max:100')
				->help('Tiempo estimado para presentar solicitudes'),
			Tabs::make('Detalles', [
				Tab::make('Precios', [
					BelongsTo::make('Régimen', 'form', 'App\Nova\Form')
						->filterable(),
					BelongsTo::make('Rango de precios', 'pricerange', 'App\Nova\Pricerange')
						->filterable()
						->hideFromIndex(),
					Text::make('Precio Venta', 'price_sale')
						->hideFromIndex(),
					Text::make('Precio Alq.', 'price_rent')
						->hideFromIndex(),
					Textarea::make('Descripción', 'form_detail')
						->rows(2)
						->help('Detalles')
						->hideFromIndex(),
				]),
				Tab::make('Ofertante', [
					Text::make('Nombre', 'bidder_name')
						->hideFromIndex()
						->canSee(function ($request) {
							return $request->user()->is_admin || $request->user()->cdr_id == $this->cdr_id;
						}),
					Text::make('Teléfono', 'bidder_phone')
						->hideFromIndex()
						->canSee(function ($request) {
							return $request->user()->is_admin || $request->user()->cdr_id == $this->cdr_id;
						}),
					Text::make('Email', 'bidder_email')
						->hideFromIndex()
						->canSee(function ($request) {
							return $request->user()->is_admin || $request->user()->cdr_id == $this->cdr_id;
						}),
					Textarea::make('Comentarios', 'bidder_comments')
						->hideFromIndex()
						->alwaysShow()
						->canSee(function ($request) {
							return $request->user()->is_admin || $request->user()->cdr_id == $this->cdr_id;
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
		$user = Auth::user();

		$retorno = [
			new BySource,
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

			(new BusinessExport),
		];
	}
}
