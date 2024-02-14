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
        return [
            //
        ];
    }
}
