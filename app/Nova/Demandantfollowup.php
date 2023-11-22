<?php

namespace App\Nova;

use App\Nova\Filters\ByCdr;
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
			BelongsTo::make('Solicitante', 'demandant', 'App\Nova\Demandant'),
			BelongsTo::make('CDR', 'cdr', 'App\Nova\Cdr')
				->withMeta(["value" => $request->user()->cdr_id])
				->readonly(function ($request) {
					return !$request->user()->is_admin;
				}),
			BelongsTo::make('Usuario', 'user', 'App\Nova\User')
				->exceptOnForms(),
			Date::make('Fecha', 'date'),
			Text::make('Asunto', 'subject'),
			Textarea::make('Entrevista', 'text')
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
			new ByCdr,
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
