<?php

namespace App\Nova;

use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Http\Requests\NovaRequest;

class EventMeeting extends Resource {

	public static function calendar() {
		return [
			'color' => 'info',
			'badge' => '<svg xmlns="http://www.w3.org/2000/svg" style="display: inline-block" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
			<path d="M10 9a3 3 0 100-6 3 3 0 000 6zM6 8a2 2 0 11-4 0 2 2 0 014 0zM1.49 15.326a.78.78 0 01-.358-.442 3 3 0 014.308-3.516 6.484 6.484 0 00-1.905 3.959c-.023.222-.014.442.025.654a4.97 4.97 0 01-2.07-.655zM16.44 15.98a4.97 4.97 0 002.07-.654.78.78 0 00.357-.442 3 3 0 00-4.308-3.517 6.484 6.484 0 011.907 3.96 2.32 2.32 0 01-.026.654zM18 8a2 2 0 11-4 0 2 2 0 014 0zM5.304 16.19a.844.844 0 01-.277-.71 5 5 0 019.947 0 .843.843 0 01-.277.71A6.975 6.975 0 0110 18a6.974 6.974 0 01-4.696-1.81z" />
		  </svg>
		  '
		];

	} 

	public static function indexQuery(NovaRequest $request, $query) {

		if ($request->user()->is_cdr_admin) {
			$query->where('cdr_id', $request->user()->cdr_id);
		} else {
			$query->where('user_id', $request->user()->id);
			return $query;
		}
	}	

	public static function label() {
        return 'Reuniones';
    }

	/**
	 * The model the resource corresponds to.
	 *
	 * @var class-string<\App\Models\EventMeeting>
	 */
	public static $model = \App\Models\EventMeeting::class;

	/**
	 * The single value that should be used to represent the resource when being displayed.
	 *
	 * @var string
	 */
	public static $title = 'title';

	/**
	 * The columns that should be searched.
	 *
	 * @var array
	 */
	public static $search = [
		'title',
		'description',
	];

	/**
	 * Get the fields displayed by the resource.
	 *
	 * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
	 * @return array
	 */
	public function fields(NovaRequest $request) {
		return [
			ID::make()->sortable(),
			Text::make('Título', 'title')
				->rules('required'),
			Textarea::make('Descripción', 'description')
				->alwaysShow(),
			BelongsTo::make('Usuario', 'user', 'App\Nova\User')
				->canSee(function ($request) {
					return $request->user()->is_admin || $request->user()->is_cdr_admin && ($request->user()->cdr_id == $this->cdr_id);
				}),
			BelongsTo::make('CDR', 'cdr', 'App\Nova\Cdr')
				->canSee(function ($request) {
					return $request->user()->is_admin;
				}),				
			DateTime::make('Inicia', 'start'),
			DateTime::make('Termina', 'end')
		];
	}

	/**
	 * Get the cards available for the request.
	 *
	 * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
	 * @return array
	 */
	public function cards(NovaRequest $request) {
		return [];
	}

	/**
	 * Get the filters available for the resource.
	 *
	 * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
	 * @return array
	 */
	public function filters(NovaRequest $request) {
		return [];
	}

	/**
	 * Get the lenses available for the resource.
	 *
	 * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
	 * @return array
	 */
	public function lenses(NovaRequest $request) {
		return [];
	}

	/**
	 * Get the actions available for the resource.
	 *
	 * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
	 * @return array
	 */
	public function actions(NovaRequest $request) {
		return [];
	}
}
