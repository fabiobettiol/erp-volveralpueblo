<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Community;
use App\Models\Land;
use App\Models\House;
use App\Models\Job;
use App\Models\Business;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('/resource-info/{type}/{id}', function ($resource, $id) {

    if ($resource == 'House') {
        return getHouse($id);
    } elseif ($resource == 'Business') {
        return getBusiness($id);
    } elseif ($resource == 'Land') {
        return getLand($id);
    } elseif ($resource == 'Job') {
        return getJob($id);
    } else {
        return response()->json('Error', 404);
    }
});

Route::get('/map/community/{ccaa}/resources/{type}', function ($community, $type) {

    $retorno = [];

    $retorno['community'] = Community::select(['id','name'])->find($community);
    $retorno['resource'] = $type;

    if ($type == 'all') {
        $retorno['data']['lands'] = getLands($community);
        $retorno['data']['houses'] = getHouses($community);
        $retorno['data']['jobs'] = getJobs($community);
        $retorno['data']['business'] = getBusiness($community);
    
    } else {
        switch ($type) {
            case 'lands':
                $retorno['data']['lands'] = getLands($community);
                break;

            case 'houses':
                $retorno['data']['houses'] = getHouses($community);
                break;

            case 'jobs':
                $retorno['data']['jobs'] = getJobs($community);
                break;

            case 'business':
                $retorno['data']['business'] = getBusiness($community);
                break;              
            
            default:
                // code...
                break;
        }
    }

    return $retorno;    
});


// ---------------------------------------------------------------------------
function getLand($id) {

    $land = Land::with([
        'CDR:id,name,address,city,pc,email,web,link,link_title,phone,logo,schedule', 
        'Community:id,name',
        'Province:id,name',
        'Municipality:id,name',
        'Form:id,name',
        'Ownership:id,name',
        'Landtype:id,type',
        'Landuse:id,use',
        'Pricerange:id,range',
        'Arearange:id,range'
    ])->select(
        'id',
        'reference',
        'cdr_id',
        'community_id',
        'province_id',
        'municipality_id',
        'town',
        'population',
        'description',
        'form_id',
        'ownership_id',
        'arearange_id',
        'landtype_id',
        'landuse_id',
        'pricerange_id',
    )->findOrFail($id); 

    $fotos = [];
    foreach ($land->getMedia('fotos') as $foto) {
        $fotos[] = [
            'thumb' => $foto->getUrl('thumb'),
            'resized' => $foto->getUrl('resized')
        ];
    }
    $land->fotos = $fotos;

    return $land;
}

// ---------------------------------------------------------------------------
function getHouse($id) {

    $house = House::with([
        'CDR:id,name,address,city,pc,email,web,link,link_title,phone,logo,schedule', 
        'Community:id,name',
        'Province:id,name',
        'Municipality:id,name',
        'Form:id,name',
        'Ownership:id,name',
        'Status:id,status',
        'Pricerange:id,range'
    ])->select(
        'id',
        'reference',
        'cdr_id',
        'community_id',
        'province_id',
        'municipality_id',
        'town',
        'description',
        'form_id',
        'ownership_id',
        'population',
        'stories',
        'total_rooms',
        'bedrooms',
        'bathrooms',
        'status_id',
        'courtyard',
        'courtyard_detail',
        'stables',
        'stables_detail',
        'lands',
        'lands_detail',
        'tobusiness',
        'tobusiness_detail',
        'pricerange_id'
    )->findOrFail($id); 

    $fotos = [];
    foreach ($house->getMedia('fotos') as $foto) {
        $fotos[] = [
            'thumb' => $foto->getUrl('thumb'),
            'resized' => $foto->getUrl('resized')
        ];
    }
    $house->fotos = $fotos; 

    return $house;
}

// ---------------------------------------------------------------------------
function getJob($id) {

    $job = Job::with([
        'CDR:id,name,address,city,pc,email,web,link,link_title,phone,logo,schedule', 
        'Community:id,name',
        'Province:id,name',
        'Municipality:id,name',
        'Sector:id,name',
        'Jobform:id,name',
        'Jobownership:id,name',
    ])->select(
        'id',
        'reference',
        'cdr_id',
        'community_id',
        'province_id',
        'municipality_id',
        'town',
        'population',
        'sector_id',
        'description',
        'jobform_id',
        'jobownership_id',
        'position',
        'requirements',
        'description'
    )->findOrFail($id); 

    $fotos = [];
    foreach ($job->getMedia('fotos') as $foto) {
        $fotos[] = [
            'thumb' => $foto->getUrl('thumb'),
            'resized' => $foto->getUrl('resized')
        ];
    }
    $job->fotos = $fotos;

    return $job;    
}

// ---------------------------------------------------------------------------
function getBusiness($id) {

    $business = Business::with([
        'CDR:id,name,address,city,pc,email,web,link,link_title,phone,logo,schedule', 
        'Community:id,name',
        'Province:id,name',
        'Municipality:id,name',
        'Sector:id,name',
        'Form:id,name',
        'Ownership:id,name',
        'Pricerange:id,range'
    ])->select(
        'id',
        'reference',
        'cdr_id',
        'community_id',
        'province_id',
        'municipality_id',
        'town',
        'population',
        'sector_id',
        'description',
        'form_id',
        'ownership_id',
        'pricerange_id',
        'property_type',
        'terms',
        'deadlines'
    )->findOrFail($id); 

    $fotos = [];
    foreach ($business->getMedia('fotos') as $foto) {
        $fotos[] = [
            'thumb' => $foto->getUrl('thumb'),
            'resized' => $foto->getUrl('resized')
        ];
    }
    $business->fotos = $fotos;

    return $business;
}