@props([
    'intervenciones'
])

<div id="tbl-intervenciones" class="resource-table mx-auto card my-4 shadow-sm tbl-intervenciones d-none">
    <div class="card-header d-flex flex-row">
        <h4 class="p-2 flex-grow-1">Intervenciones</h4>
        <a id="x-intervenciones" href="#">
            <i class="fa-solid fa-x py-3 px-1"></i>
        </a>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col">
                <table class="table table-sm table-borderless">
                    <tr>
                        <th>Familia</th>
                        <th>Fecha</th>
                        <th>Asunto</th>
                    </tr>
                    @foreach ($intervenciones as $s)
                        <tr>
                            <td>{{ $s->family->reference }}</td>
                            <td>{{ \Carbon\Carbon::parse($s->date)->format('d/m/Y') }}</td>
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
            $('p#Intervenciones').html({{ $intervenciones->count() }});
        })
    </script>
@endpush
