<?php

namespace App\Nova;

use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Http\Requests\NovaRequest;

class EventOther extends Resource {

	public static function calendar() {
		return [
			'color' => 'success',
			'badge' => '<svg xmlns="http://www.w3.org/2000/svg" style="display: inline-block" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
			<path d="M4.214 3.227a.75.75 0 00-1.156-.956 8.97 8.97 0 00-1.856 3.826.75.75 0 001.466.316 7.47 7.47 0 011.546-3.186zM16.942 2.271a.75.75 0 00-1.157.956 7.47 7.47 0 011.547 3.186.75.75 0 001.466-.316 8.971 8.971 0 00-1.856-3.826z" />
			<path fill-rule="evenodd" d="M10 2a6 6 0 00-6 6c0 1.887-.454 3.665-1.257 5.234a.75.75 0 00.515 1.076 32.94 32.94 0 003.256.508 3.5 3.5 0 006.972 0 32.933 32.933 0 003.256-.508.75.75 0 00.515-1.076A11.448 11.448 0 0116 8a6 6 0 00-6-6zm0 14.5a2 2 0 01-1.95-1.557 33.54 33.54 0 003.9 0A2 2 0 0110 16.5z" clip-rule="evenodd" />
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
        return 'Otros';
    }	

	/**
	 * The model the resource corresponds to.
	 *
	 * @var class-string<\App\Models\EventOther>
	 */
	public static $model = \App\Models\EventOther::class;

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
