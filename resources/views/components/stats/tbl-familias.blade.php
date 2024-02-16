@props([
    'familias'
])

<div id="tbl-familias" class="resource-table mx-auto card my-4 shadow-sm tbl-familias d-none">
    <div class="card-header d-flex flex-row">
        <h4 class="p-2 flex-grow-1">Familias</h4>
        <a id="x-familias" href="#">
            <i class="fa-solid fa-x py-3 px-1"></i>
        </a>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col">
                <table class="table table-sm table-borderless ">
                    <tr>
                        <th>Referencia</th>
                        <th>Asentamiento</th>
                        <th class="text-center">Miembros</th>
                        <th class="text-center">Intervenciones</th>
                        <th class="text-center">Nacionalidad</th>
                        <th class="text-center">Destino</th>
                        <th class="text-center">Estado Asentamiento</th>
                    </tr>
                    @foreach ($familias as $s)
                    <tr>
                        <td>{{ $s->reference }}</td>
                        <td>{{ $s->settlementdate }}</td>
                        <td align="center">{{ $s->members_count }}</td>
                        <td align="center">{{ $s->contacts_count }}</td>
                        <td align="center">{{ $s->nationality->alfa3 }}</td>
                        <td align="center">{{ $s->destinationprovince->acronym ?? '--' }}</td>
                        <td align="center">{{ $s->settlementstatus->name }}</td>
                    </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
</div>
