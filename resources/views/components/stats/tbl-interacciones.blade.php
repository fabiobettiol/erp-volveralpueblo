@props([
    'interacciones'
])

<div class="resource-table mx-auto card my-4 shadow-sm tbl-interacciones">
  <div class="card-body">
        <h3 class="p-2">Interacciones</h3>
        <div class="row">
            <div class="col">
                <table class="table table-sm table-borderless">
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
    </div>
</div>
