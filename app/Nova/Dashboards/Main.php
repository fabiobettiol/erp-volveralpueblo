<?php

namespace App\Nova\Dashboards;

use App\Models\Family;
use Laravel\Nova\Cards\Help;
use App\Nova\Metrics\Demandants;
use Illuminate\Support\Facades\Auth;
use App\Nova\Metrics\DemandantsFollowups;
use Stepanenko3\NovaCards\Cards\HtmlCard;
use Laravel\Nova\Dashboards\Main as Dashboard;
use Coroowicaksono\ChartJsIntegration\StackedChart;


class Main extends Dashboard
{
    /**
     * Get the cards for the dashboard.
     *
     * @return array
     */
    public function cards()
    {

        // $thisCdr = Auth::user()->cdr_id;
        // $thisYear = now()->year;


        // $numFamilies = Family::where('cdr_id', 22)
        //     ->whereYear('settlementdate', 2024)
        //     ->count();

        // return [
        //     (new HtmlCard)
        //         ->width('1/3')
        //         ->markdown('# Hello World!'), // Required

        //     (new HtmlCard)
        //         ->width('1/3')
        //         ->view('cards.families', ['count' => $numFamilies]),
        // ];
    }
}
