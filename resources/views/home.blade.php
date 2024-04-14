@extends('layouts.master')
@section('title', 'Home')

@section('styles')
@vite('resources/css/app.css')
@endsection

@section('scripts')
@vite(['resources/js/app.js'])
@endsection

@section('content')

@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
<div class="alert alert-danger">{{ session('error') }}</div>
@endif





@auth
<button class="btn btn-primary" data-bs-toggle="offcanvas" href="#offcanvasExample" role="button" aria-controls="offcanvasExample">
    Obrir xat
</button>
<input type="hidden" id="userId" value="{{ Auth::user()->id }}">
@endauth


@auth
<!-- Offcanvas Chat -->

<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">



    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="offcanvasExampleLabel">Xat</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body h-100">
        <div class="row h-100">
            <div class="col-12">
                <div class="row mb-3">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>Usuaris</h4>
                            </div>
                            <div class="card-body h-100 overflow-auto mh-100">
                                <div id="users" class="users-list row mh-100">
                                    @foreach($friends as $friend)
                                    <div class="user col-4" id="{{ $friend->id }}">
                                        <div class="user-info">
                                            <img class="rounded-circle" src="{{$friend->avatar}}" alt="avatar 1" style="width: 45px; height: 100%;">
                                            <div class="card-text">
                                                <div class="user-name">{{ $friend->username }}</div>
                                                <div class="user-status">Online</div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>


                <div class="card h-75 mh-75">
                    <div class="card-header">
                        <h4>Xat</h4>
                    </div>
                    <div class="card-body h-100 overflow-auto mh-100 ">
                        <div id="chat-user" class="chat-list mh-100 ">

                        </div>
                    </div>
                    <div class="card-footer">
                        <form action="" id='form' method="post">
                            <div class="input-group">
                                <input type="text" name="message" class="form-control" placeholder="Escriu el teu missatge aquí...">
                                <input type="hidden" name="receiver" id="receiver" value="">
                                <button type="submit" class="btn btn-primary">Send</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endauth


@endsection