<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\BelongsTo;
use App\Nova\Metrics\FamilyMemberCDR;
use App\Nova\Metrics\FamilyMemberAgeCDR;
use App\Nova\Metrics\FamilyMemberGenderCDR;
use Laravel\Nova\Http\Requests\NovaRequest;
use Maatwebsite\LaravelNovaExcel\Actions\DownloadExcel;

class Familymember extends Resource {

	public static $group = 'Asentad@s';

	public static function label() {
		return 'Miembros';
	}

	public static $perPageViaRelationship = 10;

	public static function indexQuery(NovaRequest $request, $query) {

		if ($request->user()->hasPermissionTo('view all familymembers')) {
			return $query;
		}

		if ($request->user()->hasPermissionTo('view own familymembers')) {
			return $query->whereHas('family', function($q) use ($request) {
				return $q->where('cdr_id', $request->user()->cdr_id);
			});
		}
	}

	public function authorizedToUpdate(Request $request): bool {

		if ($request->user()->hasPermissionTo('edit familymembers')) {
			return true;
		}

		if ($request->user()->hasPermissionTo('edit own familymembers')) {
			return $this->family->cdr_id == $request->user()->cdr_id;
		}
	}

	public function authorizedToDelete(Request $request): bool {

		if ($request->user()->hasPermissionTo('delete familymembers')) {
			return true;
		}

		return $request->user()->hasPermissionTo('delete own familymembers');
	}

	public function authorizedToRestore(Request $request): bool {

		if ($request->user()->hasPermissionTo('restore familymembers')) {
			return true;
		}

		return $request->user()->hasPermissionTo('restore own familymembers');
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
		'name',
		'surname',
		'employment_comment',
		'itineraries_comments',
		'program_comments'
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
			BelongsTo::make('Núcleo familiar', 'family', 'App\Nova\Family'),
			Belongsto::make('CDR', 'cdr', 'App\Nova\Cdr')
				->sortable()
				->filterable()
				->canSee(function ($request) {
					return $request->user()->hasPermissionTo('administrator');
				}),
			Boolean::make('Persona de referencia', 'is_responsible')->sortable(),
			Boolean::make('Es menor', 'is_child')->sortable(),
			BelongsTo::make('Género', 'gender', 'App\Nova\Gender')->sortable(),
			Text::make('Nombre', 'name')
				->rules('required', 'max:60')
				->sortable(),
			Text::make('Apellido', 'surname')
				->rules('required', 'max:60')
				->sortable(),
			Text::make('Teléfono', 'phone')
				->hideFromIndex(),
			Text::make('Email')
				->hideFromIndex(),
			Date::make('Fecha de nacimiento', 'dateofbirth')
				->sortable()
				->rules('required', 'date'),
			BelongsTo::make('País de origen', 'nationality', '\App\Nova\Country')
				->hideFromIndex()
				->help('País de origen, nacimiento'),
			Select::make('Situación laboral', 'employment_status')->options([
				'1' => 'Empleado por cuenta ajena',
				'2' => 'Empleado por cuenta propia',
				'3' => 'Desempleado',
				'4' => 'Estudiando',
				'5' => 'Otros',
			])->displayUsingLabels()
			->sortable()
			->required(),
			Textarea::make('Comentarios', 'employment_comment')
				->rules('max:255')
				->rows(2)
				->hideFromIndex()
				->help('Comentarios sobre la situación de empleo'),
			BelongsTo::make('Sector', 'sector', 'App\Nova\Sector')
				->hideFromIndex()
				->help('Sector económico donde está empleado'),
			Boolean::make('Itinerarios', 'itineraries')
				->sortable(),
			Textarea::make('Comentarios', 'itineraries_comments')
				->help('Comentarios sobre itinerarios')
				->alwaysShow(),
			Boolean::make('Otros programas', 'other_programs')
				->sortable(),
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
		return [
			(new FamilyMemberCDR)->width('1/3'),
			(new FamilyMemberGenderCDR)->width('1/3'),
			(new FamilyMemberAgeCDR)->width('1/3')
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
