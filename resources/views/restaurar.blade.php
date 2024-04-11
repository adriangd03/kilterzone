@extends('layouts.master')

@section('title', 'Restaurar')

@section('content')
<div class="container py-3">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header text-center ">
                    Canviar contrasenya

                </div>

                <form method="POST" action="{{ route('restaurarContrasenya.post') }}">
                    <div class="card-body">
                        @csrf
                        <!-- Camp de email -->
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{old('email')}}" required>
                            @error('email','restaurar')
                            <div class="alert alert-danger mt-2" name="error">{{ $message }}</div>
                            @enderror
                        </div>
                        <!-- Camp de password i de confirmació de password -->
                        <div class="mb-3">
                            <label for="password" class="form-label ">Nova contrasenya</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                            @error('password','restaurar')
                            <div class="alert alert-danger mt-2" name="error">{{ $message }}</div>
                            @enderror

                        </div>
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label ">Confirmar contrasenya</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                            @error('password_confirmation','restaurar')
                            <div class="alert alert-danger mt-2" name="error">{{ $message }}</div>
                            @enderror
                        </div>

                        <input type="hidden" name="token" value="{{ $token }}">


                        
                        @error('error','restaurar')
                        <div class="alert alert-danger mt-2" name="error"> {{ $message }}</div>
                        @enderror
                        
                        <div class="mb-3 mt-2">
                            <button type="submit" class="btn btn-primary">
                                Restaurar contrasenya
                            </button>
                        </div>
                    </form>
                    
                </div>

                @if(session('success'))

                <div class="alert alert-success mt-2" name="error">{{ session('success') }}</div>
                @endif

                @if(session('error'))
                <div class="alert alert-danger mt-2" name="error">{{ session('error') }}</div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection