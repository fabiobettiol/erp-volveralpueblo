<?php

namespace App\Nova\Actions;

use Illuminate\Bus\Queueable;
use Elibyy\TCPDF\Facades\TCPDF;
use Laravel\Nova\Actions\Action;
use Illuminate\Support\Collection;
use Laravel\Nova\Fields\ActionFields;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Laravel\Nova\Http\Requests\NovaRequest;

class JobExport extends Action
{
    use InteractsWithQueue, Queueable;

    public $name = 'Exportar PDF Empleos';

    public function handle(ActionFields $fields, Collection $models)
    {
		$filename = 'Empleos.pdf';

		$view = \View::make('reportes.pdf-job', compact('models'));
		$html = $view->render();

		$pdf = new TCPDF;

		$pdf::SetTitle('Empleos');
		$pdf::AddPage();
		$pdf::writeHTML($html, true, false, true, false, '');

		$pdf::Output(storage_path('app/public/pdf/' . $filename), 'F');

		return Action::download(env('APP_URL') . '/storage/pdf/' . $filename, $filename);
    }

    /**
     * Get the fields available on the action.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [];
    }
}
