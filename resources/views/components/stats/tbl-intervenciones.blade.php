@props([
    'intervenciones'
])
<h2>Intevenciones</h2>
<div class="row">
    <div class="col">
        <table class="table table-sm table-striped">
            <tr>
                <th>Familia</th>
                <th>Fecha</th>
                <th>Asunto</th>
            </tr>
            @foreach ($intervenciones as $s)
                <tr>
                    <td>{{ $s->family->reference }}</td>
                    <td>{{ \Carbon\Carbon::parse($s->date)->format('d/m/Y') }}</td>
                    <td>{{ ucwords(strtolower($s->subject)) }}</td>
                </tr>
            @endforeach
        </table>
    </div>
</div>
