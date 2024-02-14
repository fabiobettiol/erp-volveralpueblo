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
use App\Nova\Filters\BySector;
use App\Nova\Filters\BySource;
use Eminiarts\Tabs\TabsOnEdit;
use App\Nova\Actions\JobExport;
use Illuminate\Contracts\Database\Eloquent\Builder;
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
use Maatwebsite\LaravelNovaExcel\Actions\DownloadExcel;

class Job extends Resource {
	// use TabsOnEdit;

	public static function label() {
		return 'Trabajos';
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
		'description',	
		'position',
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
					return $request->user()->is_admin;
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
				->rules('required', 'max:100')
				->hideFromIndex(),
			Text::make('Código Postal', 'postcode')
				->rules('required','min:5', 'max:5')
				->hideFromIndex(),
			Text::make('Habitantes', 'population')
				->hideFromIndex(),

			Tabs::make('Detalles', [
				Tab::make('Oferta', [
					BelongsTo::make('Titularidad', 'jobownership', 'App\Nova\Jobownership')
						->hideFromIndex(),
					BelongsTo::make('Sector', 'sector', 'App\Nova\Sector')
						->sortable()
						->filterable(),
					Textarea::make('Descripción', 'description')
						->hideFromIndex()
						->alwaysShow(),
					BelongsTo::make('Régimen laboral', 'jobform', 'App\Nova\Jobform')
						->sortable(),
					Text::make('Puesto de trabajo', 'position'),
					Textarea::make('Requerimientos', 'requirements')
						->hideFromIndex()
						->alwaysShow(),
					Date::make('Desde', 'date_from')
						->hideFromIndex(),
					Date::make('Hasta', 'date_to')
						->sortable(),
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
					Text::make('Email para CV', 'cv_email')
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

			(new JobExport),
		];
	}
}
