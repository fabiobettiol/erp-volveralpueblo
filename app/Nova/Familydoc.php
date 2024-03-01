<?php

namespace App\Nova;

use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\File;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Laravel\Nova\Http\Requests\NovaRequest;

class Familydoc extends Resource {

	public static function label() {
		return 'Documentos';
	}

	public static function indexQuery(NovaRequest $request, $query) {

		if ($request->user()->hasPermissionTo('view all familydocs')) {
			return $query;
		}

		if ($request->user()->hasPermissionTo('view own familydocs')) {
			return $query->whereHas('family', function($q) use ($request) {
				return $q->where('cdr_id', $request->user()->cdr_id);
			});
		}
	}

	public function authorizedToUpdate(Request $request): bool {

		if ($request->user()->hasPermissionTo('edit familydocs')) {
			return true;
		}

		if ($request->user()->hasPermissionTo('edit own familydocs')) {
			return $this->family->cdr_id == $request->user()->cdr_id;
		}
	}

	public function authorizedToDelete(Request $request): bool {

		if ($request->user()->hasPermissionTo('delete familydocs')) {
			return true;
		}

		return $request->user()->hasPermissionTo('delete own familydocs');
	}

	public function authorizedToRestore(Request $request): bool {

		if ($request->user()->hasPermissionTo('restore familydocs')) {
			return true;
		}

		return $request->user()->hasPermissionTo('restore own familydocs');
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
	public static $model = \App\Models\Familydoc::class;

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
		'details',
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
			BelongsTo::make('Familia', 'family', 'App\Nova\Family'),
			Text::make('Nombre', 'name')
				->rules('required', 'max:50'),
			Textarea::make('Detalles', 'details')
				->alwaysShow(),
			File::make('Documento', 'file')
				->disk('familias-documentos')
				->download(function ($request, $model, $disk, $value) {
					$file = $model->family->family_name . '-' . $model->name;
					return Storage::disk($disk)->download($value, $file);
				})
				->rules('required')
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
