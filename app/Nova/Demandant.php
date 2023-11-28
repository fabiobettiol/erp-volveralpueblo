<?php

namespace App\Nova;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Maatwebsite\LaravelNovaExcel\Actions\DownloadExcel;

class Demandant extends Resource {

	public static $globallySearchable = false;

	public static $group = 'Demandantes';

	public static function label() {
		return 'Solicitantes';
	}

	public static function availableForNavigation(Request $request) {
		return !$request->user()->is_collaborator;
	}

	/**
	 * The model the resource corresponds to.
	 *
	 * @var string
	 */
	public static $model = \App\Models\Demandant::class;

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
		'email',
		'phone',
		'identification',
		'subject',
		'experience',
		'tellus',
		'necessity'
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
			Date::make('Fecha', 'created_at')
				->filterable()
				->onlyOnIndex(),
			Date::make('Fecha de Registro', 'created_at')
				->onlyOnDetail(),				
			Belongsto::make('CDR', 'cdr', 'App\Nova\Cdr')
				->filterable(),
			BelongsTo::make('Sexo', 'gender', 'App\Nova\Gender'),
			Text::make('Nombre', 'name')
				->rules('required', 'max:45'),
			Text::make('Apellido', 'surname')
				->rules('required', 'max:60'),
			Text::make('Email', 'email')
				->rules('required', 'max:75'),
			Text::make('Teléfono', 'phone')
				->rules('regex:/^([0-9\s\-\+\(\)]*)$/','min:9'),
			BelongsTo::make('Tipo documento', 'documenttype', 'App\Nova\Documenttype')
				->hideFromIndex(),
			Text::make('Identificación', 'identification')
				->rules('required', 'max:20'),
			Text::make('Ciudad', 'city')
				->rules('required', 'max:60')
				->hideFromIndex(),
			BelongsTo::make('Nacionalidad', 'country', 'App\Nova\Country')->hideFromIndex(),
			Date::make('Fecha Nacimiento', 'birthdate')
				->help('Selecciona en el calendario o escribe en formato AAAA-MM-DD')
				->hideFromIndex(),
			Number::make('Adultos', 'adults')
				->rules('required', 'gt:0')
				->hideFromIndex(),
			Number::make('Niños', 'children')
				->rules('required', 'gte:0')
				->hideFromIndex(),
			Boolean::make('Potencial poblador', 'potential')
				->filterable()
				->help('Indique si este solicitante podría ser un potencial poblador'),
			Textarea::make('Detalles del potencial poblador', 'potential_details')
				->help('Explique brevemente por qué lo considera un potencial poblador')
				->alwaysShow()
				->rows(2),

			Select::make('Cómo nos conoció', 'knowingus')->options([
				'1' => 'Algún conocido/a',
				'2' => 'Buscadores de internet',
				'3' => 'Prensa',
				'4' => 'Redes sociales',
				'5' => 'Otros',
			])->hideFromIndex()	,

			BelongsTo::make('Provincia de origen', 'provincefrom', 'App\Nova\Province')
				->nullable(),
			BelongsTo::make('Provincia de destino', 'provinceto', 'App\Nova\Province')
				->nullable(),

			Textarea::make('Proyecto de emprendimiento', 'subject')
				->alwaysShow(),
			Textarea::make('Experiencia, Profesión, Habilidades', 'experience')
				->alwaysShow(),
			Textarea::make('Podemos ayudarte', 'tellus')
				->alwaysShow(),
			Textarea::make('Necesidades', 'necessity')
				->alwaysShow(),

			HasMany::make('Documentos', 'documents', 'App\Nova\Demandantdoc'),
			HasMany::make('Interacciones', 'followups', 'App\Nova\Demandantfollowup'),
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
		return [
			(new DownloadExcel)
				->withHeadings()
				->allFields(),
		];
	}
}
