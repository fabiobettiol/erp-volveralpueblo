@props([
    'viviendas'
])

<h2>Viviendas</h2>
<div class="row">
    <div class="col">
        <table class="table table-sm table-striped ">
            <tr>
                <th>Referencia</th>
                <th>Provincia</th>
                <th>Municipio</th>
                <th>Localidad</th>
            </tr>

            @foreach ($viviendas as $s)
                <tr>
                    <td>{{ $s->reference }}</td>
                    <td>{{ $s->province->acronym ?? '--'}}</td>
                    <td>{{ $s->municipality->name ?? '--'}}</td>
                    <td>{{ ucwords(strtolower($s->town ?? '--')) }}</td>
                </tr>
            @endforeach
        </table>
    </div>
</div>
