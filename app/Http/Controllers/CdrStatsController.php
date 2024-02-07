<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\Land;
use App\Models\House;
use App\Models\Family;
use App\Models\Business;
use App\Models\Familymember;
use Illuminate\Http\Request;
use App\Models\Familycontact;
use App\Models\Familyfollowup;
use App\Models\Demandantfollowup;
use App\Models\Demandant;

class CdrStatsController extends Controller
{
    public $cdr;
    public $desde;
    public $hasta;
    public $solicitantes;
    public $numSolicitantes;
    public $interacciones;
    public $numInteracciones;
    public $familias;
    public $numFamilias;
    public $miembros;
    public $numMiembros;
    public $intervenciones;
    public $numIntervenciones;
    public $seguimientos;
    public $numSeguimientos;
    public $numViviendas;
    public $numNegocios;
    public $numTierras;
    public $numTrabajos;



    public function init($cdr, $desde, $hasta) {
        $this->cdr = $cdr;
        $this->desde = $desde;
        $this->hasta = $hasta;

        // - Solicitantes (demandantes)
        $this->numInteracciones = $this->numInteracciones();
        $this->numSolicitantes = $this->numSolicitantes();
        $this->solicitantes = $this->solicitantes();
        $this->interacciones = $this->interacciones();

        // - Asentados (familias)
        $this->numFamilias = $this->numFamilias();
        $this->familias = $this->familias();
        $this->miembros = $this->miembros();
        $this->numMiembros = $this->numMiembros();
        $this->intervenciones = $this->intervenciones();
        $this->numIntervenciones = $this->numIntervenciones();
        $this->seguimientos = $this->seguimientos();
        $this->numSeguimientos = $this->numSeguimientos();

        // - Recuros
        $this->numViviendas = $this->numViviendas();
        $this->numNegocios = $this->numNegocios();
        $this->numTierras = $this->numTierras();
        $this->numTrabajos = $this->numTrabajos();

        return view('stats.cdr-stats', [
            'solicitantes' => $this->solicitantes,
            'numSolicitantes' => $this->numSolicitantes,
            'interacciones' => $this->interacciones,
            'numInteracciones' => $this->numInteracciones,

            'familias' => $this->familias,
            'numFamilias' => $this->numFamilias,
            'miembros' => $this->miembros,
            'numMiembros' => $this->numMiembros,
            'intervenciones' => $this->intervenciones,
            'numIntervenciones' => $this->numIntervenciones,
            'seguimientos' => $this->seguimientos,
            'numSeguimientos' => $this->numSeguimientos,

            'numViviendas' => $this->numViviendas,
            'numNegocios' => $this->numNegocios,
            'numTierras' => $this->numTierras,
            'numTrabajos' => $this->numTrabajos
        ]);

    }

    protected function solicitantes() {
        return Demandant::whereHas('followups', function($query) {
                $query->where('cdr_id', $this->cdr);
                $query->whereDate('created_at', '>=', $this->desde);
                $query->whereDate('created_at', '<=', $this->hasta);
            })
            ->withCount('followups')
            ->with('country:id,alfa3','provinceto:id,acronym')
            ->get();
    }

    protected function numSolicitantes() {
        return Demandantfollowup::distinct('demandant_id')
            ->where('cdr_id', $this->cdr)
            ->whereDate('date', '>=', $this->desde)
            ->whereDate('date', '<=', $this->hasta)
            ->count();
    }

    protected function interacciones() {
        return Demandantfollowup::with('demandant:id,name,surname')
            ->where('cdr_id', $this->cdr)
            ->whereDate('date', '>=', $this->desde)
            ->whereDate('date', '<=', $this->hasta)
            ->get();
    }

    protected function numInteracciones() {
        return Demandantfollowup::where('cdr_id', $this->cdr)
            ->whereDate('date', '>=', $this->desde)
            ->whereDate('date', '<=', $this->hasta)
            ->count();
    }
    protected function familias() {
        return Family::with(
                'settlementstatus:id,name',
                'destinationprovince:id,acronym',
                'nationality:id,alfa3'
            )->withCount('members')
            ->withCount('contacts')
            ->where('cdr_id', $this->cdr)
            ->whereDate('settlementdate', '>=', $this->desde)
            ->whereDate('settlementdate', '<=', $this->hasta)
            ->get();
    }


    protected function numFamilias() {
        return Family::where('cdr_id', $this->cdr)
            ->whereDate('settlementdate', '>=', $this->desde)
            ->whereDate('settlementdate', '<=', $this->hasta)
            ->count();
    }

    protected function miembros() {
        return Familymember::with('family:id,reference')
            ->whereHas('family', function($query) {
                $query->where('settlementdate', '>=', $this->desde);
                $query->where('settlementdate', '<=', $this->hasta);
            })->where('cdr_id', $this->cdr)
        ->get();
    }

    protected function numMiembros() {
        return Familymember::where('cdr_id', $this->cdr)
            ->whereHas('family', function($query) {
                $query->where('settlementdate', '>=', $this->desde);
                $query->where('settlementdate', '<=', $this->hasta);
            })
        ->count();
    }

    protected function intervenciones() {
        return Familycontact::with('family:id,reference')
            ->where('cdr_id', $this->cdr)
            ->whereDate('date', '>=', $this->desde)
            ->whereDate('date', '<=', $this->hasta)
            ->get();
    }

    protected function numIntervenciones() {
        return Familycontact::where('cdr_id', $this->cdr)
            ->whereDate('date', '>=', $this->desde)
            ->whereDate('date', '<=', $this->hasta)
            ->count();
    }

    protected function seguimientos() {
        return Familyfollowup::with('family:id,reference')
            ->where('cdr_id', $this->cdr)
            ->whereDate('date', '>=', $this->desde)
            ->whereDate('date', '<=', $this->hasta)
            ->get();
    }

    protected function numSeguimientos() {
        return Familyfollowup::where('cdr_id', $this->cdr)
            ->whereDate('date', '>=', $this->desde)
            ->whereDate('date', '<=', $this->hasta)
            ->count();
    }

    protected function numViviendas() {
        return House::where('cdr_id', $this->cdr)
            ->whereDate('created_at', '>=', $this->desde)
            ->whereDate('created_at', '<=', $this->hasta)
            ->count();
    }

    protected function numNegocios() {
        return Business::where('cdr_id', $this->cdr)
            ->whereDate('created_at', '>=', $this->desde)
            ->whereDate('created_at', '<=', $this->hasta)
            ->count();
    }

    protected function numTierras() {
        return Land::where('cdr_id', $this->cdr)
            ->whereDate('created_at', '>=', $this->desde)
            ->whereDate('created_at', '<=', $this->hasta)
            ->count();
    }

    protected function numTrabajos() {
        return Job::where('cdr_id', $this->cdr)
            ->whereDate('created_at', '>=', $this->desde)
            ->whereDate('created_at', '<=', $this->hasta)
            ->count();
    }
}
