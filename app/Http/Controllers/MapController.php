<?php

namespace App\Http\Controllers;


use App\Models\Cdr;
use App\Models\Job;
use App\Models\Form;
use App\Models\Land;
use App\Models\House;
use App\Models\Sector;
use App\Models\Jobform;
use App\Models\Business;
use App\Models\Community;
use Illuminate\Http\Request;

class MapController extends Controller
{
    public function show($resource = NULL) {

        // - Filtering selectors
        $comunities = Community::orderBy('name')->get();
        $sectors = Sector::orderBy('name')->get();
        $forms = Form::orderBy('name')->get();
        $jobForms = Jobform::orderBy('name')->get();

        $list = [];

        $resources['Business']  = Business::with(['Community:id,name', 'Province:id,name'])
            ->where('available','1')
            ->when($resource, function($query) use ($resource) {
                return $query->where('reference', '=', $resource);
            })
            ->get(['id', 'reference','community_id', 'province_id', 'town', 'postcode', 'form_id',  'sector_id', 'lat', 'lng']);
        $resources['House'] = House::with(['Community:id,name', 'Province:id,name'])
            ->where('available','1')
            ->when($resource, function($query) use ($resource) {
                return $query->where('reference', '=', $resource);
            })            
            ->get(['id', 'reference','community_id', 'province_id', 'town', 'postcode', 'form_id',  'lat', 'lng']);
        $resources['Land']   = Land::with(['Community:id,name', 'Province:id,name'])
            ->where('available','1')
            ->when($resource, function($query) use ($resource) {
                return $query->where('reference', '=', $resource);
            })            
            ->get(['id', 'reference','community_id', 'province_id', 'town', 'postcode', 'form_id', 'lat', 'lng']);
        $resources['Job']  = Job::with(['Community:id,name', 'Province:id,name'])
            ->where('available','1')
            ->when($resource, function($query) use ($resource) {
                return $query->where('reference', '=', $resource);
            })            
            ->get(['id', 'reference','community_id', 'province_id', 'town', 'postcode', 'jobform_id', 'sector_id', 'lat', 'lng']);

        foreach ($resources as $key => $resource) { 
            foreach ($resource as $res) {

                if($key != 'Job') {
                    $form = $res->form_id;
                } else {
                    $form = $res->jobform_id;
                }

                if($key == 'Job' || $key == 'Business') {
                    $sector = $res->sector_id;
                } else {
                    $sector = null;
                }

                $list[] = [
                    'type' => $key,
                    'id' => $res->id,
                    'reference' => $res->reference,
                    'community' => $res->community_id,
                    'ccaa' => $res->community->name,
                    'province' => $res->province->name,
                    'town' => $res->town,
                    'postcode' => $res->postcode,
                    'form' => $res->form_id,
                    'jobform' => $res->jobform_id,
                    'sector' => $sector,
                    'lat' => $res->lat,
                    'lng' => $res->lng
                ];
            }
        }

        return view('mapa', [
            'markers' => $list,
            'communities' => $comunities,
            'sectors' => $sectors,
            'forms' => $forms,
            'jobForms' => $jobForms
        ]);

    }
}
