<?php

namespace App\Nova;

use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;


class EventCall extends Resource {
    
	public static function calendar() {
		return [
			'color' => 'primary',
			'badge' => ''
		];

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
			ID::make()->sortable(),
			Text::make('Título', 'title')
				->rules('required'),
			Textarea::make('Descripción', 'description')
				->alwaysShow(),
			DateTime::make('Inicia', 'start')
				->rules(['required']),
			DateTime::make('Termina', 'end')
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
