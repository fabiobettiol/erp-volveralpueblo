<?php

namespace App\Nova;

use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\BelongsTo;
use Illuminate\Support\Facades\Http;
use Metrixinfo\Nova\Fields\Iframe\Iframe;
use Laravel\Nova\Http\Requests\NovaRequest;

class Cdr extends Resource {
	public static $group = 'Auxiliares';

	public static $defaultSort = 'name'; // Update to your default column

	public static function availableForNavigation(Request $request) {
		return
			($request->user()->is_admin ||
			($request->user()->is_cdr_admin && $request->user()->cdr_id));
	}

	/**
	 * Build an "index" query for the given resource.
	 *
	 * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
	 * @param  \Illuminate\Database\Eloquent\Builder  $query
	 * @return \Illuminate\Database\Eloquent\Builder
	 */
	public static function indexQuery(NovaRequest $request, $query) {
		if ($request->user()->is_cdr_admin) {

			return $query->where('id', $request->user()->cdr_id);

		} elseif (static::$defaultSort && empty($request->get('orderBy'))) {

			$query->getQuery()->orders = [];
			return $query->orderBy(static::$defaultSort);

		}

		return $query;
	}

	public static function label() {
		return 'CDRs';
	}

	public static function authorizedToCreate(Request $request) {
		return $request->user() ? $request->user()->is_admin : true;
	}

	public function authorizedToDelete(Request $request) {
		return $request->user() ? $request->user()->is_admin : false;
	}

	/**
	 * The model the resource corresponds to.
	 *
	 * @var string
	 */
	public static $model = \App\Models\Cdr::class;

	/**
	 * The single value that should be used to represent the resource when being displayed.
	 *
	 * @var string
	 */
	public static $title = 'name';

	/**
	 * The columns that should be searched.
	 *
	 * @var array
	 */
	public static $search = [
		'name',
	];

	/**
	 * Get the fields displayed by the resource.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return array
	 */
	public function fields(Request $request) {
		return [
			ID::make(__('ID'), 'id')->sortable(),
			Text::make('Enlace para demandantes', function () {
				return 'https://erp-recursos.volveralpueblo.org/personas/' . $this->hash;
			})->onlyOnDetail(),
			Text::make('Nombre', 'name'),
			Boolean::make('Mapa', 'mapinfo')
				->hideWhenCreating()
				->canSee(function ($request) {
					return $request->user()->is_admin;
				}),
			Image::make('Logo')->path('logos/cdr'),
			BelongsTo::make('Zona', 'zone', 'App\Nova\Zone')
				->canSee(function ($request) {
					return $request->user()->is_admin;
				}),
			BelongsTo::make('Comunidad', 'community', 'App\Nova\Community')
				->filterable(),
			BelongsTo::make('Provincia', 'province', 'App\Nova\Province')
				->filterable(),
			BelongsTo::make('Municipio', 'municipality', 'App\Nova\Municipality')
				->filterable(),
			BelongsTo::make('Tipo', 'cdrtype', 'App\Nova\Cdrtype'),
			Textarea::make('Dirección', 'address'),
			Text::make('Ciudad', 'city')
				->hideFromIndex(),
			Text::make('Cód. Postal', 'pc')
				->hideFromIndex(),
			Text::make('Contacto', 'contact')
				->hideFromIndex(),
			Text::make('Forma Contacto', 'contact_type')
				->hideFromIndex(),
			Text::make('Horario', 'schedule')
				->hideFromIndex(),
			Text::make('Teléfono', 'phone')
				->hideFromIndex(),
			Text::make('Email')
				->hideFromIndex(),
			Text::make('Web')
				->hideFromIndex(),
			Text::make('Enlace', 'link')
				->hideFromIndex()
				->help('Enlace adicional, diferente del sitio web'),
			Text::make('Título enlace', 'link_title')
				->help('Equiqueta que se mostrará para el anterior enlace (Por ejemplo, "Formulario") - 20 caracteres')
				->rules('max:20')
				->hideFromIndex(),

			Boolean::make('Web Coceder', 'web_coceder')
				->canSee(function ($request) {
					return $request->user()->is_admin;
				}),
			Boolean::make('Web Volver al Pueblo', 'web_volveralpueblo')
				->canSee(function ($request) {
					return $request->user()->is_admin;
				}),
			Boolean::make('Web Biocuidados', 'web_biocuidados')
				->canSee(function ($request) {
					return $request->user()->is_admin;
				}),
			Boolean::make('Mapa', 'mapinfo')
				->hideFromIndex()
				->canSee(function ($request) {
					return $request->user()->is_admin;
				}),
			Text::make('Latitud', 'lat')
				->hideFromIndex()
				->canSee(function ($request) {
					return $request->user()->is_admin;
				}),
			Text::make('Longitud', 'lng')
				->hideFromIndex()
				->canSee(function ($request) {
					return $request->user()->is_admin;
				}),
			Boolean::make('Activo', 'active')
				->canSee(function ($request) {
					return $request->user()->is_admin;
				}),
			Text::make('Comentarios', 'comments')
				->hideFromIndex()
				->canSee(function ($request) {
					return $request->user()->is_admin;
				}),

			Textarea::make('Webhook Teams', 'teams_webhook')
				->hideFromIndex()
				->canSee(function ($request) {
					return $request->user()->is_admin;
				}),

			HasMany::make('Colaboradores', 'users', 'App\Nova\User')
				->canSee(function ($request) {
					return $request->user()->is_admin || $request->user()->is_cdr;
				}),
			HasMany::make('Comarcas', 'comarcas', 'App\Nova\Region')
				->canSee(function ($request) {
					return $request->user()->is_admin || $request->user()->is_cdr;
				}),

			HasMany::make('Convenios', 'convenios', 'App\Nova\Cdragreement')
				->canSee(function ($request) {
					return $request->user()->is_admin || $request->user()->is_cdr;
				}),
			//HasMany::make('Noticias', 'news', 'App\Nova\Cdrnew'),
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
		return [];
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
		return [];
	}
}
