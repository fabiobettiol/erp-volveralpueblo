@extends('layouts.app-stats')

@section('header')
    <div class="row mt-3">
        <div class="col-2">
            @if (auth()->user()->hasPermissionTo('view global stats'))
                <img src="/logos/logo-coceder.png" style="height: 6rem">
            @else
                <img src="/storage/{{ $cdrInfo->logo }}" style="height: 6rem">
            @endif
        </div>
        <div class="col-10 text-center">
            @if (auth()->user()->hasPermissionTo('view global stats'))
                <h5 style="line-height: 6rem; margin: 0">COCEDER - Estadísticas Globales</h5>
            @else
                <h5 style="line-height: 6rem; margin: 0">{{ $cdrInfo->name }}</h5>
            @endif
        </div>
    </div>
@endsection

@section('solicitantes')
    <x-stats.filter-card cdr="{{ $cdr }}"/>
@endsection

@section('scripts')
    <script>
        $('#lens-solicitantes').click(function(e) {
            e.preventDefault();
            $('#tbl-solicitantes').toggleClass('d-none');
        });

        $('#lens-interacciones').click(function(e) {
            e.preventDefault();
            $('#tbl-interacciones').toggleClass('d-none');
        });

        $('#lens-familias').click(function(e) {
            e.preventDefault();
            $('#tbl-familias').toggleClass('d-none');
        });

        $('#lens-miembros').click(function(e) {
            e.preventDefault();
            $('#tbl-miembros').toggleClass('d-none');
        });

        $('#lens-intervenciones').click(function(e) {
            e.preventDefault();
            $('#tbl-intervenciones').toggleClass('d-none');
        });

        $('#lens-seguimientos').click(function(e) {
            e.preventDefault();
            $('#tbl-seguimientos').toggleClass('d-none');
        });

        $('#lens-viviendas').click(function(e) {
            e.preventDefault();
            $('#tbl-viviendas').toggleClass('d-none');
        });

        $('#lens-negocios').click(function(e) {
            e.preventDefault();
            $('#tbl-negocios').toggleClass('d-none');
        });

        $('#lens-tierras').click(function(e) {
            e.preventDefault();
            $('#tbl-tierras').toggleClass('d-none');
        });

        $('#lens-trabajos').click(function(e) {
            e.preventDefault();
            $('#tbl-trabajos').toggleClass('d-none');
        });

        $('#x-solicitantes').click(function(e) {
            e.preventDefault();
            $('#tbl-solicitantes').addClass('d-none');
        });

        $('#x-interacciones').click(function(e) {
            e.preventDefault();
            $('#tbl-interacciones').addClass('d-none');
        });

        $('#x-familias').click(function(e) {
            e.preventDefault();
            $('#tbl-familias').addClass('d-none');
        });

        $('#x-miembros').click(function(e) {
            e.preventDefault();
            $('#tbl-miembros').addClass('d-none');
        });

        $('#x-intervenciones').click(function(e) {
            e.preventDefault();
            $('#tbl-intervenciones').addClass('d-none');
        });

        $('#x-seguimientos').click(function(e) {
            e.preventDefault();
            $('#tbl-seguimientos').addClass('d-none');
        });

        $('#x-viviendas').click(function(e) {
            e.preventDefault();
            $('#tbl-viviendas').addClass('d-none');
        });

        $('#x-negocios').click(function(e) {
            e.preventDefault();
            $('#tbl-negocios').addClass('d-none');
        });

        $('#x-tierras').click(function(e) {
            e.preventDefault();
            $('#tbl-tierras').addClass('d-none');
        });

        $('#x-trabajos').click(function(e) {
            e.preventDefault();
            $('#tbl-trabajos').addClass('d-none');
        });

        $('#filtro-ano').change(function() {
            $('#filtro-mes').val('0');
            $('#filtro-trimestre').val('0');
            $('#filtro-semestre').val('0');
            $('#filtro-form').submit();
        });

        $('#filtro-mes').change(function() {
            $('#filtro-trimestre').val('0');
            $('#filtro-semestre').val('0');
        });

        $('#filtro-trimestre').change(function() {
            $('#filtro-mes').val('0');
            $('#filtro-semestre').val('0');
        });

        $('#filtro-semestre').change(function() {
            $('#filtro-mes').val('0');
            $('#filtro-trimestre').val('0');
        });
    </script>
@endsection
