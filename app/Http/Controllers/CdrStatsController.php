<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Cdr;
use App\Models\Job;
use App\Models\Land;
use App\Models\House;
use App\Models\Family;
use App\Models\Business;
use App\Models\Demandant;
use App\Models\Familymember;
use Illuminate\Http\Request;
use App\Models\Familycontact;
use App\Models\Familyfollowup;
use App\Models\Demandantfollowup;


class CdrStatsController extends Controller
{
    public $cdr;
    public $ano;
    public $mes;
    public $trimestre;
    public $semestre;
    public $solicitantes;
    public $interacciones;
    public $familias;
    public $miembros;
    public $intervenciones;
    public $seguimientos;
    public $viviendas;
    public $negocios;
    public $tierras;
    public $trabajos;
    public $trimestres;
    public $semestres;

    public function filter($cdr) {
        return view ('stats.cdr-stats-init', [
            'cdr' => $cdr
        ]);
    }

    public function init(Request $request) {

        $this->cdr = $request->cdr;
        $this->cdrInfo = Cdr::find($this->cdr);
        $this->ano = $request->filtro_ano;
        $this->mes = $request->filtro_mes;
        $this->trimestre = $request->filtro_trimestre;
        $this->semestre = $request->filtro_semestre;

        $this->trimestres = [
            '1' => [
                'desde' => $this->ano . '/01/01',
                'hasta' => $this->ano . '/03/31',
            ],
            '2' => [
                'desde' => $this->ano . '/04/01',
                'hasta' => $this->ano . '/06/30',
            ],
            '3' => [
                'desde' => $this->ano . '/07/01',
                'hasta' => $this->ano . '/09/31',
            ],
            '4' => [
                'desde' => $this->ano . '/10/01',
                'hasta' => $this->ano . '/12/31',
            ]
        ];

        $this->semestres = [
            '1' => [
                'desde' => $this->ano . '/01/01',
                'hasta' => $this->ano . '/06/30',
            ],
            '2' => [
                'desde' => $this->ano . '/07/01',
                'hasta' => $this->ano . '/12/31',
            ]
        ];

        // - Solicitantes (demandantes)
        $this->solicitantes = $this->solicitantes();
        $this->interacciones = $this->interacciones();

        // - Asentados (familias)
        $this->familias = $this->familias();
        $this->miembros = $this->miembros();
        $this->intervenciones = $this->intervenciones();
        $this->seguimientos = $this->seguimientos();

        // - Recuros
        $this->viviendas = $this->viviendas();
        $this->negocios = $this->negocios();
        $this->tierras = $this->tierras();
        $this->trabajos = $this->trabajos();

        return view('stats.cdr-stats', [
            'cdrInfo' => $this->cdrInfo,
            'solicitantes' => $this->solicitantes,
            'interacciones' => $this->interacciones,

            'familias' => $this->familias,
            'miembros' => $this->miembros,
            'intervenciones' => $this->intervenciones,
            'seguimientos' => $this->seguimientos,

            'viviendas' => $this->viviendas,
            'negocios' => $this->negocios,
            'tierras' => $this->tierras,
            'trabajos' => $this->trabajos
        ]);

    }

    protected function solicitantes() {
        return Demandant::whereHas('followups', function($query) {
            $query->where('cdr_id', $this->cdr);
            $query->whereYear('date', $this->ano);
            if (!empty($this->mes)) {
                $query->whereMonth('date', $this->mes);
            } elseif (!empty($this->trimestre)) {
                $query->where('date', '>=', $this->trimestres[$this->trimestre]['desde']);
                $query->where('date', '<=', $this->trimestres[$this->trimestre]['hasta']);
            } elseif (!empty($this->semestre)) {
                $query->where('date', '>=', $this->semestres[$this->semestre]['desde']);
                $query->where('date', '<=', $this->semestres[$this->semestre]['hasta']);
            }
        })->withCount(['followups' => function ($query) {
            $query->where('cdr_id', $this->cdr);
        }])->with('country:id,alfa3','provinceto:id,acronym')
        ->get();
    }

    protected function interacciones() {
        return Demandantfollowup::with('demandant:id,name,surname')
            ->where('cdr_id', $this->cdr)
            ->whereYear('date', $this->ano)
            ->when(!empty($this->mes), function ($q) {
                return $q->whereMonth('date', $this->mes);
            })
            ->when(!empty($this->trimestre), function ($q) {
                return $q->where('date', '>=', $this->trimestres[$this->trimestre]['desde'])
                    ->where('date', '<=', $this->trimestres[$this->trimestre]['hasta']);
            })
            ->when(!empty($this->semestre), function ($q) {
                return $q->where('date', '>=', $this->semestres[$this->semestre]['desde'])
                    ->where('date', '<=', $this->semestres[$this->semestre]['hasta']);
            })->get();
    }

    protected function familias() {
        return Family::with(
                'settlementstatus:id,name',
                'destinationprovince:id,acronym',
                'nationality:id,alfa3'
            )->withCount('members')
            ->withCount('contacts')
            ->where('cdr_id', $this->cdr)
            ->whereYear('settlementdate', $this->ano)
            ->when(!empty($this->mes), function ($q) {
                return $q->whereMonth('settlementdate', $this->mes);
            })
            ->when(!empty($this->trimestre), function ($q) {
                return $q->where('settlementdate', '>=', $this->trimestres[$this->trimestre]['desde'])
                    ->where('settlementdate', '<=', $this->trimestres[$this->trimestre]['hasta']);
            })
            ->when(!empty($this->semestre), function ($q) {
                return $q->where('settlementdate', '>=', $this->semestres[$this->semestre]['desde'])
                    ->where('settlementdate', '<=', $this->semestres[$this->semestre]['hasta']);
            })->get();
    }

