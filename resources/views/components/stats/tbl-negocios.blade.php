@props([
    'negocios'
])

<h2>Negocios</h2>
<div class="row">
    <div class="col">
        <table class="table table-sm table-striped ">
            <tr>
                <th>Referencia</th>
                <th>Nombre</th>
                <th>Provincia</th>
                <th>Municipio</th>
                <th>Localidad</th>
            </tr>

            @foreach ($negocios as $s)
                <tr>
                    <td>{{ $s->reference }}</td>
                    <td>{{ $s->property_name ?? '--' }}</td>
                    <td>{{ $s->province->acronym }}</td>
                    <td>{{ $s->municipality->name ?? '--' }}</td>
                    <td>{{ ucwords(strtolower($s->town ?? '--')) }}</td>
                </tr>
            @endforeach
        </table>
    </div>
</div>
