@extends('layouts.master')
@section('title', 'Registre')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 ">
            <div class="card">
                <div class="card-header text-center">Registre</div>
                @error('error', 'registre')
                <div class="alert alert-danger mt-2">{{ $message }}</div>
                @enderror


                <div class="card-body">
                    <form method="POST" action="{{ route('registre') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="email" class="form-label">adreça de correu</label>

                            <input id="email" type="email" class="form-control @error('email', 'registre') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                            @error('email', 'registre')
                            <div class="alert alert-danger mt-2" name="error">{{ $message }}</div>

                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="username" class="form-label">Nom d'usuari</label>

                            <input id="username" type="text" class="form-control @error('username', 'registre') is-invalid @enderror" name="username" value="{{ old('username') }}" required autocomplete="username" autofocus>

                            @error('username', 'registre')
                            <div class="alert alert-danger mt-2" name="error">{{ $message }}</div>

                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Contrasenya</label>

                            <input id="password" type="password" class="form-control @error('password', 'registre') is-invalid @enderror" name="password" required autocomplete="current-password">

                            @error('password', 'registre')
                            <div class="alert alert-danger mt-2" name="error">{{ $message }}</div>

                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirma la contrasenya</label>

                            <input id="password_confirmation" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                        </div>

                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary">
                                Registrat
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

@endsection