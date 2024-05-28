@extends('layouts.master')

@section('title', 'Configuració')

@section('scripts')
@vite('resources/js/configuracio.js')
@endsection

@section('content')

<div class="container mt-5">
    <div class="row">
        <div class="col">
            <h1>Configuració</h1>

            <hr class="mt-3 mb-3">

            <div class="card border-0">
                <div class="card-body">
                    <h5 class="card-title">Editar perfil</h5>
                    <div class="row mt-3 mb-3">
                        <div class="col">
                            <div class="d-flex ">
                                <h6>Imatge de perfil</h6>
                            </div>
                            <div class="d-flex align-items-center">
                                <img src="{{ Auth::user()->avatar }}" class="rounded-circle border" alt="avatar" style="width: 60px; height: 60px;">
                                <span class="ms-2 text-muted">{{ Auth::user()->username }}</span>
                                <button type="button" class="btn btn-secondary ms-4 " data-bs-toggle="modal" data-bs-target="#modalAvatar">Canviar</button>
                            </div>

                            @error('avatar', 'actualitzarImatgePerfil')
                            <div class="alert alert-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <form method="POST" action="{{route('actualitzarDades')}}">
                        @csrf
                        <div class="mb-3">
                            <label for="username" class="form-label">Nom d'usuari</label>
                            <input type="text" class="form-control" id="username" name="username" value="{{ old('username', Auth::user()->username) }}">
                            @error('username', 'actualitzarDades')
                            <div class="alert alert-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Descripció</label>
                            <textarea class="form-control" id="description" name="description" rows="3">{{ old('description', Auth::user()->description) }}</textarea>
                            @error('description', 'actualitzarDades')
                            <div class="alert alert-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">Actualitzar</button>
                    </form>
                </div>
            </div>
            <hr class="mt-3 mb-3">
            <div class="card border-0">
                <div class="card-body">
                    <h5 class="card-title">Canviar contrasenya</h5>
                    <form method="POST" action="{{route('actualitzarContrasenya')}}">
                        @csrf
                        <div class="mb-3">
                            <label for="old_password" class="form-label">Contrasenya actual</label>
                            <input type="password" class="form-control" id="old_password" name="old_password">
                            @error('old_password', 'actualitzarContrasenya')
                            <div class="alert alert-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Nova contrasenya</label>
                            <input type="password" class="form-control" id="password" name="password">
                            @error('password', 'actualitzarContrasenya')
                            <div class="alert alert-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Repeteix la nova contrasenya</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                            @error('password_confirmation', 'actualitzarContrasenya')
                            <div class="alert alert-danger mt-2">{{ $message }}</div>
                            @enderror

                        </div>
                        <button type="submit" class="btn btn-primary">Canviar contrasenya</button>
                    </form>
                </div>

            </div>
        </div>
    </div>

</div>


<!-- ModalAvatar -->
<div class="modal fade" id="modalAvatar" tabindex="-1" aria-labelledby="modalAvatarLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header ">
                <h4 class="modal-title m-auto" id="modalAvatarLabel">Canviar imatge de perfil</h4>

            </div>
            <div class="modal-body p-0">
                <button id="btnAvatar" type="button" class="btn rounded-0  user-hover w-100 border border-bottom border-top pt-3 pb-3 fs-5 p-1 text-center text-primary ">Pujar imatge</button>
                <form method="POST" action="{{ route('eliminarImatgePerfil') }}">
                    @csrf
                    <button id="btnEliminarAvatar" type="submit" class="btn rounded-0 text-danger w-100 border border-bottom user-hover pt-3 pb-3 fs-5 p-1 text-center mt-0">Eliminar imatge actual</button>
                </form>
                
                <button type="button" class="btn rounded-top-0 w-100 border border-bottom user-hover pt-3 pb-3 fs-5 p-1 text-center mt-0" data-bs-dismiss="modal" aria-label="Close">Cancel·lar</button>
                <form id="formAvatar" method="POST" action="{{ route('actualitzarImatgePerfil') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="file" id="avatar" name="avatar" class="d-none">
                </form>
            </div>

        </div>
    </div>
</div>

@endsection