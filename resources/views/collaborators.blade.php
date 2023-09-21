<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <title>Colaboradores</title>

        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
		<script src="https://kit.fontawesome.com/3abe3c36cd.js" crossorigin="anonymous"></script>

        <style type="text/css">
            body {
                margin-top: 1rem;
            }

            h3 {
                font-size: 14px;
            }
            .cdr-container {
                padding: 1rem;
            }

            .cdr-data:hover {
                background: #efefef;
            }

            .cdr-container a {
                text-decoration: none;
                color: #000;
            }

            .cdr-container a:hover {
               color: #000;
            }

            .cdr-data {
                border: 1px dotted #DDD;
                padding: 1rem;
            }

            .cdr-data p {
                font-size: 10px;
            }

            .btn-tipo {
                color: #B0B0B0;
                font-size: 0.75rem;
                line-height: 1rem;
                background: #646464;
                padding: 0.5rem 1rem;
                border-radius: 0.5rem;
                margin-bottom: 0.5rem;
                display: inline-block;
            }

            .btn-tipo:hover {
                text-decoration: none;
                color: #FFF;
            }

            .btn-tipo.active {
                color: #FFF;
            }

        </style>
    </head>
    <body>
    	<div class="container-fluid">
            <div class="row">
                <div class="col">
                    @foreach($types as $type)
                        <a class="btn-tipo <?php echo $type->id == "1" ? ' active cdrs' : ''; ?>" data-tipo="{{ $type->id }}" href="#">{{ $type->name }}</a>
                    @endforeach
                    <a class="btn-tipo" data-tipo="todas" href="#">Todas</a>
                </div>
            </div>
            <div class="row">
                @foreach ($cdrs as $cdr)
                    <div class="cdr-container cdr-tipo-{{ $cdr->cdrtype->id }} col-xl-2 col-md-3 col-sm-6 col-xs-12">
                        <a target="_blank" href="{{ $cdr->web }}">
                            <div class="cdr-data">
                                <h3>{{ $cdr->name }}</h3>
                                <img class="img-fluid" src="{{ '/storage/' . $cdr->logo }}">
                                <p>
                                    {{ $cdr->cdrtype->name}} <br/>
                                    {{ $cdr->city}}, {{ $cdr->community->name }}
                                </p>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
    	</div>

		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

        <script>
            $( document ).ready(function() {
              $('.cdrs').click();
            });

            $('.btn-tipo').on('click', function (e) {
                e.preventDefault();

                $('.btn-tipo').removeClass('active');
                $(this).addClass('active');

                if ($(this).data('tipo') == 'todas') {
                    $('.cdr-container').fadeIn(200);
                } else {
                    $('.cdr-container').hide();
                    var c = ('.cdr-tipo-' + $(this).data('tipo'));
                    $(c).fadeIn(200);
                }
            });
        </script>


    </body>
</html>
