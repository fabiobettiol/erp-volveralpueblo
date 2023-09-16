<?php

namespace App\Nova;

use App\Nova\Filters\ByCdr;
use App\Nova\Filters\ByUpdatedStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;

class Family extends Resource {

	public static $group = 'Asentad@s';

	public static function label() {
		return 'Familias';
	}

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
	public static $model = \App\Models\Family::class;

	/**
	 * The single value that should be used to represent the resource when being displayed.
	 *
	 * @var string
	 */
	public static $title = 'reference';

	/**
	 * The columns that should be searched.
	 *
	 * @var array
	 */
	public static $search = [
		'reference',
		'family_name',
		'family_description',
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
			//Text::make('id', 'id')->sortable()->hideWhenCreating(),

			Boolean::make('Actualizado', 'datos_actualizados'),
			Text::make('Referencia', 'reference')
				->sortable()
				->hideWhenCreating(),
			Text::make('Familia', 'family_name')
				->help('Un breve nombre descriptivo para esta familia')
				->sortable(),
			Textarea::make('Descripción', 'family_description')
				->help('Un descripcíón sobre la familia')
				->alwaysShow()
				->rows(2),
			Belongsto::make('CDR', 'cdr', 'App\Nova\Cdr')
				->canSee(function ($request) {
					return $request->user()->is_admin;
				}),
			Date::make('Fecha de asentamiento', 'settlementdate')->required(),
			BelongsTo::make('País de origen', 'nationality', 'App\Nova\Country'),
			BelongsTo::make('Provincia de origen', 'sourceprovince', 'App\Nova\Province'),
			Text::make('Loalidad de origen', 'fromcityname')->hideFromIndex(),
			BelongsTo::make('Provincia de destino', 'destinationprovince', 'App\Nova\Province'),
			Text::make('Localidad de destino', 'tocityname')->hideFromIndex(),
			BelongsTo::make('Tipo de asentamiento', 'settlementtype', 'App\Nova\Settlementtype'),
			BelongsTo::make('Estado de asentamiento', 'settlementstatus', 'App\Nova\Settlementstatus'),
			Boolean::make('Itinerarios', 'itineraries')->hideFromIndex(),
			Textarea::make('Detalles de itinerarios', 'itineraries_comment')
				->help('Detalles sobre los itinerarios')
				->alwaysShow(),

			Boolean::make('Atienden los medios', 'publicity')->hideFromIndex(),
			Text::make('Detalles', 'publicity_comment')->hideFromIndex()->hideFromIndex(),
			Boolean::make('Entrevistas/videos realizados', 'publicity_available')->hideFromIndex(),
			Textarea::make('Enlace/ruta', 'publicity_resources')
				->help('Agregue cada enlace o ruta en una nueva linea.')
				->alwaysShow(),

			Boolean::make('Vivienda', 'difficulty_housing')->hideFromIndex(),
			Boolean::make('Económicas', 'difficulty_economic')->hideFromIndex(),
			Boolean::make('Comunicativas', 'difficulty_communicative')->hideFromIndex(),
			Boolean::make('Sociales', 'difficulty_social')->hideFromIndex(),
			Boolean::make('Privacidad', 'difficulty_privacy')->hideFromIndex(),
			Boolean::make('Otras', 'difficulty_others')->hideFromIndex(),
			Textarea::make('Detalles', 'difficulty_comments')
				->alwaysShow(),

			Boolean::make('Paz y tranquilidad', 'positive_peace')->hideFromIndex(),
			Boolean::make('Calidad de vida', 'positive_lifequality')->hideFromIndex(),
			Boolean::make('Naturalez', 'positive_nature')->hideFromIndex(),
			Boolean::make('Social, contacto personal', 'positive_social')->hideFromIndex(),
			Boolean::make('Otras', 'positive_others')->hideFromIndex(),
			Textarea::make('Detalles', 'positive_comments')
				->alwaysShow(),

			// - Se remueven los campos Rating for falta de soporte para Nova v4
			// - Reemplazar por selectores (BelongsTo)
			// - Este campo no debería ir en familias sino en seguimientos (de familias)

			// Rating::make('Adaptación', 'adaptation_rating')->min(0)->max(5)->increment(1)
			// 	->withStyles([
			// 		'star-size' => 20,
			// 	])
			// 	->help("1 = Muy complicado ... 5 = Muy bien"),

			// Rating::make('Valorar experiencia', 'experience_rating')->min(0)->max(5)->increment(1)
			// 	->withStyles([
			// 		'star-size' => 20,
			// 	])
			// 	->help("1 = Muy complicado ... 5 = Muy bien")
			// 	->hideFromIndex(),

			HasMany::make('Documentos', 'documents', 'App\Nova\Familydoc'),

			HasMany::make('Miembros', 'members', 'App\Nova\Familymember'),

			HasMany::make('Seguimiento', 'followups', 'App\Nova\Familyfollowup'),

			HasMany::make('Intervenciones', 'contacts', 'App\Nova\Familycontact'),
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
		$user = Auth::user();

		$retorno = [
			new ByUpdatedStatus,
		];

		if ($user->is_admin) {
			array_unshift($retorno, new ByCdr);
		}

		return $retorno;
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
