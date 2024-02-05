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

class CdrStatsController extends Controller
{
    public $cdr;
    public $desde;
    public $hasta;
    public $numSolicitantes;
    public $numInteracciones;
    public $numFamilias;
    public $numMiembros;
    public $numIntervenciones;
    public $numSeguimientos;
    public $numViviendas;
    public $numNegocios;
    public $numTierras;
    public $numTrabajos;

    public function init($cdr, $desde, $hasta) {
        $this->cdr = $cdr;
        $this->desde = $desde;
        $this->hasta = $hasta;

        // - Solicitantes (demndantes)
        $this->numInteracciones = $this->numInteracciones();
        $this->numSolicitantes = $this->numSolicitantes();

        // - Asentados (familias)
        $this->numFamilias = $this->numFamilias();
        $this->numMiembros = $this->numMiembros();
        $this->numIntervenciones = $this->numIntervenciones();
        $this->numSeguimientos = $this->numSeguimientos();

        // - Recuros
        $this->numViviendas = $this->numViviendas();
        $this->numNegocios = $this->numNegocios();
        $this->numTierras = $this->numTierras();
        $this->numTrabajos = $this->numTrabajos();


    ddd($this->numInteracciones,$this->numSolicitantes,$this->numFamilias,$this->numMiembros,$this->numIntervenciones,$this->numSeguimientos,$this->numViviendas,$this->numNegocios,$this->numTierras,$this->numTrabajos);

    }

    protected function numInteracciones() {
        return Demandantfollowup::where('cdr_id', $this->cdr)
            ->whereDate('date', '>=', $this->desde)
            ->whereDate('date', '<=', $this->hasta)
            ->count();
    }

    protected function numSolicitantes() {
        return Demandantfollowup::distinct('demandant_id')
            ->where('cdr_id', $this->cdr)
            ->whereDate('date', '>=', $this->desde)
            ->whereDate('date', '<=', $this->hasta)
            ->count();
    }

    protected function numFamilias() {
        return Family::where('cdr_id', $this->cdr)
            ->whereDate('settlementdate', '>=', $this->desde)
            ->whereDate('settlementdate', '<=', $this->hasta)
            ->count();
    }

    protected function numMiembros() {
        return Familymember::where('cdr_id', $this->cdr)
            ->whereHas('family', function($query) {
                $query->where('settlementdate', '>=', $this->desde);
                $query->where('settlementdate', '<=', $this->hasta);
            })
        ->count();
    }

    protected function numIntervenciones() {
        return Familycontact::where('cdr_id', $this->cdr)
            ->whereDate('date', '>=', $this->desde)
            ->whereDate('date', '<=', $this->hasta)
            ->count();
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
