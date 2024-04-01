<?php

namespace App\Nova;

use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Http\Requests\NovaRequest;

class EventFamilycontact extends Resource
{

	public static function calendar() {
		return [
			'color' => 'primary',
			'badge' => '<svg xmlns="http://www.w3.org/2000/svg" style="display: inline-block" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                <path fill-rule="evenodd" d="M9.293 2.293a1 1 0 011.414 0l7 7A1 1 0 0117 11h-1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-3a1 1 0 00-1-1H9a1 1 0 00-1 1v3a1 1 0 01-1 1H5a1 1 0 01-1-1v-6H3a1 1 0 01-.707-1.707l7-7z" clip-rule="evenodd" />
                </svg>'
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
        return 'Intervenciones (F)';
    }

    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\EventFamilycontact>
     */
    public static $model = \App\Models\EventFamilycontact::class;

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
    public function fields(NovaRequest $request)
    {
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
    public function cards(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function filters(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function lenses(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function actions(NovaRequest $request)
    {
        return [];
    }
}
