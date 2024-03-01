<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\BelongsTo;
use Illuminate\Support\Facades\Auth;
use Laravel\Nova\Http\Requests\NovaRequest;
use Maatwebsite\LaravelNovaExcel\Actions\DownloadExcel;

class Familycontact extends Resource {
	public static function label() {
		return 'Intervenciones';
	}

	public static function singularLabel() {
		return 'Intervención';
	}

	public static $group = 'Asentad@s';

	public static function indexQuery(NovaRequest $request, $query) {

		if ($request->user()->hasPermissionTo('view all familycontacts')) {
			return $query;
		}

		if ($request->user()->hasPermissionTo('view own familycontacts')) {
			return $query->whereHas('family', function($q) use ($request) {
				return $q->where('cdr_id', $request->user()->cdr_id);
			});
		}
	}

	public function authorizedToUpdate(Request $request): bool {

		if ($request->user()->hasPermissionTo('edit familycontacts')) {
			return true;
		}

		if ($request->user()->hasPermissionTo('edit own familycontacts')) {
			return $this->family->cdr_id == $request->user()->cdr_id;
		}
	}

	public function authorizedToDelete(Request $request): bool {

		if ($request->user()->hasPermissionTo('delete familycontacts')) {
			return true;
		}

		return $request->user()->hasPermissionTo('delete own familycontacts');
	}

	public function authorizedToRestore(Request $request): bool {

		if ($request->user()->hasPermissionTo('restore familycontacts')) {
			return true;
		}

		return $request->user()->hasPermissionTo('restore own familycontacts');
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
				->sortable()
				->filterable()
				->onlyOnIndex(),
			Date::make('Fecha', 'date')
				->rules('required')
				->hideFromIndex(),
			BelongsTo::make('Familia', 'family', 'App\Nova\Family'),
			BelongsTo::make('CDR', 'cdr', 'App\Nova\Cdr')
				->sortable()
				->filterable()
				->exceptOnForms(),
			BelongsTo::make('Usuario', 'user', 'App\Nova\User')
				->sortable()
				->exceptOnForms(),
			Boolean::make('Concluída', 'completed')
				->filterable()	
				->sortable(),
			Text::make('Asunto', 'subject')
				->rules('required'),
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
		return [
			(new DownloadExcel)
				->withHeadings()
				->allFields(),
		];
	}
}
