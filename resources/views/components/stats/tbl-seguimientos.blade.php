@props([
    'seguimientos'
])

<div class="resource-table mx-auto card my-4 shadow-sm tbl-seguimientos">
  <div class="card-body">
        <h3 class="p-2">Seguimientos</h3>
        <div class="row">
            <div class="col">
                <table class="table table-sm table-borderless">
                    <tr>
                        <th>Familia</th>
                        <th>Fecha</th>
                        <th>Asunto</th>
                    </tr>
                    @foreach ($seguimientos as $s)
                        <tr>
                            <td>{{ $s->family->reference }}</td>
                            <td>{{ \Carbon\Carbon::parse($s->date)->format('d/m/Y') }}</td>
                            <td>{{ ucwords(strtolower($s->subject)) }}</td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
</div>
