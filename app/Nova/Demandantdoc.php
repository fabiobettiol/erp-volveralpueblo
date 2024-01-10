<?php

namespace App\Nova;

use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\File;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Laravel\Nova\Http\Requests\NovaRequest;

class Demandantdoc extends Resource {

    /* FABIO:
     * This resource is SET TO BE HIDDEN fo the sidebar
     * as it shows demandant-related documents for ALL demandants
     * without filtering if the demandant has Followups in the current usr's CDR
    */

	public static function label() {
		return 'Documentos';
	}

	public static function availableForNavigation(Request $request) {
		return !$request->user()->is_collaborator;
	}

	public static function redirectAfterCreate(NovaRequest $request, $resource)
	{	
		return '/resources/demandants/'.$resource->demandant_id;
	}

	public static function redirectAfterUpdate(NovaRequest $request, $resource)
	{	
		return '/resources/demandants/'.$resource->demandant_id;
	}

	/**
	 * The model the resource corresponds to.
	 *
	 * @var string
	 */
	public static $model = \App\Models\Demandantdoc::class;

	
	/**
	 * The single value that should be used to represent the resource when being displayed.
	 *
	 * @var string
	 */
	public static $title = 'name';

	/**
	 * The columns that should be searched.
	 *
	 * @var array
	 */
	public static $search = [
		'name', 
		'details',
		'demandant.name',
		'demandant.surname',
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
				->onlyOnIndex(),
			BelongsTo::make('Solicitante', 'demandant', 'App\Nova\Demandant'),
			Text::make('Nombre del Documento', 'name')
				->rules('max:50')
				->help('CV, Constancia, Certificado de Empadronamiento...'),
			Textarea::make('Detalles', 'details')
				->alwaysShow(),
			File::make('Documento', 'file')
				->disk('solicitantes-documentos')
				->download(function ($request, $model, $disk, $value) {
					$file = $model->demandant->name . ' ' . $model->demandant->surname . '-' . $model->name;
					return Storage::disk($disk)->download($value, $file);
				}),
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
