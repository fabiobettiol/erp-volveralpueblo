@props([
    'miembros'
])

<div id="tbl-miembros" class="resource-table mx-auto card my-4 shadow-sm tbl-miembros d-none">
    <div class="card-header d-flex flex-row">
        <h4 class="p-2 flex-grow-1">Miembros</h4>
        <a id="x-miembros" href="#">
            <i class="fa-solid fa-x py-3 px-1"></i>
        </a>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col">
                <table class="table table-sm table-borderless ">
                    <tr>
                        <th>Referencia</th>
                        <th>Nombre</th>
                        <th class="text-center">Género</th>
                        <th>Situación</th>
                        <th class="text-center">Adultos</th>
                        <th class="text-center">Menores</th>
                    </tr>
                    @php
                        $totAdults = 0;
                        $totChildren = 0;

                        $situacion = [
                            '1' => 'Empleado por cuenta ajena',
                            '2' => 'Empleado por cuenta propia',
                            '3' => 'Desempleado',
                            '4' => 'Estudiando',
                            '5' => 'Otros',
                        ];
                    @endphp

                    @foreach ($miembros as $s)
                        @php
                            $adult = '';
                            $child = '';
                            if (is_null($s->dateofbirth)) {
                                $adult = '--';
                                $child = '--';
                            } elseif (Carbon\Carbon::parse($s->dateofbirth)->age >= 18) {
                                $child = '';
                                $totAdults++;
                            } else {
                                $adult = '';
                                $totChildren++;
                            }
                        @endphp

                        <tr>
                            <td>{{ $s->family->reference }}</td>
                            <td>{{ ucwords(strtolower($s->name . ' ' . substr_replace($s->surname, str_repeat('*', strlen($s->surname)),1))) }}</td>
                            <td class="text-center">{{ $s->gender->name }}</td>
                            <td>{{ ($s->employment_status == null) ? '--' : $situacion[$s->employment_status] }}</td>
                            <td class="text-center" style="font-family: FontAwesome">{{ $adult }}</td>
                            <td class="text-center" style="font-family: FontAwesome">{{ $child }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="4">Total</td>
                        <td class="text-center">{{ $totAdults }}</td>
                        <td class="text-center">{{ $totChildren }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
