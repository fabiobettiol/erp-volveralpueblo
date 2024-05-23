<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Fields\FormData;
use Illuminate\Contracts\Database\Eloquent\Builder;

class Enterprise extends Resource
{
    public static function label() {
        return 'Empresas';
    }

    public static function singularLabel() {
        return 'Empresa';
    }

    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\Enterprise>
     */
    public static $model = \App\Models\Enterprise::class;

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
            BelongsTo::make('Emprededor', 'entrepreneur', 'App\Nova\Entrepreneur'),
            Text::make('Nombre', 'name'),
            Text::make('CIF', 'cif'),
            Textarea::make('Dirección', 'address'),
            BelongsTo::make('Comunidad','community','App\Nova\Community'),

            BelongsTo::make('Provincia', 'province', 'App\Nova\Province')
                ->dependsOn(['community'], function (BelongsTo $field, NovaRequest $request, FormData $formData) {
                    $field->relatableQueryUsing(function (NovaRequest $request, Builder $query) use ($formData) {
                        $query->when($formData->community, function ($query) use ($formData) {
                            $query->where('community_id', $formData->community);
                        });
                    });
                })
                ,
            BelongsTo::make('Municipio', 'municipality', 'App\Nova\Municipality')
                ->dependsOn(['province'], function (BelongsTo $field, NovaRequest $request, FormData $formData) {
                    $field->relatableQueryUsing(function (NovaRequest $request, Builder $query) use ($formData) {
                        $query->when($formData->province, function ($query) use ($formData) {
                            $query->where('province_id', $formData->province);
                        });
                    });
                }),

            BelongsTo::make('Localidad', 'locality', 'App\Nova\Locality')
                ->dependsOn(['municipality'], function (BelongsTo $field, NovaRequest $request, FormData $formData) {
                    $field->relatableQueryUsing(function (NovaRequest $request, Builder $query) use ($formData) {
                        $query->when($formData->municipality, function ($query) use ($formData) {
                            $query->where('municipality_id', $formData->municipality);
                        });
                    });
                })->hideFromIndex(),

            Text::make('Código Postal', 'post_code'),
            Date::make('Fecha de fundación', 'date'),
            Text::make('Teléfono', 'phone'),
            Text::make('Móvil', 'mobile'),
            Text::make('Email', 'email'),
            Text::make('Sitio Web', 'website'),
            Textarea::make('Descripción', 'description'),
            Textarea::make('Actividad', 'activity'),
            Number::make('Empleados', 'employees'),
            Textarea::make('Productos', 'products'),
            Textarea::make('Forma de venta', 'selling'),
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
