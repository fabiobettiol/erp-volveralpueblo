<?php

namespace App\Nova;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;

class Demandantfollowup extends Resource {

	public static $group = 'Demandantes';

	public static function label() {
		return 'Interacciones';
	}

	public static function singularLabel() {
		return 'Interaccion';
	}

	public static function availableForNavigation(Request $request) {
		return !$request->user()->is_collaborator;
	}

	/**
	 * The model the resource corresponds to.
	 *
	 * @var string
	 */
	public static $model = \App\Models\Demandantfollowup::class;

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

	/**
	 * Get the fields displayed by the resource.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return array
	 */
	public function fields(Request $request) {
		return [
			// ID::make(__('ID'), 'id'),
			Date::make('Fecha', 'date')
				->rules('required', 'date')
				->filterable(),
			BelongsTo::make('Solicitante', 'demandant', 'App\Nova\Demandant'),
			BelongsTo::make('CDR', 'cdr', 'App\Nova\Cdr')
				->filterable()
				->readonly(function ($request) {
					return !$request->user()->is_admin;
				}),
			BelongsTo::make('Usuario', 'user', 'App\Nova\User')
				->exceptOnForms(),
			Text::make('Asunto', 'subject')
				->rules('required', 'max:100'),
			Textarea::make('Entrevista', 'text')
				->rules('required')
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
		return [
			//
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
		return [];
	}
}
