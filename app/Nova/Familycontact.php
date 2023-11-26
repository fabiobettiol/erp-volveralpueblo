<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;

class Familycontact extends Resource {
	public static function label() {
		return 'Intervención';
	}

	public static $group = 'Asentad@s';

	public static function indexQuery(NovaRequest $request, $query) {
		if ($request->user()->is_admin) {
			return $query;
		} else {
			return $query->where('cdr_id', $request->user()->cdr_id);
		}
	}

	public static function availableForNavigation(Request $request) {
		return !$request->user()->is_collaborator;
	}

	/**
	 * The model the resource corresponds to.
	 *
	 * @var string
	 */
	public static $model = \App\Models\Familycontact::class;

	/**
	 * The single value that should be used to represent the resource when being displayed.
	 *
	 * @var string
	 */
	public static $title = 'id';

	/**
	 * The columns that should be searched.
	 *
	 * @var array
	 */
	public static $search = [
		'subject',
		'text',
		'comments'
	];

	public static $globallySearchable = false;

	/**
	 * Get the fields displayed by the resource.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return array
	 */
	public function fields(Request $request) {
		return [
			// ID::make(__('ID'), 'id')->sortable(),
			Date::make('Fecha', 'date')
				->filterable()
				->onlyOnIndex(),
			Date::make('Fecha', 'date')
			->hideFromIndex(),				
			BelongsTo::make('Familia', 'family', 'App\Nova\Family'),
			BelongsTo::make('CDR', 'cdr', 'App\Nova\Cdr')
				->filterable()
				->exceptOnForms(),
			BelongsTo::make('Usuario', 'user', 'App\Nova\User')
				->exceptOnForms(),

			Boolean::make('Concluída', 'completed')
				->filterable()	
				->sortable(),
			Text::make('Asunto', 'subject'),
			Textarea::make('Descripción', 'text')
				->alwaysShow(),
			Textarea::make('Comentarios', 'comments')
				->alwaysShow(),
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
