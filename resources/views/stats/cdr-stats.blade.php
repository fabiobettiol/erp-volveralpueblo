@extends('layouts.app-stats')
@section('solicitantes')
    <div class="row">
        <div class="col-md-2">
            <x-stats.card title="Solicitantes" icon="fa-user" value="{{ $numSolicitantes }}"/>
        </div>
        <div class="col-md-2">
            <x-stats.card title="Interacciones" icon="fa-solid fa-comments" value="{{ $numInteracciones }}"/>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <x-stats.tbl-solicitantes :solicitantes="$solicitantes" />
        </div>
    </div>

    <div class="row">
        <div class="col">
            <x-stats.tbl-interacciones :interacciones="$interacciones" />
        </div>
    </div>
@endsection

@section('familias')

    <div class="row">
        <div class="col-md-2">
            <x-stats.card title="Familias" icon="fa-solid fa-people-roof" value="{{ $numFamilias }}"/>
        </div>
        <div class="col-md-2">
            <x-stats.card title="Miembros" icon="fa-solid fa-users-viewfinder" value="{{ $numMiembros }}"/>
        </div>
        <div class="col-md-2">
            <x-stats.card title="Intervenciones" icon="fa-regular fa-handshake" value="{{ $numIntervenciones }}"/>
        </div>
        <div class="col-md-2">
            <x-stats.card title="Seguimientos" icon="fa-regular fa-eye" value="{{ $numSeguimientos }}"/>
        </div>
    </div>

   <div class="row">
        <div class="col">
            <x-stats.tbl-familias :familias="$familias" />
        </div>
    </div>

@endsection

@section('recursos')
    <div class="row">
        <div class="col-md-2">
            <x-stats.card title="Viviendas" icon="fa-solid fa-house-chimney" value="{{ $numViviendas }}"/>
        </div>
        <div class="col-md-2">
            <x-stats.card title="Negocios" icon="fa-solid fa-shop" value="{{ $numNegocios }}"/>
        </div>
        <div class="col-md-2">
            <x-stats.card title="Tierras" icon="fa-solid fa-tractor" value="{{ $numTierras }}"/>
        </div>
        <div class="col-md-2">
            <x-stats.card title="Trabajos" icon="fa-solid fa-person-digging" value="{{ $numTrabajos }}"/>
        </div>
    </div>
@endsection
