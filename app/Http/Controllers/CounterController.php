<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\Land;
use App\Models\House;
use App\Models\Family;
use App\Models\Business;
use App\Models\Demandant;
use Illuminate\Http\Request;

class CounterController extends Controller
{
    public function count() {
        $counters = [
            'houses' => House::where('available', true)->count(),
            'jobs' => Job::where('available', true)->count(),
            'lands' => Land::where('available', true)->count(),
            'businesses' => Business::where('available', true)->count(),
            'families' => Family::count(), // Status 2 = Asentados
            'demandants' => Demandant::count(),
        ];

        return view('counters', ['counters' => $counters]);
    }
}
