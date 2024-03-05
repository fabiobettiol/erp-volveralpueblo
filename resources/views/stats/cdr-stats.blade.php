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
    <x-stats.filter-cdr-card cdr="{{ $cdrInfo->id }}" :cdrs="$cdrs" />

    <div class="info-solicitantes">
        <x-stats.filter-card cdr="{{ Request::get('cdr') }}" />

        <div class="row">
            <div class="col-md-3">
                <x-stats.card id="lens-solicitantes" title="Solicitantes" icon="fa-user" value=""/>
            </div>
            <div class="col-md-3">
                <x-stats.card title="Adultos" :lens="false" icon="fa-user" value=""/>
            </div>
            <div class="col-md-3">
                <x-stats.card title="Menores" :lens="false" icon="fa-user" value=""/>
            </div>
            <div class="col-md-3">
                <x-stats.card id="lens-interacciones" title="Interacciones" icon="fa-solid fa-comments" value=""/>
            </div>
        </div>

        <div class="row">
            <div class="col">
                <x-stats.tbl-solicitantes class="tbl-solicitantes" :solicitantes="$solicitantes"/>
            </div>
        </div>

        <div class="row">
            <div class="col">
                <x-stats.tbl-interacciones :interacciones="$interacciones" />
            </div>
        </div>
    </div>
@endsection

@section('familias')
    <div class="info-familias">
        <div class="row">
            <div class="col">
                <x-stats.card id="lens-familias" title="Familias" icon="fa-solid fa-people-roof" value=""/>
            </div>
            <div class="col">
                <x-stats.card id="lens-miembros" title="Miembros" icon="fa-solid fa-users-viewfinder" value=""/>
            </div>
            <div class="col">
                <x-stats.card id="lens-intervenciones" title="Intervenciones" icon="fa-regular fa-handshake" value=""/>
            </div>
            <div class="col">
                <x-stats.card id="lens-seguimientos" title="Seguimientos" icon="fa-regular fa-eye" value=""/>
            </div>
        </div>

        <div class="row">
            <div class="col">
                <x-stats.tbl-familias :familias="$familias" />
            </div>
        </div>

        <div class="row">
            <div class="col">
                <x-stats.tbl-miembros :miembros="$miembros" />
            </div>
        </div>

        <div class="row">
            <div class="col">
                <x-stats.tbl-intervenciones :intervenciones="$intervenciones" />
            </div>
        </div>

        <div class="row">
            <div class="col">
                <x-stats.tbl-seguimientos :seguimientos="$seguimientos" />
            </div>
        </div>
    </div>
@endsection

@section('recursos')
    <div class="info-recursos">
        <div class="row mb-5">
            <div class="col-md-3">
                <x-stats.card id="lens-viviendas" title="Viviendas" icon="fa-solid fa-house-chimney" value=""/>
            </div>
            <div class="col-md-3">
                <x-stats.card id="lens-negocios" title="Negocios" icon="fa-solid fa-shop" value=""/>
            </div>
            <div class="col-md-3">
                <x-stats.card id="lens-tierras" title="Tierras" icon="fa-solid fa-tractor" value=""/>
            </div>
            <div class="col-md-3">
                <x-stats.card id="lens-trabajos" title="Trabajos" icon="fa-solid fa-person-digging" value=""/>
            </div>
        </div>

        <div class="row">
            <div class="col">
                <x-stats.tbl-viviendas :viviendas="$viviendas"/>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <x-stats.tbl-negocios :negocios="$negocios"/>
            </div>
        </div>

        <div class="row">
            <div class="col">
                <x-stats.tbl-tierras :tierras="$tierras"/>
            </div>
        </div>

        <div class="row">
            <div class="col">
                <x-stats.tbl-trabajos :trabajos="$trabajos"/>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {

            $('#leyenda').html('');

            @if(Request::post('filtro_ano'))
                $('#filtro-ano').val('{{ Request::post('filtro_ano') }}' );
                $('#leyenda').html({{ Request::post('filtro_ano') }});
            @endif
            @if(Request::post('filtro_mes'))
                $('#filtro-mes').val('{{ Request::post('filtro_mes') }}' );
                var leyenda = $('#leyenda').html() + ' - ' + $('#filtro-mes option:selected').text();
                $('#leyenda').html(leyenda);
            @endif
            @if(Request::post('filtro_trimestre'))
                $('#filtro-trimestre').val('{{ Request::post('filtro_trimestre') }}' );
                var leyenda = $('#leyenda').html() + ' - ' + $('#filtro-trimestre option:selected').text();
                $('#leyenda').html(leyenda);
            @endif
            @if(Request::post('filtro_semestre'))
                $('#filtro-semestre').val('{{ Request::post('filtro_semestre') }}' );
                var leyenda = $('#leyenda').html() + ' - ' + $('#filtro-semestre option:selected').text();
                $('#leyenda').html(leyenda);
            @endif

            if ($('#filtro-ano').val() != 0) {
                $('#filtro-mes').attr('disabled', false);
                $('#filtro-trimestre').attr('disabled', false);
                $('#filtro-semestre').attr('disabled', false);
            } else {
                $('#filtro-mes').attr('disabled', true);
                $('#filtro-trimestre').attr('disabled', true);
                $('#filtro-semestre').attr('disabled', true);
            }
        });

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

        $('#cdr-select').change(function() {
            $('#cdr-id').val($('#cdr-select').val());
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
            $('#filtro-form').submit();
        });

        $('#filtro-trimestre').change(function() {
            $('#filtro-mes').val('0');
            $('#filtro-semestre').val('0');
            $('#filtro-form').submit();
        });

        $('#filtro-semestre').change(function() {
            $('#filtro-mes').val('0');
            $('#filtro-trimestre').val('0');
            $('#filtro-form').submit();
        });
    </script>
@endsection

