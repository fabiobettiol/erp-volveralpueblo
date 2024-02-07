@props([
    'trabajos'
])

<h2>Trabajos</h2>
<div class="row">
    <div class="col">
        <table class="table table-sm table-striped ">
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
