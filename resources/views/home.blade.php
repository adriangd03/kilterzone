@extends('layouts.master')

@section('title', 'Home')

@auth
@section('scripts')
@vite(['resources/js/home.js'])
@endsection
@endauth
@section('content')

<div class="container mt-5">
    <h1>Home</h1>

    <hr class="mt-3 mb-3">
    <div class="row d-flex">
        <div class="col">
            <div class="card border-0 border-end border-secondary-2 rounded-0">
                <div class="card-body">
                    @auth
                    @if($rutes->count() > 0)
                    <h2 class="fs-3 ">Rutes dels teus amics</h2>
                    @foreach($rutes as $ruta)
                    <div class="border-top border-bottom border-secondary-2 mb-2">
                        <div class="d-flex m-2 align-items-center">
                            <a href="{{ route('perfil', ['id' => $ruta->creador->id]) }}">
                                <img class="rounded-circle border" src="{{ $ruta->creador->avatar }}" alt="avatar 1" style="width: 45px; height: 45px;">
                            </a>
                            <div class="card-text align-baseline align-items-baseline ms-2">
                                <a class="text-reset text-decoration-none" href="{{ route('perfil', ['id' => $ruta->creador->id]) }}">

                                    <span class="fw-bold text-dark">{{ $ruta->creador->username }}</span>
                                </a>
                            </div>
                            <a href="{{route('ruta', $ruta->id)}}" class="btn btn-primary ms-auto">Veure Ruta</a>

                        </div>
                        <div class="d-flex m-2 ms-4 flex-column">
                            <div class="d-flex">
                                <div class="fw-bold text-dark ms-2">{{ $ruta->nom_ruta }} {{ $ruta->dificultat }} {{ $ruta->inclinacio }}</div>
                                <div class="ms-2">{{ $ruta->layout }}</div>
                            </div>

                            <div class="ms-2">{{ $ruta->descripcio }}</div>
                        </div>

                        <div class="d-flex justify-content-center text-center">
                            <div class="col-md-4 col-6 justify-content-center text-center">
                                <a href="{{ route('ruta', $ruta->id) }}" class="">
                                    {!! $ruta->ruta !!}
                                </a>
                            </div>
                        </div>
                        <div class="d-flex mb-2">
                            <span class="ms-2">Creat fa {{ $ruta->created }}</span>
                            <div class="text-muted ms-2"><i class="bi bi-heart"></i> Likes: {{ $ruta->likes }}</div>
                            <div class="text-muted ms-2 "><img src="{{asset('img/climb.svg')}}" class="opacity-50" alt="escalada" style="width: 23px; height: 23px;">Escalat: {{ $ruta->escalada }}</div>
                        </div>
                    </div>
                    @endforeach
                    @else
                    <h2 class="fs-3 ">Rutes populars de la setmana</h2>
                    @foreach($rutesPopulars as $ruta)
                    <div class="border-top border-bottom border-secondary-2 mb-2">
                        <div class="d-flex m-2 align-items-center">
                            <a href="{{ route('perfil', ['id' => $ruta->creador->id]) }}">
                                <img class="rounded-circle border" src="{{ $ruta->creador->avatar }}" alt="avatar 1" style="width: 45px; height: 45px;">
                            </a>
                            <div class="card-text align-baseline align-items-baseline ms-2">
                                <a class="text-reset text-decoration-none" href="{{ route('perfil', ['id' => $ruta->creador->id]) }}">

                                    <span class="fw-bold text-dark">{{ $ruta->creador->username }}</span>
                                </a>
                            </div>
                            <a href="{{route('ruta', $ruta->id)}}" class="btn btn-primary ms-auto">Veure Ruta</a>

                        </div>
                        <div class="d-flex m-2 ms-4 flex-column">
                            <div class="d-flex">
                                <div class="fw-bold text-dark ms-2">{{ $ruta->nom_ruta }} {{ $ruta->dificultat }} {{ $ruta->inclinacio }}</div>
                                <div class="ms-2">{{ $ruta->layout }}</div>
                            </div>

                            <div class="ms-2">{{ $ruta->descripcio }}</div>
                        </div>

                        <div class="d-flex justify-content-center text-center">
                            <div class="col-md-4 col-6 justify-content-center text-center">
                                <a href="{{ route('ruta', $ruta->id) }}" class="">
                                    {!! $ruta->ruta !!}
                                </a>
                            </div>
                        </div>
                        <div class="d-flex mb-2">
                            <span class="ms-2">Creat fa {{ $ruta->created }}</span>
                            <div class="text-muted ms-2"><i class="bi bi-heart"></i> Likes: {{ $ruta->likes }}</div>
                            <div class="text-muted ms-2 "><img src="{{asset('img/climb.svg')}}" class="opacity-50" alt="escalada" style="width: 23px; height: 23px;">Escalat: {{ $ruta->escalada }}</div>
                        </div>
                    </div>
                    @endforeach
                    @endif
                    @endauth

                    @guest
                    <h2 class="fs-3 ">Rutes populars de la setmana</h2>

                    @foreach($rutesPopulars as $ruta)
                    <div class="border-top border-bottom border-secondary-2 mb-2">
                        <div class="d-flex m-2 align-items-center">
                            <a href="{{ route('perfil', ['id' => $ruta->creador->id]) }}">
                                <img class="rounded-circle border" src="{{ $ruta->creador->avatar }}" alt="avatar 1" style="width: 45px; height: 45px;">
                            </a>
                            <div class="card-text align-baseline align-items-baseline ms-2">
                                <a class="text-reset text-decoration-none" href="{{ route('perfil', ['id' => $ruta->creador->id]) }}">

                                    <span class="fw-bold text-dark">{{ $ruta->creador->username }}</span>
                                </a>
                            </div>
                            <a href="{{route('ruta', $ruta->id)}}" class="btn btn-primary ms-auto">Veure Ruta</a>

                        </div>
                        <div class="d-flex m-2 ms-4 flex-column">
                            <div class="d-flex">
                                <div class="fw-bold text-dark ms-2">{{ $ruta->nom_ruta }} {{ $ruta->dificultat }} {{ $ruta->inclinacio }}</div>
                                <div class="ms-2">{{ $ruta->layout }}</div>
                            </div>

                            <div class="ms-2">{{ $ruta->descripcio }}</div>
                        </div>

                        <div class="d-flex justify-content-center text-center">
                            <div class="col-md-4 col-6 justify-content-center text-center">
                                <a href="{{ route('ruta', $ruta->id) }}" class="">
                                    {!! $ruta->ruta !!}
                                </a>
                            </div>
                        </div>
                        <div class="d-flex mb-2">
                            <span class="ms-2">Creat fa {{ $ruta->created }}</span>
                            <div class="text-muted ms-2"><i class="bi bi-heart"></i> Likes: {{ $ruta->likes }}</div>
                            <div class="text-muted ms-2 "><img src="{{asset('img/climb.svg')}}" class="opacity-50" alt="escalada" style="width: 23px; height: 23px;">Escalat: {{ $ruta->escalada }}</div>
                        </div>
                    </div>
                    @endforeach

                    @endguest

                </div>
            </div>
        </div>
        <div class="card border-0 col-4 ">
            <div class="card-body d-flex flex-column border-start border-secondary-2 rounded-0">
                @auth
                @if($rutes->count() > 0)
                <div class="mb-2">
                    <h2 class="fs-3 ">Rutes populars de la setmana</h2>
                </div>
                @foreach($rutesPopulars as $ruta)
                <div class="card border-0 rounded-0 border-bottom border-secondary-2 mb-2">
                    <div class="row g-0">
                        <div class="col-md-6 ">
                            <a href="{{ route('ruta', $ruta->id) }}">
                                {!! $ruta->ruta !!}
                            </a>
                        </div>
                        <div class="col-md-5 justify-content-center">
                            <div class="d-flex m-2 align-items-center">
                            <a href="{{ route('perfil', ['id' => $ruta->creador->id]) }}">
                                <img class="rounded-circle border" src="{{ $ruta->creador->avatar }}" alt="avatar 1" style="width: 45px; height: 45px;">
                            </a>
                            <div class="card-text align-baseline align-items-baseline ms-2">
                                <a class="text-reset text-decoration-none" href="{{ route('perfil', ['id' => $ruta->creador->id]) }}">

                                    <span class="fw-bold text-dark">{{ $ruta->creador->username }}</span>
                                </a>
                            </div>
                            </div>
                            <div class="ms-1">
                                <h5 class="card-title fw-bold text-center">{{ $ruta->nom_ruta }} {{ $ruta->dificultat }} {{ $ruta->inclinacio }}</h5>

                                <div class="text-center w-100">
                                    <a href="{{ route('ruta', $ruta->id) }}" class="btn btn-primary">Veure Ruta</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex mt-2">
                        <span class="ms-2">Creat fa {{ $ruta->created }}</span>
                        <div class="text-muted ms-2"><i class="bi bi-heart"></i> Likes: {{ $ruta->likes }}</div>
                        <div class="text-muted ms-2 "><img src="{{asset('img/climb.svg')}}" class="opacity-50" alt="escalada" style="width: 23px; height: 23px;">Escalat: {{ $ruta->escalada }}</div>
                    </div>
                </div>
                @endforeach

                @else
                <div class="mb-2">
                    <h2 class="fs-3 ">Usuaris recomenats</h2>
                </div>
                @foreach($recomendacioFriends as $recomendacioFriend)

                <a href="{{ route('perfil', $recomendacioFriend->id) }}" class=" text-decoration-none rounded user-hover">
                    <div class="row m-1 w-100 pt-2 pb-2 ">
                        <div class="col align-content-center">
                            <div class="d-flex d-inine align-content-center align-items-center">
                                <img class="rounded-circle border" src="{{ $recomendacioFriend->avatar }}" alt="avatar 1" style="width: 45px; height: 45px;">
                                <div class="card-text ms-2">
                                    <div class="friend-username text-dark">{{ $recomendacioFriend->username }}</div>
                                </div>

                            </div>
                        </div>

                    </div>
                </a>

                @endforeach

                @endif
                @endauth

                @guest

                <div class="mb-2">
                    <h2 class="fs-3 ">Usuaris recomenats</h2>
                </div>
                @foreach($usuarisRecomenats as $recomendacioUsuari)
                <a href="{{ route('perfil', $recomendacioUsuari->id) }}" class=" text-decoration-none border-bottom border-top border-secondary-2 rounded user-hover">
                    <div class="row m-1 w-100 pt-2 pb-2 ">
                        <div class="col align-content-center">
                            <div class="d-flex d-inine align-content-center align-items-center">
                                <img class="rounded-circle border" src="{{ $recomendacioUsuari->avatar }}" alt="avatar 1" style="width: 45px; height: 45px;">
                                <div class="card-text ms-2">
                                    <div class="friend-username text-dark">{{ $recomendacioUsuari->username }}</div>
                                </div>

                            </div>
                        </div>

                    </div>
                </a>

                @endforeach


                @endguest
            </div>
        </div>
    </div>
</div>

@endsection