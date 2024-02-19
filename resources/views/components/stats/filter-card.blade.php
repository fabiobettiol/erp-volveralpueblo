@props([
    'cdr'
])

<form id="filtro-form" method="post">
    @csrf
    <input type="hidden" name="cdr" value="{{ $cdr }}">
    <div class="card filters shadow-sm my-4">
        <div class="card-body">
            <div class="d-flex flex-row">
                <span class="flex-grow-1 m-0 leyenda">Periodo</span>
                <span id="leyenda" class="leyenda"></span>
            </div>
            <div class="row py-2">
                <div class="col">
                    <select id="filtro-ano" name="filtro_ano" class="form-select">
                        <option value="0">Año</option>
                        <option value="2024">2024</option>
                        <option value="2023">2023</option>
                        <option value="2022">2022</option>
                        <option value="2021">2021</option>
                        <option value="2020">2020</option>
                    </select>
                </div>

                <div class="col">
                    <select id="filtro-mes" name="filtro_mes" class="form-select" disabled>
                        <option value="0">Mes</option>
                        <option value="1">Enero</option>
                        <option value="2">Febrero</option>
                        <option value="3">Marzo</option>
                        <option value="4">Abril</option>
                        <option value="5">Mayo</option>
                        <option value="6">Junio</option>
                        <option value="7">Julio</option>
                        <option value="8">Agosto</option>
                        <option value="9">Septiembre</option>
                        <option value="10">Octubre</option>
                        <option value="11">Noviembre</option>
                        <option value="12">Diciembre</option>
                    </select>
                </div>
                <div class="col">
                    <select id="filtro-trimestre" name="filtro_trimestre" class="form-select" disabled>
                        <option value="0" selected>Trimestre</option>
                        <option value="1">Primer Trimestre</option>
                        <option value="2">Segundo Trimestre</option>
                        <option value="3">Tercer Trimestre</option>
                        <option value="4">Cuarto Trimestre</option>
                    </select>
                </div>
                <div class="col">
                    <select id="filtro-semestre" name="filtro_semestre" class="form-select" disabled>
                        <option value="0" selected>Semestre</option>
                        <option value="1">Primer Semestre</option>
                        <option value="2">Segundo Semestre</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
</form>

