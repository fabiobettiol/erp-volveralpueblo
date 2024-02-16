<?php

namespace App\Http\Controllers;

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
    public $viviendas;
    public $negocios;
    public $tierras;
    public $trabajos;

    public function filter() {
        return view ('stats.cdr-stats-init');
    }

    public function init(Request $request) {
        //dd($request->all());
        $this->cdr = 22;
        $this->ano = $request->filtro_ano;
        $this->mes = $request->filtro_mes;
        $this->trimestre = $request->filtro_trimestre;
        $this->semestre = $request->filtro_semestre;

        // - Solicitantes (demandantes)
        $this->numSolicitantes = $this->numSolicitantes();
        $this->solicitantes = $this->solicitantes();
        $this->numInteracciones = $this->numInteracciones();
        $this->interacciones = $this->interacciones();

        // - Asentados (familias)
        $this->numFamilias = $this->numFamilias();
        $this->familias = $this->familias();
        $this->numMiembros = $this->numMiembros();
        $this->miembros = $this->miembros();
        $this->intervenciones = $this->intervenciones();
        $this->numIntervenciones = $this->numIntervenciones();
        $this->seguimientos = $this->seguimientos();
        $this->numSeguimientos = $this->numSeguimientos();

        // - Recuros
        $this->viviendas = $this->viviendas();
        $this->negocios = $this->negocios();
        $this->tierras = $this->tierras();
        $this->trabajos = $this->trabajos();
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

            'viviendas' => $this->viviendas,
            'negocios' => $this->negocios,
            'tierras' => $this->tierras,
            'trabajos' => $this->trabajos,
            'numViviendas' => $this->numViviendas,
            'numNegocios' => $this->numNegocios,
            'numTierras' => $this->numTierras,
            'numTrabajos' => $this->numTrabajos
        ]);

    }

    protected function solicitantes() {
        return Demandant::whereHas('followups', function($query) {
                $query->where('cdr_id', $this->cdr);
                $query->whereYear('date', $this->ano);
            })
            ->when(!empty($this->mes), function ($q) {
                return $q->whereMonth('created_at', $this->mes);
            })
            ->when(!empty($this->trimestre), function ($q) {
                return $q->whereMonth('created_at', $this->trimestre);
            })
            ->when(!empty($this->semestre), function ($q) {
                return $q->whereMonth('created_at', $this->semestre);
            })
            ->withCount(['followups' => function ($query) {
                $query->where('cdr_id', $this->cdr);
            }])
            ->with('country:id,alfa3','provinceto:id,acronym')
            ->get();
    }

    protected function numSolicitantes() {
        return Demandantfollowup::distinct('demandant_id')
            ->where('cdr_id', $this->cdr)
            ->whereYear('date', $this->ano)
            ->when(!empty($this->mes), function ($q) {
                return $q->whereMonth('date', $this->mes);
            })
            ->when(!empty($this->trimestre), function ($q) {
                return $q->whereMonth('date', $this->trimestre);
            })
            ->when(!empty($this->semestre), function ($q) {
                return $q->whereMonth('date', $this->semestre);
            })->count();
    }

    protected function interacciones() {
        return Demandantfollowup::with('demandant:id,name,surname')
            ->where('cdr_id', $this->cdr)
            ->whereYear('date', $this->ano)
            ->when(!empty($this->mes), function ($q) {
                return $q->whereMonth('date', $this->mes);
            })
            ->when(!empty($this->trimestre), function ($q) {
                return $q->whereMonth('date', $this->trimestre);
            })
            ->when(!empty($this->semestre), function ($q) {
                return $q->whereMonth('date', $this->semestre);
            })->get();
    }

    protected function numInteracciones() {
        if (!empty($this->ano)) {
            return Demandantfollowup::where('cdr_id', $this->cdr)
            ->whereYear('date', $this->ano)
            ->when(!empty($this->mes), function ($q) {
            return $q->whereMonth('date', $this->mes);
            })
            ->when(!empty($this->trimestre), function ($q) {
                return $q->whereMonth('date', $this->trimestre);
            })
            ->when(!empty($this->semestre), function ($q) {
                return $q->whereMonth('date', $this->semestre);
            })->count();
        } else {
            return 0;
        }
    }

    protected function familias() {
        return Family::with(
                'settlementstatus:id,name',
                'destinationprovince:id,acronym',
                'nationality:id,alfa3'
            )->withCount('members')
            ->withCount('contacts')
            ->where('cdr_id', $this->cdr)
            ->when(!empty($this->mes), function ($q) {
                return $q->whereMonth('settlementdate', $this->mes);
            })
            ->when(!empty($this->trimestre), function ($q) {
                return $q->whereMonth('settlementdate', $this->trimestre);
            })
            ->when(!empty($this->semestre), function ($q) {
                return $q->whereMonth('settlementdate', $this->semestre);
            })->get();
    }


    protected function numFamilias() {
        return Family::where('cdr_id', $this->cdr)
            ->when(!empty($this->mes), function ($q) {
                return $q->whereMonth('settlementdate', $this->mes);
            })
            ->when(!empty($this->trimestre), function ($q) {
                return $q->whereMonth('settlementdate', $this->trimestre);
            })
            ->when(!empty($this->semestre), function ($q) {
                return $q->whereMonth('settlementdate', $this->semestre);
            })->count();
    }

    protected function miembros() {
        return Familymember::with('family:id,reference')
            ->whereHas('family', function($query) {
                $query->when(!empty($this->mes), function ($q) {
                    return $q->whereMonth('settlementdate', $this->mes);
                });
                $query->when(!empty($this->trimestre), function ($q) {
                    return $q->whereMonth('settlementdate', $this->trimestre);
                });
                $query->when(!empty($this->semestre), function ($q) {
                    return $q->whereMonth('settlementdate', $this->semestre);
                });
            })->where('cdr_id', $this->cdr)->get();
    }

    protected function numMiembros() {
        return Familymember::where('cdr_id', $this->cdr)
            ->whereHas('family', function($query) {
                $query->when(!empty($this->mes), function ($q) {
                    return $q->whereMonth('settlementdate', $this->mes);
                });
                $query->when(!empty($this->trimestre), function ($q) {
                    return $q->whereMonth('settlementdate', $this->trimestre);
                });
                $query->when(!empty($this->semestre), function ($q) {
                    return $q->whereMonth('settlementdate', $this->semestre);
                });
            })->count();
    }

    protected function intervenciones() {
        return Familycontact::with('family:id,reference')
            ->where('cdr_id', $this->cdr)
            ->when(!empty($this->mes), function ($q) {
                return $q->whereMonth('date', $this->mes);
            })
            ->when(!empty($this->trimestre), function ($q) {
                return $q->whereMonth('date', $this->trimestre);
            })
            ->when(!empty($this->semestre), function ($q) {
                return $q->whereMonth('date', $this->semestre);
            })->get();
    }

    protected function numIntervenciones() {
        return Familycontact::where('cdr_id', $this->cdr)
            ->when(!empty($this->mes), function ($q) {
                return $q->whereMonth('date', $this->mes);
            })
            ->when(!empty($this->trimestre), function ($q) {
                return $q->whereMonth('date', $this->trimestre);
            })
            ->when(!empty($this->semestre), function ($q) {
                return $q->whereMonth('date', $this->semestre);
            })->count();
    }

    protected function seguimientos() {
        return Familyfollowup::with('family:id,reference')
            ->where('cdr_id', $this->cdr)
            ->when(!empty($this->mes), function ($q) {
                return $q->whereMonth('date', $this->mes);
            })
            ->when(!empty($this->trimestre), function ($q) {
                return $q->whereMonth('date', $this->trimestre);
            })
            ->when(!empty($this->semestre), function ($q) {
                return $q->whereMonth('date', $this->semestre);
            })->get();
    }

    protected function numSeguimientos() {
        return Familyfollowup::where('cdr_id', $this->cdr)
            ->when(!empty($this->mes), function ($q) {
                return $q->whereMonth('date', $this->mes);
            })
            ->when(!empty($this->trimestre), function ($q) {
                return $q->whereMonth('date', $this->trimestre);
            })
            ->when(!empty($this->semestre), function ($q) {
                return $q->whereMonth('date', $this->semestre);
            })->count();
    }

    protected function viviendas() {
        return House::with(
                'municipality:id,name',
                'province:id,acronym'
            )->where('cdr_id', $this->cdr)
            ->when(!empty($this->mes), function ($q) {
                return $q->whereMonth('created_at', $this->mes);
            })
            ->when(!empty($this->trimestre), function ($q) {
                return $q->whereMonth('created_at', $this->trimestre);
            })
            ->when(!empty($this->semestre), function ($q) {
                return $q->whereMonth('created_at', $this->semestre);
            })->get();
    }

    protected function numViviendas() {
        return House::where('cdr_id', $this->cdr)
            ->when(!empty($this->mes), function ($q) {
                return $q->whereMonth('created_at', $this->mes);
            })
            ->when(!empty($this->trimestre), function ($q) {
                return $q->whereMonth('created_at', $this->trimestre);
            })
            ->when(!empty($this->semestre), function ($q) {
                return $q->whereMonth('created_at', $this->semestre);
            })->count();
    }

    protected function negocios() {
        return Business::with(
                'municipality:id,name',
                'province:id,acronym'
            )->where('cdr_id', $this->cdr)
            ->when(!empty($this->mes), function ($q) {
                return $q->whereMonth('created_at', $this->mes);
            })
            ->when(!empty($this->trimestre), function ($q) {
                return $q->whereMonth('created_at', $this->trimestre);
            })
            ->when(!empty($this->semestre), function ($q) {
                return $q->whereMonth('created_at', $this->semestre);
            })->get();
    }

    protected function numNegocios() {
        return Business::where('cdr_id', $this->cdr)
            ->when(!empty($this->mes), function ($q) {
                return $q->whereMonth('created_at', $this->mes);
            })
            ->when(!empty($this->trimestre), function ($q) {
                return $q->whereMonth('created_at', $this->trimestre);
            })
            ->when(!empty($this->semestre), function ($q) {
                return $q->whereMonth('created_at', $this->semestre);
            })->count();
    }

    protected function tierras() {
        return Land::with(
                'municipality:id,name',
                'province:id,acronym'
            )->where('cdr_id', $this->cdr)
            ->when(!empty($this->mes), function ($q) {
                return $q->whereMonth('created_at', $this->mes);
            })
            ->when(!empty($this->trimestre), function ($q) {
                return $q->whereMonth('created_at', $this->trimestre);
            })
            ->when(!empty($this->semestre), function ($q) {
                return $q->whereMonth('created_at', $this->semestre);
            })->get();
    }

    protected function numTierras() {
        return Land::where('cdr_id', $this->cdr)
            ->when(!empty($this->mes), function ($q) {
                return $q->whereMonth('created_at', $this->mes);
            })
            ->when(!empty($this->trimestre), function ($q) {
                return $q->whereMonth('created_at', $this->trimestre);
            })
            ->when(!empty($this->semestre), function ($q) {
                return $q->whereMonth('created_at', $this->semestre);
            })->count();
    }

    protected function trabajos() {
        return Job::with(
                'municipality:id,name',
                'province:id,acronym'
            )->where('cdr_id', $this->cdr)
            ->when(!empty($this->mes), function ($q) {
                return $q->whereMonth('created_at', $this->mes);
            })
            ->when(!empty($this->trimestre), function ($q) {
                return $q->whereMonth('created_at', $this->trimestre);
            })
            ->when(!empty($this->semestre), function ($q) {
                return $q->whereMonth('created_at', $this->semestre);
            })->get();
    }

    protected function numTrabajos() {
        return Job::where('cdr_id', $this->cdr)
            ->when(!empty($this->mes), function ($q) {
                return $q->whereMonth('created_at', $this->mes);
            })
            ->when(!empty($this->trimestre), function ($q) {
                return $q->whereMonth('created_at', $this->trimestre);
            })
            ->when(!empty($this->semestre), function ($q) {
                return $q->whereMonth('created_at', $this->semestre);
            })->count();
    }
}
