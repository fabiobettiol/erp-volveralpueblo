<?php

use App\Http\Controllers\MapController;
use App\Models\Business;
use App\Models\Cdr;
use App\Models\Cdrtype;
use App\Models\House;
use App\Models\Job;
use App\Models\Land;
use App\Models\Region;
use Elibyy\TCPDF\Facades\TCPDF;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

require __DIR__ . '/auth.php';

Route::get('/test', function () {
	return view('reportes.pdf-houses');
});

Route::get('/', function () {

	return;

	$files = Storage::allFiles('public/backgrounds');
	$bg = $files[rand(0, count($files) - 1)];

	return view('welcome')->with([
		'background' => $bg,
	]);
});

Route::get('/informacion', function () {
	return view('informacion');
})->middleware(['auth', 'verified'])
	->name('informacion');

Route::get('/formulario', function () {
	return view('userform');
})->middleware(['auth', 'verified'])
	->name('formulario');

Route::get('/mapa/{recurso?}', [MapController::class, 'show'])
	->name('mapa');

// - Ruta para incrustar los CDRs como una página en las webs
Route::get('/collaborators/{origen?}', function ($origen = null) {

	$types = Cdrtype::all();

	$cdrs = Cdr::with(['community', 'province', 'municipality', 'cdrtype'])
		->where('active', 1)
		->when($origen, function ($query) use ($origen) {
			return $query->where('web_' . $origen, '=', 1);
		})
		->orderBy('name')
		->get();

	return view('collaborators')
		->with([
			'cdrs' => $cdrs,
			'types' => $types,
		]);
});

// - Ruta para incrustar un CDRs como módulo en las webs, a partir de la comarca
Route::get('/cdr/{comarca}', function ($comarca = null) {

	$comarcas = Region::where('comarca', $comarca)->get();

	dd($comarcas);

	if ($comarcas->cdr) {
		return view('cdr')->with(['cdr' => $cdr]);
	} else {
		return;
	}
});

/*
|--------------------------------------------------------------------------
| INACTVE (TEST) Routes
|--------------------------------------------------------------------------
 */

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

// Route::get('/registro', function () {

// 	return view('userform');

// });

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

// - Ruta para importar las comarcas desde un KML
/*Route::get('/comarcas-mapa', function() {

$parser = Parser::fromFile('comarcas.kml');
$kml = $parser->getKml();
$document = $kml->getDocument();
$folders = $document->getFolders();

foreach ($folders as $folder) {
$placemarks = $folder->getPlacemarks();

foreach ($placemarks as $placemark) {

$region = new Region;
$region->polygon = '[ ';
$info = $placemark->getExtendedData()->getSchemaData()->getSimpleData();

$region->comarca = $info[7]->getValue();
$region->community_id = $info[3]->getValue();
$region->province_id = $info[5]->getValue();

if (array_key_exists(8, $info)) {
$region->name = $info[8]->getValue();
} else {
$region->name = $info[6]->getValue();
}

$multiGeometries = $placemark->getMultiGeometry();
$polygons = $multiGeometries->getPolygons();

// foreach ($polygons as $polygon) {

$polygon = $polygons[0];

$coordinates = $polygon->getOuterBoundaryIs()
->getLinearRing()
->getCoordinates();

$pairs = explode(' ', $coordinates->getElement()->getValue());

foreach ($pairs as $pair) {
$latLng = explode(',', $pair);
$region->polygon.= '{ lat: ' . $latLng[1] . ', lng: '. $latLng[0] .' },';
}
// }

$region->polygon.= ' ]';
$region->save();
}

die('------ FIN -------');
}
});*/

// - Ruta para pruebas de SendInBlue --- ELIMINAR
/*Route::get('/nuevo', function () {

$config = SendinBlue\Client\Configuration::getDefaultConfiguration()->setApiKey('api-key', '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');

$apiInstance = new SendinBlue\Client\Api\ContactsApi(
new GuzzleHttp\Client(),
$config
);
$createContact = new \SendinBlue\Client\Model\CreateContact(); // Values to create a contact
$createContact['email'] = 'prueba2@fabiobettiol.com';
$createContact['attributes'] = [
'NOMBRE' => 'NOMBREEE2',
'APELLIDOS' => 'APELLIDOSSS2',
];
$createContact['listIds'] = [3];

try {
$result = $apiInstance->createContact($createContact);
dd($result);
} catch (Exception $e) {
dump('ERROR', $e);
return;
echo 'Exception when calling ContactsApi->createContact: ', $e->getMessage(), PHP_EOL;
}

$userId = $result['id'];

});

Route::get('/detalles', function () {

$config = SendinBlue\Client\Configuration::getDefaultConfiguration()->setApiKey('api-key', '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');

$apiInstance = new SendinBlue\Client\Api\ContactsApi(
new GuzzleHttp\Client(),
$config
);

$identifier = 'arturoo151515@gmail.com';

try {
$result = $apiInstance->getContactInfo($identifier);
dd($result);
} catch (Exception $e) {
echo 'Exception when calling ContactsApi->getContactInfo: ', $e->getMessage(), PHP_EOL;
}
});*/
