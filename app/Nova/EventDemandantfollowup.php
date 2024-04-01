<?php

namespace App\Nova;

use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Http\Requests\NovaRequest;

class EventDemandantfollowup extends Resource
{

	public static function calendar() {
		return [
			'color' => 'secondary',
			'badge' => '<svg xmlns="http://www.w3.org/2000/svg" style="display: inline-block" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                <path fill-rule="evenodd" d="M12.577 4.878a.75.75 0 01.919-.53l4.78 1.281a.75.75 0 01.531.919l-1.281 4.78a.75.75 0 01-1.449-.387l.81-3.022a19.407 19.407 0 00-5.594 5.203.75.75 0 01-1.139.093L7 10.06l-4.72 4.72a.75.75 0 01-1.06-1.061l5.25-5.25a.75.75 0 011.06 0l3.074 3.073a20.923 20.923 0 015.545-4.931l-3.042-.815a.75.75 0 01-.53-.919z" clip-rule="evenodd" />
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
        return 'Interacciones (S)';
    }

    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\EventDemandantfollowup>
     */
    public static $model = \App\Models\EventDemandantfollowup::class;

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
        'id',
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
