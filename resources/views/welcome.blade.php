<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Volver al Pueblo</title>

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

        <!-- Styles -->
        <style>
            body {
                background: url({{ Storage::url($background) }});
                background-repeat: no-repeat;
                background-color: #FFF;
                background-size: cover;
                font-family: 'Nunito', sans-serif;
                background-position: center;
            }

            .login-box {
                background: #000c;
                color: #FFF;
                padding: 2rem;
            }
        </style>
    </head>
    <body>
        <div class="container-fluid">
            <div class="jumbotron d-flex align-items-center min-vh-100">
                <div class="col-md-6 col-xs-12 mx-auto text-center login-box">
                    <div class="row">
                        <div class="col-md-6 col-xs-12">
                            <img src="/storage/logos/logo-volver-al-pueblo-inverso.png" style="width: 200px;">
                        </div>
                        <div class="col-md-6 col-xs-12">
                             <img src="/storage/logos/logo-coceder-inverso.png" style="width: 110px;">
                        </div>
                    </div>
                    @if (Route::has('login'))
                        <div class="hidden fixed top-0 right-0 px-6 py-4 sm:block">
                            @auth
                                <a href="{{ url('/dashboard') }}" class="text-sm text-gray-700 underline">Dashboard</a>
                            @else
                                <a class="btn   btn-success" href="{{ route('login') }}" class="text-sm text-gray-700 underline">Accede</a>

                                @if (Route::has('register'))
                                    <a class="btn   btn-info" href="{{ route('register') }}" class="ml-4 text-sm text-gray-700 underline">Regístrate</a>
                                @endif
                            @endauth
                        </div>
                    @endif                
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    </body>
</html>
