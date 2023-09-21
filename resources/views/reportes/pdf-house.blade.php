@extends('reportes.pdf-resources-layout')

@section('resourceTitle')
    <table classs="page-title">
        <tr>
            <td width="100%" align="center"><h1>Viviendas</h1></td>
        </tr>
        <tr>
            <td>
                <br><br>
            </td>
        </tr>
    </table>
@endsection

@section('content')
        @foreach ($models as $model)
        <div style="border: 1px dotted #EFEFEF">
            <br />
            <table cellpadding="1">
                <tr>
                    <td width="25%">
                        <table cellpadding="2">
                            <tr>
                                <td class="border">Comunidad</td>
                                <td class="shadow data">{{ $model->community->name }}</td>
                            </tr>
                            <tr>
                                <td class="border">Provincia</td>
                                <td class="shadow data">{{ $model->province->name }}</td>
                            </tr>
                            <tr>
                                <td class="border">Localidad</td>
                                <td class="shadow data">{{ $model->town}}</td>
                            </tr>
                        </table>
                    </td>
                    <td width="25%">
                        <table cellpadding="2">
                            <tr>
                                <td class="border">Referencia</td>
                                <td class="shadow data">{{ $model->reference }}</td>
                            </tr>
                            <tr>
                                <td class="border">Régimen:</td>
                                <td class="shadow data">{{ $model->form->name }}</td>
                            </tr>
                            @if (!empty($model->price_rent ))
                                <tr>
                                    <td class="border">P. Alquiler</td>
                                    <td class="shadow data">{{ $model->price_rent }}</td>
                                </tr>
                            @endif
                            @if (!empty($model->price_sale))
                                <tr>
                                    <td class="border">P. Venta</td>
                                    <td class="shadow data">{{ $model->price_sale }}</td>
                                </tr>
                            @endif
                        </table>
                    </td>
                    <td width="50%">
                        <table cellpadding="2">
                            <tr>
                                <td class="border">Descripción:</td>
                            </tr>
                            <tr>
                                <td width=100% class="shadow text">{{ trim($model->description) }}</td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="3">
                        <table cellpadding="2">
                            <tr>
                                <td class="border" width="15%" align="center" valign="center">
                                    <a href="{{ env('APP_URL') .'/mapa/'. $model->reference }}">
                                        <img class="icon" src="/storage/icons/map-icon.png">
                                        <br />
                                        Ir al mapa
                                    </a>
                                </td>
                                <td align="center" width="15%" class="border"><img class="logo-cdr" src="/storage/{{ $model->cdr->logo }}"></td>
                                <td width="70%" class="border"><strong>{{ $model->cdr->name }}</strong><br />
                                    Dirección: {{ $model->cdr->address }},
                                    {{ $model->cdr->city }},
                                    {{ $model->cdr->pc }},
                                    {{ $model->cdr->province->name }}.
                                    <br />
                                    Horario: {{ $model->cdr->schedule }}
                                    <br />
                                    Email: {{ $model->cdr->email }}
                                    <br />
                                    Web: {{ $model->cdr->web }}
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </div>
            <br /><br />
        @endforeach
</div>
@endsection

