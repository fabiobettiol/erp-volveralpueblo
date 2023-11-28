<?php

namespace App\Nova\Dashboards;

use Laravel\Nova\Cards\Help;
use App\Nova\Metrics\Demandants;
use App\Nova\Metrics\DemandantsFollowups;
use Laravel\Nova\Dashboards\Main as Dashboard;

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
            // new Help,
            (new Demandants)->width('1/4'),
            (new DemandantsFollowups)->width('1/4'),
        ];
    }
}
