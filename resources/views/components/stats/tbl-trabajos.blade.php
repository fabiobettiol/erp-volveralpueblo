@props([
    'trabajos'
])

<div class="tbl-trabajos card my-4 shadow-sm">
  <div class="card-body">
        <h3 class="p-2">Trabajos</h3>
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
                            <td>{{ ucfirst(strtolower($s->position)) }}</td>
                            <td>{{ $s->province->acronym ?? '--'}}</td>
                            <td>{{ $s->municipality->name ?? '--' }}</td>
                            <td>{{ ucwords(strtolower($s->town ?? '--')) }}</td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
</div>
