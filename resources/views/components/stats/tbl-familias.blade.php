@props([
    'familias'
])

<div class="row">
    <div class="col">
        <table class="table table-sm table-striped ">
            <tr>
                <th>Referencia</th>
                <th>Asentamiento</th>
                <th class="text-center">Nacionalidad</th>
                <th class="text-center">Destino</th>
                <th class="text-center">Estado Asentamiento</th>
            </tr>
            @foreach ($familias as $s)
            <tr>
                <td>{{ $s->reference }}</td>
                <td>{{ $s->settlementdate }}</td>
                <td align="center">{{ $s->nationality->alfa3 }}</td>
                <td align="center">{{ $s->destinationprovince->acronym ?? '--' }}</td>
                <td align="center">{{ $s->settlementstatus->name }}</td>
            </tr>
            @endforeach
        </table>
    </div>
</div>
