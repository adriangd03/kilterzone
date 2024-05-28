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
                            <img src="{{$user->avatar}}" alt="avatar" class="rounded-circle border" style="width: 50px; height: 50px;" draggable="false">
                            <span>{{$user->username}}</span>
                        </a>
                    </div>
                    <div>
                        <button class="btn rounded-circle border-dark p-0 ps-1 pe-1 text-center  " type="button"><i class="bi bi-three-dots"></i><span class="visually-hidden">Opcions</span></button>
                    </div>
                </div>
                <div id="comentaris" class="card-body comments">
                    @foreach($comentaris as $comentari)
                    <div class="d-flex mb-4">
                        <div class="d-flex w-100">
                            <a href="{{route('perfil', $comentari->user->id)}}" class="text-reset text-decoration-none">
                            <img src="{{$comentari->user->avatar}}" alt="avatar" class="rounded-circle me-1 border" style="width: 40px; height: 40px;" draggable="false">
                            </a>
                            <div class="d-flex w-100 flex-column">
                                <div class="d-flex align-items-baseline">
                                    <a href="{{route('perfil', $comentari->user->id)}}" class="text-reset text-decoration-none">
                                        <span class="fw-bold">{{$comentari->user->username}}</span>
                                    </a>
                                    <p class="m-0 ms-2 small ">{{$comentari->comentari}}</p>
                                </div>
                                <div class="d-flex">
                                    <span class="text-muted small me-2"> {{$comentari->created}}</span>
                                    <button class="btn p-0 border-0 text-muted" type="button"><span class="small align-top">Respondre</span></button>
                                </div>
                            </div>
                            <button class="btn p-0 border-0 me-2"><i class="bi bi-heart"></i></button>
                        </div>
                    </div>
                    @endforeach

                </div>
                <div class="card-footer">
                    <div class="d-flex">
                        <div class="d-flex">
                            <p class="m-0 text-break">{{$user->username}}: {{$ruta->descripcio}}</p>

                        </div>

                    </div>
                    <div class="d-flex justify-content-between">
                        <div class="d-flex">
                            @guest
                            <button id="like" class="btn me-2 p-0 border-0  disabled" disabled><i class="bi fs-5 bi-heart"></i></button>
                            @endguest
                            @auth
                            <button id="like" class="btn me-2 p-0 border-0"><i class="bi fs-5 @if($liked) bi-heart-fill text-danger  @else bi-heart @endif"></i></button>
                            @endauth
                            <button id="compartir" class="btn me-2 p-0 border-0 @guest disabled @endguest" @guest disabled @endguest><i class="bi fs-5 bi-share"></i></button>
                        </div>
                        <div class="d-flex">
                            <span id="totalEscalat">{{$ruta->escalada}} </span>
                            <btn id="escalat" class="btn p-0 align-items-center border-0 @guest disabled @endguest" @guest disabled @endguest><img src="{{asset('img/climb.svg')}}" alt="escalada" style="width: 23px; height: 23px;"></btn>
                        </div>
                    </div>
                    <div class="d-flex">
                        <span><span id="totalLikes">{{$ruta->likes}}</span> likes</span>
                    </div>
                    <div class="d-flex">
                        <span class="">Creat fa {{ $ruta->created }}</span>
                    </div>
                    <div class="d-flex">
                        <form id="formComentari" action="" method="post" class="w-100">
                            @csrf
                            <div class="input-group">
                                <input type="text" name="comentari" class="form-control @guest disabled @endguest" @guest disabled @endguest placeholder="@auth Comenta... @endauth @guest Inicia sessió per comentar @endguest">
                                <button type="submit" class="btn btn-primary @guest disabled @endguest" @guest disabled @endguest><i class="bi bi-send"></i></button>
                            </div>
                            <input id="rutaId" type="hidden" name="rutaId" value="{{$ruta->id}}">
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>


    @endsection