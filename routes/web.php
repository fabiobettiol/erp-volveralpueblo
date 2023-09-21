<?php

use App\Models\Job;
use App\Models\Land;
use App\Models\House;
use App\Models\Business;
use Elibyy\TCPDF\Facades\TCPDF;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


Route::get('/mapa-comarcas', function () {
	$comarcas = Region::with('community', 'province')
		->where('visible', true)
		->get();

	$cdrs = Cdr::with('cdrtype', 'community', 'province')
		->where('active', '1')
		->where('mapinfo', '1')
		->get();

	return view('comarcas-map')->with([
		'comarcas' => $comarcas,
		'cdrs' => $cdrs,
	]);
});

Route::get('/reporte/{recurso}/{mes}/{ano}', function ($recurso, $mes, $ano) {

	if ($recurso == 'viviendas') {

		$models = getHouses($mes, $ano);

		$filename = 'Viviendas.pdf';

		$view = \View::make('reportes.pdf-house', compact('models'));
		$html = $view->render();

		$pdf = new TCPDF;

		$pdf::SetTitle('Viviendas');
		$pdf::AddPage();
		$pdf::writeHTML($html, true, false, true, false, '');

		$pdf::Output(storage_path('app/public/pdf/' . $filename), 'D');

	} elseif ($recurso == 'trabajos') {

		$models = getJobs($mes, $ano);
		$filename = 'Trabajos.pdf';

		$view = \View::make('reportes.pdf-job', compact('models'));
		$html = $view->render();

		$pdf = new TCPDF;

		$pdf::SetTitle('Trabajos');
		$pdf::AddPage();
		$pdf::writeHTML($html, true, false, true, false, '');

		$pdf::Output(storage_path('app/public/pdf/' . $filename), 'D');

	} elseif ($recurso == 'negocios') {

		$models = getBusinesses($mes, $ano);
		$filename = 'Negocios.pdf';

		$view = \View::make('reportes.pdf-business', compact('models'));
		$html = $view->render();

		$pdf = new TCPDF;

		$pdf::SetTitle('Negocios');
		$pdf::AddPage();
		$pdf::writeHTML($html, true, false, true, false, '');

		$pdf::Output(storage_path('app/public/pdf/' . $filename), 'D');

	} elseif ($recurso == 'tierras') {

		$models = getLands($mes, $ano);
		$filename = 'Land.pdf';

		$view = \View::make('reportes.pdf-land', compact('models'));
		$html = $view->render();

		$pdf = new TCPDF;

		$pdf::SetTitle('Land');
		$pdf::AddPage();
		$pdf::writeHTML($html, true, false, true, false, '');

		$pdf::Output(storage_path('app/public/pdf/' . $filename), 'D');
	}

});

/* --------------------------------------------------------------------------------- */

function getHouses($mes, $ano) {

	$lista = [];

	$houses = House::with(['cdr', 'community', 'province', 'municipality', 'form'])
		->where('available', '1')
		->whereYear('updated_at', '=', $ano)
		->whereMonth('updated_at', '=', $mes)
		->orderBy('community_id')
		->orderBy('province_id')
		->get();

	return $houses;

}

function getJobs($mes, $ano) {

	$lista = [];
	$jobs = Job::with(['cdr', 'community', 'province', 'municipality', 'jobform'])
		->where('available', '1')
		->whereYear('updated_at', '=', $ano)
		->whereMonth('updated_at', '=', $mes)
		->orderBy('community_id')
		->orderBy('province_id')
		->get();

	return $jobs;
}

function getBusinesses($mes, $ano) {

	$lista = [];
	$businesses = Business::with(['cdr', 'community', 'province', 'municipality', 'form'])
		->where('available', '1')
		->whereYear('updated_at', '=', $ano)
		->whereMonth('updated_at', '=', $mes)
		->orderBy('community_id')
		->orderBy('province_id')
		->get();

	return $businesses;
}

function getLands($mes, $ano) {

	$lista = [];
	$lands = Land::with(['cdr', 'community', 'province', 'municipality', 'form', 'arearange', 'landuse', 'landtype'])
		->where('available', '1')
		->whereYear('updated_at', '=', $ano)
		->whereMonth('updated_at', '=', $mes)
		->orderBy('community_id')
		->orderBy('province_id')
		->get();

	return $lands;
}