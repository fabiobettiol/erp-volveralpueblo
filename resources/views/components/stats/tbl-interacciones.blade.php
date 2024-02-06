@props([
    'interacciones'
])

<div class="row">
    <div class="col">
        <table class="table table-sm table-striped">
            <tr>
                <th>Fecha</th>
                <th>Nombre</th>
                <th>Asunto</th>
            </tr>
            @foreach ($interacciones as $s)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($s->date)->format('d/m/Y') }}</td>
                    <td>{{ ucwords(strtolower(($s->demandant->name ?? '') . ' ' . substr_replace(($s->demandant->surname ?? ''), str_repeat('*', strlen(($s->demandant->surname ?? ''))),1))) }}</td>
                    <td>{{ ucwords(strtolower($s->subject)) }}</td>
                </tr>
            @endforeach
        </table>
    </div>
</div>
