<?php

namespace App\Nova;

use Illuminate\Support\Str;
use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Http\Requests\NovaRequest;

class FamilyImpact extends Resource
{
    public static function label() {
        return 'Impactos';
    }

    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\FamilyImpact>
     */
    public static $model = \App\Models\FamilyImpact::class;

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
            ID::make()->sortable(),
            Text::make('Impacto', 'name')
                ->displayUsing(function($text) {
                    return Str::limit($text, 30);
                })->onlyOnIndex(),

            Text::make('Impacto', 'name')->hideFromIndex(),

            BelongsTo::make('Tipo', 'type', 'App\Nova\FamilyImpactType'),

            BelongsToMany::make('Ámbito', 'scopes', 'App\Nova\FamilyImpactScope'),

            BelongsToMany::make('Familias', 'families', 'App\Nova\Family')
                ->fields(function ($request, $relatedModel) {
                    return [
                        Text::make('Descripción', 'description')
                            ->displayUsing(function($text) {;
                                return Str::limit($text, 30);
                            }),
                        Date::make('Fecha', 'date'),
                    ];
                })->onlyOnIndex(),

            BelongsToMany::make('Familias', 'families', 'App\Nova\Family')
                ->fields(function ($request, $relatedModel) {
                    return [
                        Textarea::make('Descripción', 'description'),
                        //Date::make('Fecha', 'date'),
                    ];
                })->hideFromIndex(),


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
