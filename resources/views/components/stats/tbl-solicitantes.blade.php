@props([
    'solicitantes'
])
<div class="row">
    <div class="col">
        <table class="table table-sm table-striped ">
            <tr>
                <th>Nombre</th>
                <th>Ciudad</th>
                <th class="text-center">País</th>
                <th class="text-center">Adultos</th>
                <th class="text-center">Menores</th>
                <th class="text-center">Destino</th>
            </tr>
            @php
                $totAdults = 0;
                $totChildren = 0;
            @endphp
            @foreach ($solicitantes as $s)

            <tr>
                <td>{{ ucwords(strtolower($s->name . ' ' . substr_replace($s->surname, str_repeat('*', strlen($s->surname)),1))) }}</td>
                <td>{{ ucwords(strtolower($s->city)) }}</td>
                <td class="text-center">{{ $s->country->alfa3 ?? '--'}}</td>
                <td class="text-center">{{ $s->adults }}</td>
                <td class="text-center">{{ $s->children }}</td>
                <td class="text-center">{{ $s->provinceto->acronym ?? '--' }}</td>
                @php
                    $totAdults = $totAdults + $s->adults;
                    $totChildren = $totChildren + $s->children
                @endphp
            </tr>
            @endforeach
            <tr>
                <td colspan="3">Total</td>
                <td class="text-center">{{ $totAdults }}</td>
                <td class="text-center">{{ $totChildren }}</td>
                <td></td>
            </tr>
        </table>
    </div>
</div>