@props([
    'solicitantes'
])

<div class="mx-auto card my-4 shadow-sm tbl-solicitantes">
    <div class="card-body">
        <h3 class="p-2">Solicitantes</h3>
        <div class="row">
            <div class="col">
                <table class="table table-sm table-borderless">
                    <tr>
                        <th>Nombre</th>
                        <th>Ciudad</th>
                        <th class="text-center">País</th>
                        <th class="text-center">Interacciones</th>
                        <th class="text-center">Adultos</th>
                        <th class="text-center">Menores</th>
                        <th class="text-center">Destino</th>
                    </tr>
                    @php
                        $totAdults = 0;
                        $totChildren = 0;
                        $totFollowUps = 0;
                    @endphp
                    @foreach ($solicitantes as $s)

                    <tr>
                        <td>{{ ucwords(strtolower($s->name . ' ' . substr_replace($s->surname, str_repeat('*', strlen($s->surname)),1))) }}</td>
                        <td>{{ ucwords(strtolower($s->city)) }}</td>
                        <td class="text-center">{{ $s->country->alfa3 ?? '--'}}</td>
                        <td class="text-center">{{ $s->followups_count }}</td>
                        <td class="text-center">{{ $s->adults }}</td>
                        <td class="text-center">{{ $s->children }}</td>
                        <td class="text-center">{{ $s->provinceto->acronym ?? '--' }}</td>
                        @php
                            $totAdults = $totAdults + $s->adults;
                            $totChildren = $totChildren + $s->children;
                            $totFollowUps = $totFollowUps + $s->followups_count;
                        @endphp
                    </tr>
                    @endforeach
                    <tr>
                        <td colspan="3">Total</td>
                        <td class="text-center">{{ $totFollowUps }}</td>
                        <td class="text-center">{{ $totAdults }}</td>
                        <td class="text-center">{{ $totChildren }}</td>
                        <td></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>


