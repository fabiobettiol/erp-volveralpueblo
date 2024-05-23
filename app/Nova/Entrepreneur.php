<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Http\Requests\NovaRequest;

class Entrepreneur extends Resource
{
    public static function label() {
        return 'Emprendedores';
    }

    public static function singularLabel() {
        return 'Empendedor';
    }
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\Entreprenour>
     */
    public static $model = \App\Models\Entrepreneur::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public function title() {
        return $this->first_name . ' ' . $this->last_name;
    }

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'first_name', 'last_name'
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
            ID::make()->sortable(),
            Text::make('Nombre','first_name'),
            Text::make('Apellido','last_name'),
            Text::make('DNI/NIE','identification'),
            Date::make('Fecha de nacimiento','dob'),
            Text::make('Teléfono','phone'),
            Text::make('Móvil','mobile'),
            Text::make('Nacionalidad','nationality'),
            Text::make('Email','email'),
            Text::make('Dirección','address'),
            BelongsTo::make('Comunidad','community','App\Nova\Community'),
            BelongsTo::make('Provincia','province','App\Nova\Province'),
            BelongsTo::make('Localidad','locality','App\Nova\Locality'),
            Text::make('Código Postal','post_code'),
            Textarea::make('Estudios','studies'),
            Textarea::make('Perfil Profesional','profile'),
            HasMany::make('Empresas','enterprises','App\Nova\Enterprise'),
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
