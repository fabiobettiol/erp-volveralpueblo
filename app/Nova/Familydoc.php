<?php

namespace App\Nova;

use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\File;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Familydoc extends Resource {

	public static function label() {
		return 'Documentos';
	}

	/**
	 * The model the resource corresponds to.
	 *
	 * @var string
	 */
	public static $model = \App\Models\Familydoc::class;

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
	];

	/**
	 * Get the fields displayed by the resource.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return array
	 */
	public function fields(Request $request) {
		return [
			ID::make(__('ID'), 'id')->sortable(),
			BelongsTo::make('Familia', 'family', 'App\Nova\Family'),
			Text::make('Nombre', 'name'),
			Textarea::make('Detalles', 'details')
				->alwaysShow(),
			File::make('Documento', 'file')
			->disk('familias-documentos')
			->download(function ($request, $model, $disk, $value) {
				$file = $model->family->family_name . '-' . $model->name;
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
