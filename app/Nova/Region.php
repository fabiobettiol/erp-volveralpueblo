<?php

namespace App\Nova;

use App\Nova\Filters\ByCdr;
use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;
use App\Nova\Filters\ByProvince;
use Laravel\Nova\Fields\Boolean;
use App\Nova\Filters\ByCommunity;
use Laravel\Nova\Fields\Textarea;
use App\Nova\Filters\ByVisibility;
use Laravel\Nova\Fields\BelongsTo;
use Illuminate\Support\Facades\Auth;
use Laravel\Nova\Http\Requests\NovaRequest;

class Region extends Resource
{
    public static function label() {
        return 'Comarcas';
    }

    public static $group = 'Auxiliares';

    public static function availableForNavigation(Request $request)
    {
        return $request->user()->is_admin;
    }

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Region::class;

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
            //ID::make(__('ID'), 'id')->sortable(),
            Text::make('Nombre', 'name')->sortable(),
            Text::make('Código', 'comarca'),
            BelongsTo::make('Communidad', 'community', 'App\Nova\Community'),
            BelongsTo::make('Provincia', 'province', 'App\Nova\Province'),
            BelongsTo::make('CDR', 'cdr'),
            Boolean::make('Visible', 'visible'),
            Textarea::make('Polígono', 'polygon'),
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
        $user = Auth::user();

        $retorno = [

            new ByVisibility,
            new ByCommunity,
            new ByProvince,

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
