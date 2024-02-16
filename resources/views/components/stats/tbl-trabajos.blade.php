@props([
    'trabajos'
])

<div id="tbl-trabajos" class="tbl-trabajos card my-4 shadow-sm d-none">
    <div class="card-header d-flex flex-row">
        <h4 class="p-2 flex-grow-1">Trabajos</h4>
        <a id="x-trabajos" href="#">
            <i class="fa-solid fa-x py-3 px-1"></i>
        </a>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col">
                <table class="table table-sm table-borderless ">
                    <tr>
                        <th>Referencia</th>
                        <th>Posición</th>
                        <th>Provincia</th>
                        <th>Municipio</th>
                        <th>Localidad</th>
                    </tr>

                    @foreach ($trabajos as $s)
                        <tr>
                            <td>{{ $s->reference }}</td>
                            <td>{{ Str::ucfirst(Str::lower($s->position)) }}</td>
                            <td>{{ $s->province->acronym ?? '--'}}</td>
                            <td>{{ $s->municipality->name ?? '--' }}</td>
                            <td>{{ Str::ucfirst(Str::lower($s->town ?? '--')) }}</td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
</div>

@push('component-scripts')
    <script>
        $(document).ready(function () {
            $('p#Trabajos').html({{ $trabajos->count() }});
        })
    </script>
@endpush
