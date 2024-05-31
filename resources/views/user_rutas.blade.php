@extends('layouts.master')

@section('title', 'Rutas')

@auth
@section('scripts')
@vite(['resources/js/veureRutas.js'])
@endsection
@endauth
@section('content')

<div class="container mt-5">
    <h1>Rutes guardades</h1>

    <hr class="mt-3 mb-3">



    <div class="row d-flex">
        @if(count($rutes) == 0)
        <div class="col justify-content-center text-center  w-100">
            <div class="card h-100 justify-content-center border-0 text-center">
                <div class="card-body border-0">
                    <h5 class="card-title text-center">No tens cap ruta guardada</h5>
                    <p class="card-text text-center">Dona like o escala alguna ruta per poder veure-le aquí més tard</p>
                </div>
            </div>
        </div>
        @endif
        @foreach ($rutes as $ruta)
        <div class="col-md-3 col-sm-4 col-6">
            <div class="card border-0 h-100">
                <div class="card-img-top row">
                <a href="{{ route('ruta', $ruta->id) }}">
                    {!! $ruta->ruta !!}
                </a>
                </div>
                <div class="card-body row d-flex flex-column mb-0 align-items-bottom">
                    <div class="d-flex mt-auto ms-0  align-items-center">
                        <a href="{{ route('perfil', ['id' => $ruta->creador->id]) }}">
                            <img class="rounded-circle border" src="{{ $ruta->creador->avatar }}" alt="avatar 1" style="width: 45px; height: 45px;">
                        </a>
                        <div class="card-text align-baseline align-items-baseline ms-2">
                            <a class="text-reset text-decoration-none" href="{{ route('perfil', ['id' => $ruta->creador->id]) }}">

                                <span class="fw-bold text-dark">{{ $ruta->creador->username }}</span>
                            </a>
                        </div>
                    </div>

                    <span class="card-title fs-5 fw-bold ">{{ $ruta->nom_ruta }} {{ $ruta->dificultat }} {{ $ruta->inclinacio }} </span>
                    <span class="card-title ">{{ $ruta->layout }}</span>
                    <p class="card-text">{{ $ruta->descripcio }}</p>
                    <div class="d-flex mb-2">
                        <div class="text-muted"><i class="bi bi-heart"></i> Likes: {{ $ruta->likes }}</div>
                        <div class="text-muted  "><img src="{{asset('img/climb.svg')}}" class="opacity-50" alt="escalada" style="width: 23px; height: 23px;">Escalat: {{ $ruta->escalada }}</div>
                    </div>
                    <span class="">Creat fa {{ $ruta->created }}</span>
                    <a href="{{ route('ruta', $ruta->id) }}" class="btn btn-primary ">Veure ruta</a>
                </div>
            </div>
        </div>
        @endforeach
    </div>








    @endsection