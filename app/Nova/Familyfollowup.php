<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Http\Requests\NovaRequest;
use Maatwebsite\LaravelNovaExcel\Actions\DownloadExcel;

class Familyfollowup extends Resource {

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

	public static function redirectAfterCreate(NovaRequest $request, $resource)
	{	
		return '/resources/families/'.$resource->family_id;
	}

	public static function redirectAfterUpdate(NovaRequest $request, $resource)
	{	
		return '/resources/families/'.$resource->family_id;
	}

	public static $group = 'Asentad@s';

	public static function label() {
		return 'Seguimientos';
	}

	public static function singularLabel() {
		return 'Seguimiento';
	}

	/**
	 * The model the resource corresponds to.
	 *
	 * @var string
	 */
	public static $model = \App\Models\Familyfollowup::class;

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
			// ID::make(__('ID'), 'id')->sortable(),
			Date::make('Fecha', 'date')->rules('required')
				->rules('required', 'date')
				->sortable()
				->filterable(),
			BelongsTo::make('Familia', 'family', 'App\Nova\Family'),
			BelongsTo::make('CDR', 'cdr', 'App\Nova\Cdr')
				->sortable()
				->filterable()
				->exceptOnForms(),
			BelongsTo::make('Usuario', 'user', 'App\Nova\User')
				->sortable()
				->exceptOnForms(),
			Text::make('Asunto', 'subject')
				->rules('required', 'max:100'),
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
		return [
			(new DownloadExcel)
				->withHeadings()
				->allFields(),
		];
	}
}
