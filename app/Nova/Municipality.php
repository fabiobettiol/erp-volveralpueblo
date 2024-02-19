<?php

namespace App\Nova;

use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Number;
use App\Nova\Filters\ByProvince;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Http\Requests\NovaRequest;

class Municipality extends Resource
{
    public static $defaultSort = 'name' ; // Update to your default column

    public static function availableForNavigation(Request $request)
    {
        return $request->user()->is_admin;
    }

    /**
     * Build an "index" query for the given resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function indexQuery(NovaRequest $request, $query)
    {
        if (static::$defaultSort && empty($request->get('orderBy'))) {
            $query->getQuery()->orders = [];
            return $query->orderBy(static::$defaultSort);
        }
        return $query;
    }

    public static $group = 'Auxiliares';

    public static $perPageViaRelationship = 10;

    public static function label() {
        return 'Municipios';
    }

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Municipality::class;

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
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            // ID::make(__('ID'), 'id')->sortable(),
            Text::make('Nombre', 'name')
                ->sortable(),
            BelongsTo::make('Provincia', 'province', 'App\Nova\Province')
                ->sortable(),
            Number::make('Población', 'population')
                ->sortable(),
            Number::make('Masculino', 'male')
                ->sortable(),
            Number::make('Femenino', 'female')
                ->sortable(),
            HasMany::make('CDRs', 'cdrs', 'App\Nova\Cdr')
                ->sortable(),
            HasMany::make('Localidades', 'localities', 'App\Nova\Locality')
                ->sortable()
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
        return [
            new ByProvince,
        ];
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
