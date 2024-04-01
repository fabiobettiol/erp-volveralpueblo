<?php

namespace App\Nova;

use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Http\Requests\NovaRequest;
use Wdelfuego\Nova\DateTime\Fields\DateTime;


class EventCall extends Resource {
    
	public static function calendar() {
		return [
			'color' => 'danger',
			'badge' => '<svg xmlns="http://www.w3.org/2000/svg" style="display: inline-block" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
			<path d="M3.5 2A1.5 1.5 0 002 3.5V5c0 1.149.15 2.263.43 3.326a13.022 13.022 0 009.244 9.244c1.063.28 2.177.43 3.326.43h1.5a1.5 1.5 0 001.5-1.5v-1.148a1.5 1.5 0 00-1.175-1.465l-3.223-.716a1.5 1.5 0 00-1.767 1.052l-.267.933c-.117.41-.555.643-.95.48a11.542 11.542 0 01-6.254-6.254c-.163-.395.07-.833.48-.95l.933-.267a1.5 1.5 0 001.052-1.767l-.716-3.223A1.5 1.5 0 004.648 2H3.5zM16.5 4.56l-3.22 3.22a.75.75 0 11-1.06-1.06l3.22-3.22h-2.69a.75.75 0 010-1.5h4.5a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0V4.56z" />
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
        return 'Llamadas';
    }

	/**
	 * The model the resource corresponds to.
	 *
	 * @var class-string<\App\Models\EventCall>
	 */
	public static $model = \App\Models\EventCall::class;

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
			// ID::make()->sortable(),
			Text::make('Título', 'title')
				->rules('required', 'max:50'),
			Textarea::make('Descripción', 'description')
				->rows(2)
				->alwaysShow(),
			BelongsTo::make('Usuario', 'user', 'App\Nova\User')
				->canSee(function ($request) {
					return $request->user()->hasPermissionTo('administrator') ||
						($request->user()->is_cdr_admin &&
						($request->user()->cdr_id == $this->cdr_id));
				}),
			BelongsTo::make('CDR', 'cdr', 'App\Nova\Cdr')
				->canSee(function ($request) {
					return $request->user()->hasPermissionTo('administrator');
				}),
			DateTime::make('Inicia', 'start')
				->filterable()
				->rules(['required']),
			DateTime::make('Termina', 'end')
				->filterable()
				->help('Si no indica una fecha y hora de fin, el evento de considerará de todo el día'),
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
