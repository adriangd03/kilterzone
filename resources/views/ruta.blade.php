@extends('layouts.master')

@section('title', 'Ruta')

@section('scripts')
@vite('resources/js/ruta.js')
@endsection

@section('content')

<div class="container mt-5">
    <div class="row">
        <div class="col-12">
            <h1 class="fs-2">{{$ruta->nom_ruta}} {{$ruta->dificultat}}</h1>
        </div>
    </div>
    <hr class="mb-4">
    <div class="row">
        <div class="col-md-6 col-12">
            <p>{!! $ruta->ruta !!}</p>
        </div>
        <div class="col-md-6 col-12">
            <div class="card">
                <div class="card-header justify-content-between d-flex align-items-center">
                    <div>
                        <a href="{{route('perfil', $user->id)}}" class="text-reset text-decoration-none">
                            <img src="{{$user->avatar}}" alt="avatar" class="rounded-circle" style="width: 50px; height: 50px;" draggable="false">
                            <span>{{$user->username}}</span>
                        </a>
                    </div>
                    <div>
                        <button class="btn rounded-circle border-dark p-0 ps-1 pe-1 text-center  " type="button"><i class="bi bi-three-dots"></i><span class="visually-hidden">Opcions</span></button>
                    </div>
                </div>
                <div class="card-body comments">

                </div>
                <div class="card-footer">
                    <div class="d-flex">
                        <div class="d-flex">
                            <p class="m-0 text-break">{{$user->username}}: {{$ruta->descripcio}}</p>

                        </div>

                    </div>
                    <div class="d-flex justify-content-between">
                        <div class="d-flex">
                            <button id="like" class="btn me-2 p-0 border-0 @guest disabled @endguest" @guest disabled @endguest><i class="bi fs-5 bi-heart"></i></button>
                            <button id="compartir" class="btn me-2 p-0 border-0 @guest disabled @endguest" @guest disabled @endguest><i class="bi fs-5 bi-share"></i></button>
                        </div>
                        <div class="d-flex">
                            <span class="">{{$ruta->escalada}} </span>
                            <btn id="escalada" class="btn p-0 align-items-center border-0 @guest disabled @endguest" @guest disabled @endguest><img src="{{asset('img/climb.svg')}}" alt="escalada" style="width: 23px; height: 23px;"></btn>
                        </div>
                    </div>
                    <div class="d-flex">
                        <span><span id="totalLikes">{{$ruta->likes}}</span> likes</span>
                    </div>
                    <div class="d-flex">
                        <span class="">Creat fa {{ $ruta->created }}</span>
                    </div>
                    <div class="d-flex">
                        <form action="" method="post" class="w-100">
                            @csrf
                            <div class="input-group">
                                <input type="text" name="comentari" class="form-control @guest disabled @endguest" @guest disabled @endguest placeholder="@auth Comenta... @endauth @guest Inicia sessió per comentar @endguest">
                                <button type="submit" class="btn btn-primary @guest disabled @endguest" @guest disabled @endguest><i class="bi bi-send"></i></button>
                            </div>
                            <input type="hidden" name="rutaId" value="{{$ruta->id}}">
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>


    @endsection