    protected function miembros() {
        return Familymember::with('family:id,reference')
            ->whereHas('family', function($query) {
                $query->whereYear('settlementdate', $this->ano);
                $query->when(!empty($this->mes), function ($q) {
                    return $q->whereMonth('settlementdate', $this->mes);
                });
                $query->when(!empty($this->trimestre), function ($q) {
                    return $q->where('settlementdate', '>=', $this->trimestres[$this->trimestre]['desde'])
                        ->where('settlementdate', '<=', $this->trimestres[$this->trimestre]['hasta']);
                });
                $query->when(!empty($this->semestre), function ($q) {
                    return $q->where('settlementdate', '>=', $this->semestres[$this->semestre]['desde'])
                        ->where('settlementdate', '<=', $this->semestres[$this->semestre]['hasta']);
                });
            })->where('cdr_id', $this->cdr)->get();
    }

    protected function intervenciones() {
        return Familycontact::with('family:id,reference')
            ->whereYear('date', $this->ano)
            ->where('cdr_id', $this->cdr)
            ->when(!empty($this->mes), function ($q) {
                return $q->whereMonth('date', $this->mes);
            })
            ->when(!empty($this->trimestre), function ($q) {
                return $q->where('date', '>=', $this->trimestres[$this->trimestre]['desde'])
                    ->where('date', '<=', $this->trimestres[$this->trimestre]['hasta']);
            })
            ->when(!empty($this->semestre), function ($q) {
                return $q->where('date', '>=', $this->semestres[$this->semestre]['desde'])
                    ->where('date', '<=', $this->semestres[$this->semestre]['hasta']);
            })->get();
    }

    protected function seguimientos() {
        return Familyfollowup::with('family:id,reference')
            ->where('cdr_id', $this->cdr)
            ->whereYear('date', $this->ano)
            ->when(!empty($this->mes), function ($q) {
                return $q->whereMonth('date', $this->mes);
            })
            ->when(!empty($this->trimestre), function ($q) {
                return $q->where('date', '>=', $this->trimestres[$this->trimestre]['desde'])
                    ->where('date', '<=', $this->trimestres[$this->trimestre]['hasta']);
            })
            ->when(!empty($this->semestre), function ($q) {
                return $q->where('date', '>=', $this->semestres[$this->semestre]['desde'])
                    ->where('date', '<=', $this->semestres[$this->semestre]['hasta']);
            })->get();
    }

    protected function viviendas() {
        return House::with(
                'municipality:id,name',
                'province:id,acronym'
            )->where('cdr_id', $this->cdr)
            ->whereYear('created_at', $this->ano)
            ->when(!empty($this->mes), function ($q) {
                return $q->whereMonth('created_at', $this->mes);
            })
            ->when(!empty($this->trimestre), function ($q) {
                return $q->where('created_at', '>=', $this->trimestres[$this->trimestre]['desde'])
                    ->where('created_at', '<=', $this->trimestres[$this->trimestre]['hasta']);
            })
            ->when(!empty($this->semestre), function ($q) {
                return $q->where('created_at', '>=', $this->semestres[$this->semestre]['desde'])
                    ->where('created_at', '<=', $this->semestres[$this->semestre]['hasta']);
            })->get();
    }

    protected function negocios() {
        return Business::with(
                'municipality:id,name',
                'province:id,acronym'
            )->where('cdr_id', $this->cdr)
            ->whereYear('created_at', $this->ano)
            ->when(!empty($this->mes), function ($q) {
                return $q->whereMonth('created_at', $this->mes);
            })
            ->when(!empty($this->trimestre), function ($q) {
                return $q->where('created_at', '>=', $this->trimestres[$this->trimestre]['desde'])
                    ->where('created_at', '<=', $this->trimestres[$this->trimestre]['hasta']);
            })
            ->when(!empty($this->semestre), function ($q) {
                return $q->where('created_at', '>=', $this->semestres[$this->semestre]['desde'])
                    ->where('created_at', '<=', $this->semestres[$this->semestre]['hasta']);
            })->get();
    }

    protected function tierras() {
        return Land::with(
                'municipality:id,name',
                'province:id,acronym'
            )->where('cdr_id', $this->cdr)
            ->whereYear('created_at', $this->ano)
            ->when(!empty($this->mes), function ($q) {
                return $q->whereMonth('created_at', $this->mes);
            })
            ->when(!empty($this->trimestre), function ($q) {
                return $q->where('created_at', '>=', $this->trimestres[$this->trimestre]['desde'])
                    ->where('created_at', '<=', $this->trimestres[$this->trimestre]['hasta']);
            })
            ->when(!empty($this->semestre), function ($q) {
                return $q->where('created_at', '>=', $this->semestres[$this->semestre]['desde'])
                    ->where('created_at', '<=', $this->semestres[$this->semestre]['hasta']);
            })->get();
    }

    protected function trabajos() {
        return Job::with(
                'municipality:id,name',
                'province:id,acronym'
            )->where('cdr_id', $this->cdr)
            ->whereYear('created_at', $this->ano)
            ->when(!empty($this->mes), function ($q) {
                return $q->whereMonth('created_at', $this->mes);
            })
            ->when(!empty($this->trimestre), function ($q) {
                return $q->where('created_at', '>=', $this->trimestres[$this->trimestre]['desde'])
                    ->where('created_at', '<=', $this->trimestres[$this->trimestre]['hasta']);
            })
            ->when(!empty($this->semestre), function ($q) {
                return $q->where('created_at', '>=', $this->semestres[$this->semestre]['desde'])
                    ->where('created_at', '<=', $this->semestres[$this->semestre]['hasta']);
            })->get();
    }

}
