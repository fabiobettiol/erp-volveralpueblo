<?php

namespace App\Nova;

use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\File;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Laravel\Nova\Http\Requests\NovaRequest;

class Cdragreement extends Resource
{
    public static function label() {
        return 'Convenios';
    }

    public static function indexQuery(NovaRequest $request, $query) {

        if ($request->user()->hasPermissionTo('view all cdragreement')) {
            return $query;
        }

        if ($request->user()->hasPermissionTo('view own cdragreement')) {
            return $query->where('cdr_id', $request->user()->cdr_id);
        }
    }

    public function authorizedToUpdate(Request $request): bool {

        if ($request->user()->hasPermissionTo('edit cdragreement')) {
            return true;
        }

        if ($request->user()->hasPermissionTo('edit own cdragreement')) {
            return $query->where('cdr_id', $request->user()->cdr_id);
        }
    }

    public function authorizedToDelete(Request $request): bool {

        if ($request->user()->hasPermissionTo('delete cdragreement')) {
            return true;
        }

        return $request->user()->hasPermissionTo('delete own cdragreement');
    }

    public function authorizedToRestore(Request $request): bool {

        if ($request->user()->hasPermissionTo('restore cdragreement')) {
            return true;
        }

        return $request->user()->hasPermissionTo('restore own cdragreement');
    }

    public static function availableForNavigation(Request $request) {
        return !$request->user()->is_collaborator;
    }

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Cdragreement::class;

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
        'organisation'
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make(__('ID'), 'id')->sortable(),
            BelongsTo::make('CDR', 'cdr', 'App\Nova\Cdr'),
            Text::make('Nombre', 'name'),
            Text::make('Organización', 'organisation'),
            Date::make('Desde', 'from'),
            Date::make('Hasta', 'to'),
            Textarea::make('Detalles', 'details')
                ->alwaysShow(),
            File::make('Documento', 'file')
            ->disk('cdr-convenios')
            ->download(function ($request, $model, $disk, $value) {
                $file = $model->name . '-' . $model->organisation;
                return Storage::disk($disk)->download($value, $file);
            }),
            Boolean::make('Activo', 'active')
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [];
    }
}
