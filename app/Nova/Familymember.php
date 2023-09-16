<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;

class Familymember extends Resource {

	public static $group = 'Asentad@s';

	public static function label() {
		return 'Miembros';
	}

	public static $perPageViaRelationship = 10;

	public static function indexQuery(NovaRequest $request, $query) {
		if ($request->user()->is_admin) {
			return $query;
		} else {
			return $query->where('cdr_id', $request->user()->cdr_id);
		}
	}

	/**
	 * The model the resource corresponds to.
	 *
	 * @var string
	 */
	public static $model = \App\Models\Familymember::class;

	/**
	 * The single value that should be used to represent the resource when being displayed.
	 *
	 * @var string
	 */
	//public static $title = 'id';

	public function title() {
		return $this->name . ' ' . $this->surname;
	}

	/**
	 * The columns that should be searched.
	 *
	 * @var array
	 */
	public static $search = [
		'id',
		'name',
		'surname',
	];

	/**
	 * Get the fields displayed by the resource.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return array
	 */
	public function fields(Request $request) {
		return [
			//ID::make(__('ID'), 'id')->sortable(),
			//Text::make('id', 'id')->sortable(),
			BelongsTo::make('Núcleo familiar', 'family', 'App\Nova\Family'),
			Boolean::make('Persona de referencia', 'is_responsible')->sortable(),
			Boolean::make('Es menor', 'is_child')->sortable(),
			BelongsTo::make('Género', 'gender', 'App\Nova\Gender')->sortable(),
			Text::make('Nombre', 'name')->sortable(),
			Text::make('Apellido', 'surname')->sortable(),
			Text::make('Teléfono', 'phone')->hideFromIndex(),
			Text::make('Email')->hideFromIndex(),
			Date::make('Fecha de nacimiento', 'dateofbirth'),
			BelongsTo::make('País de origen', 'nationality', '\App\Nova\Country')
				->hideFromIndex()
				->help('País de origen, nacimiento'),
			Select::make('Situación laboral', 'employment_status')->options([
				'1' => 'Empleado por cuenta ajena',
				'2' => 'Empleado por cuenta propia',
				'3' => 'Desempleado',
				'4' => 'Estudiando',
				'5' => 'Otros',
			])->displayUsingLabels()->required(),
			Text::make('Comentarios', 'employment_comment')
				->hideFromIndex()
				->help('Comentarios sobre la situación de empleo'),
			BelongsTo::make('Sector', 'sector', 'App\Nova\Sector')
				->hideFromIndex()
				->help('Sector económico donde está empleado'),
			Boolean::make('Itinerarios', 'itineraries'),
			Textarea::make('Comentarios', 'itineraries_comments')
				->help('Comentarios sobre itinerarios')
				->alwaysShow(),
			Boolean::make('Otros programas', 'other_programs'),
			Textarea::make('Comentarios', 'program_comments')
				->help('Comentarios sobre otros programas')
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
		return [];
	}
}
