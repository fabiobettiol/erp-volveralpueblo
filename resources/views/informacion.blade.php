<x-app-layout>
    <x-slot name="page_header">
        <link href="assets/css/userflow.css" rel="stylesheet">
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="col-md-12"> 
                        <h2 class="text-center">Bienvenido / bienvenida!</h2>

                        <p class="text-center">Por favor <u>lee con detenimiento:</u></p>

                        <p>Para acceder a nuestras ofertas, deberás cumplir con uno de los siguientes requisitos:</p>

                        <ul>
                            <li>Tener nacionalidad española.</li>
                            <li>Ser ciudadadano de la Unión Europea.</li>
                            <li>Tener de un permiso de residencia y/o trabajo que te permita estar legalmente en España. </li>
                        </ul>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="exampleRadios" id="exampleRadios1" value="option1" checked>
                        <label class="form-check-label" for="exampleRadios1">
                            Default radio
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="exampleRadios" id="exampleRadios2" value="option2">
                        <label class="form-check-label" for="exampleRadios2">
                        Second default radio
                        </label>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
