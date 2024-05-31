@extends('layouts.master')

@section('title', 'Perfil')

@auth
@section('scripts')
@vite(['resources/js/perfil.js'])
@endsection
@endauth

@section('content')

<div class="container mt-5">
    <div class="row">
        <div class="col">
            <div class="card border-0">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-2 col-sm-3 ">
                            <img src="{{$user->avatar}}" class="rounded-circle border" height="140px" width="140px" alt="...">
                        </div>
                        <div class="col ">
                            <div class="row">

                                <div class="col-2">
                                    <h4 class="card-title">{{$user->username}}</h4>
                                </div>
                                @auth
                                <div id="divFormFriend{{$user->id}}" class="col">
                                    @if($isFriend)
                                    <form id="formEliminarAmic" method="post">
                                        @csrf
                                        <input type="hidden" name="friend_id" value="{{$user->id}}">
                                        <button id="btnUnfriend" class="btn btn-danger">Eliminar amic</button>
                                    </form>
                                    @else
                                    @if($user->sentFriendRequest)
                                    <form id="formAfegirAmic{{$user->id}}" name="formSolAmic" method="POST">
                                        @csrf
                                        <input type="hidden" name="friend_id" value="{{ $user->id }}">
                                        <button class="btn btn-dark border border-white" type="submit" disabled>Sol·licitud enviada</button>
                                    </form>
                                    @else
                                    <form id="formAfegirAmic{{$user->id}}" name="formSolAmic" method="POST">
                                        @csrf
                                        <input type="hidden" name="friend_id" value="{{ $user->id }}">
                                        <button class="btn btn-dark border border-white" type="submit">Afegir amic</button>
                                    </form>
                                    @endif
                                    @endif
                                </div>
                                @endauth
                            </div>
                            <div class="row mt-2">

                                <div class="col">
                                    <ul class="list-inline list-group-flush ">
                                        <li id="" class="list-inline-item ps-0">Amics:<span id="totalAmics{{$user->id}}">{{$user->friends}}</span></li>
                                        <li class="list-inline-item ps-0">Publicacions: {{ $rutes->count() }}</li>
                                    </ul>
                                    <p class="card-text">@if($user->description){{$user->description}}@else Aquest usuari encara no ha afegit la seva descripció, però puc dir que és una persona molt interessant. @endif</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <hr class="mt-3 mb-3">
            <div class="card border-0">
                <div class="card-body">
                    <h5 class="card-title">Publicacions</h5>
                    <div class="row">
                        @foreach($rutes as $ruta)
                        <div class="col-md-3 col-sm-4 col-6">
                            <div class="card border-0 h-100">
                                <div class="card-img-top row">
                                    <a href="{{ route('ruta', $ruta->id) }}">
                                        {!! $ruta->ruta !!}
                                    </a>
                                </div>
                                <div class="card-body ps-0 row d-flex flex-column mb-0 align-items-bottom">
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
                                <span class="card-title fw-bold ">{{ $ruta->layout }}</span>
                                <p class="card-text">{{ $ruta->descripcio }}</p>
                                <div class="d-flex mb-2">
                                    <div class="text-muted"><i class="bi bi-heart"></i> Likes: {{ $ruta->likes }}</div>
                                    <div class="text-muted"><img src="{{asset('img/climb.svg')}}" class="opacity-50" alt="escalada" style="width: 23px; height: 23px;">Escalat: {{ $ruta->escalada }}</div>
                                </div>
                                <span class="">Creat fa {{ $ruta->created }}</span>
                                <a href="{{ route('ruta', $ruta->id) }}" class="btn btn-primary ">Veure ruta</a>
                            </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>



        </div>
    </div>

</div>
@endsection