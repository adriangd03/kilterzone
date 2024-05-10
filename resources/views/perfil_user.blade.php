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
                        <div class="col-md-2 col-sm-3 offset-1">
                            <img src="{{$user->avatar}}" class="img-fluid rounded-circle avatar" alt="...">
                        </div>
                        <div class="col offset-md-1">
                            <div class="row">

                                <div class="col-2">
                                    <h4 class="card-title">{{$user->username}}</h4>
                                </div>
                               
                                <div class="col">
                                    <a href="" class="btn btn-secondary">Editar perfil</a>
    
                                </div>
                            </div>
                            <div class="row mt-2">

                                <div class="col">
                                    <ul class="list-inline list-group-flush ">
                                        <li id="" class="list-inline-item ps-0">Amics: <span id="totalAmics{{$user->id}}">{{$user->friends}}</span></li>
                                        <li class="list-inline-item ps-0">Publicacions: 0</li>
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
                </div>
            </div>



        </div>
    </div>

</div>
@endsection