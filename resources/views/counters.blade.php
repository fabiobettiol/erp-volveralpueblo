@extends('layouts.basic')
@section('css')
    <style type="text/css">
        .card-counter{
            box-shadow: 2px 2px 10px #DADADA;
            margin: 5px;
            padding: 20px 10px;
            background-color: #fff;
            height: 100px;
            border-radius: 5px;
            transition: .3s linear all;
            position: relative;
          }

          .card-counter:hover{
            box-shadow: 4px 4px 20px #DADADA;
            transition: .3s linear all;
          }

          .card-counter.primary{
            background-color: #007bff;
            color: #FFF;
          }

          .card-counter.danger{
            background-color: #ef5350;
            color: #FFF;
          }

          .card-counter.success{
            background-color: #66bb6a;
            color: #FFF;
          }

          .card-counter.info{
            background-color: #26c6da;
            color: #FFF;
          }

          .card-counter i{
            font-size: 5em;
            opacity: 0.2;
          }

          .card-counter .count-numbers{
            position: absolute;
            right: 35px;
            top: 20px;
            font-size: 32px;
            display: block;
          }

          .card-counter .count-name{
            position: absolute;
            right: 35px;
            top: 65px;
            font-style: italic;
            text-transform: capitalize;
            opacity: 0.5;
            display: block;
            font-size: 18px;
          }

          .wrapper {
            height: auto;
          }

    </style>
@endsection
@section('content')
    <div class="container-fluid wrapper">
        <div class="row">
        <div class="col-md-2">
          <div class="card-counter primary">
            <i class="fa-solid fa-house-chimney"></i>
            <span class="count-numbers">{{ $counters['houses'] }}</span>
            <span class="count-name">Viviendas</span>
          </div>
        </div>

        <div class="col-md-2">
          <div class="card-counter danger">
            <i class="fa-solid fa-person-digging"></i>
            <span class="count-numbers">{{ $counters['jobs'] }}</span>
            <span class="count-name">Empleos</span>
          </div>
        </div>

        <div class="col-md-2">
          <div class="card-counter success">
            <i class="fa-solid fa-tractor"></i>
            <span class="count-numbers">{{ $counters['lands'] }}</span>
            <span class="count-name">Tierras</span>
          </div>
        </div>

        <div class="col-md-2">
          <div class="card-counter info">
            <i class="fa-solid fa-shop"></i>
            <span class="count-numbers">{{ $counters['businesses'] }}</span>
            <span class="count-name">Negocios</span>
          </div>
        </div>

        <div class="col-md-2">
          <div class="card-counter bg-warning">
            <i class="fa-solid fa-people-roof"></i>
            <span class="count-numbers">{{ $counters['families'] }}</span>
            <span class="count-name">Familias</span>
          </div>
        </div>

        <div class="col-md-2">
          <div class="card-counter bg-light">
            <i class="fas fa-users"></i>
            <span class="count-numbers">{{ $counters['demandants'] }}</span>
            <span class="count-name">Personas</span>
          </div>
        </div>
      </div>
    </div>
@endsection
