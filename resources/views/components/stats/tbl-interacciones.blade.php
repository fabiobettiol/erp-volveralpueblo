 @props([
    'interacciones'
])

<div id="tbl-interacciones" class="resource-table mx-auto card my-4 shadow-sm tbl-interacciones d-none">
    <div class="card-header d-flex flex-row">
        <h4 class="p-2 flex-grow-1">Interacciones</h4>
        <a id="x-interacciones" href="#">
            <i class="fa-solid fa-x py-3 px-1"></i>
        </a>
    </div>
    <div class="card-body">
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
                            <td>{{ Str::ucfirst(Str::lower($s->subject)) }}</td>
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
            $('p#Interacciones').html({{ $interacciones->count() }});
        })
    </script>
@endpush
