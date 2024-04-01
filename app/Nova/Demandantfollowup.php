<?php

namespace App\Nova;
use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\BelongsTo;
use App\Nova\Metrics\DemandantsFollowups;
use App\Nova\Metrics\DemandantFollowUpCDR;
use Laravel\Nova\Http\Requests\NovaRequest;
use App\Nova\Metrics\DemandantFollowUpPerDayCDR;
use Maatwebsite\LaravelNovaExcel\Actions\DownloadExcel;

class Demandantfollowup extends Resource {

	public function authorizedToUpdate(Request $request): bool {

		if ($request->user()->hasPermissionTo('edit demandantfollowups')) {
			return true;
		}

		if ($request->user()->hasPermissionTo('edit own demandantfollowups')) {
			return $this->cdr_id == $request->user()->cdr_id;
		}
	}

	public function authorizedToDelete(Request $request): bool {

		if ($request->user()->hasPermissionTo('delete demandantfollowups')) {
			return true;
		}

		return $request->user()->hasPermissionTo('delete own demandantfollowups');
	}

	public function authorizedToRestore(Request $request): bool {

		if ($request->user()->hasPermissionTo('restore demandantfollowups')) {
			return true;
		}

		return $request->user()->hasPermissionTo('restore own demandantfollowups');
	}

	public static $group = 'Demandantes';

	public static function label() {
		return 'Interacciones';
	}

	public static function singularLabel() {
		return 'Interacción';
	}

	public static function availableForNavigation(Request $request) {
		return !$request->user()->is_collaborator;
	}

	public static function redirectAfterCreate(NovaRequest $request, $resource)
	{	
		return '/resources/demandants/'.$resource->demandant_id;
	}

	public static function redirectAfterUpdate(NovaRequest $request, $resource)
	{	
		return '/resources/demandants/'.$resource->demandant_id;
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
		'comments',
		'demandant.name',
		'demandant.surname',
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
				->sortable()
				->filterable(),
			BelongsTo::make('Solicitante', 'demandant', 'App\Nova\Demandant')
				->sortable(),
			BelongsTo::make('CDR', 'cdr', 'App\Nova\Cdr')
				->filterable()
				->readonly(function ($request) {
					return !$request->user()->hasPermissionTo('administrator');
				}),
			BelongsTo::make('Usuario', 'user', 'App\Nova\User')
				->sortable()
				->exceptOnForms(),
			Text::make('Asunto', 'subject')
				->rules('required', 'max:100'),
			Textarea::make('Interacción', 'text')
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
		return [
			(new DemandantFollowUpCDR)->width('1/3'),
			(new DemandantFollowUpPerDayCDR)->width('2/3')
		];
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
