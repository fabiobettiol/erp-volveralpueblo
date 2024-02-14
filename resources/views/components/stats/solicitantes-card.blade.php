@props([
    'title' => '',
    'icon' => '',
    'color' => '',
    'value',
])

<div class="card solicitantes-card shadow-sm">
    <div class="p-2 card-body">
        <h5 class="m-2 card-title">{{ $title }}</h5>
        <div class="card-text">
            <div class="row">
                <div class="col">
                    <div class="d-flex">
                        <div><i class="icono-{{ $color }} fa-solid fa-person"></i></div>
                        <div class="d-flex flex-column">
                            <p class="h4 my-1"><strong>{{ $value }}</strong></p>
                            <p class="m-0">Hombres</p>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="d-flex">
                        <div><i class="icono-card fa-solid fa-person-dress"></i></div>
                        <div class="d-flex flex-column">
                            <p class="h4 my-1"><strong>{{ $value }}</strong></p>
                            <p class="m-0">Mujeres</p>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="d-flex">
                        <div><i class="icono-{{ $color }} fa-solid fa-child"></i></div>
                        <div class="d-flex flex-column">
                            <p class="h4 my-1"><strong>{{ $value }}</strong></p>
                            <p class="m-0">Menores</p>
                        </div>
                    </div>
                </div>
                <div class="col"><p><i class="icono-card fa-solid fa-eye"></i>Ver Todos</p></div>
            </div>
        </div>
    </div>
</div>




