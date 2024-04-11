@extends('layouts.master')
@section('title', 'Login')
@section('styles')
@endsection
@section('scripts')
<script src="https://www.google.com/recaptcha/api.js"></script>
@endsection

@section('content')


<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 ">
            <div class="card">
                <div class="card-header text-center">Inici de sessió</div>

                @error('error', 'login')
                <div class="alert alert-danger mt-2">{{ $message }}</div>
                @enderror

                @if (session('error'))
                <div class="alert alert-danger mt-2">{{ session('error') }}</div>

                @endif

                @if (session('success'))
                <div class="alert alert-success mt-2">{{ session('success') }}</div>
                @endif

                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="email_username" class="form-label">Adreça de correu o nom de usuari</label>

                            <input id="email_username" type="email_username" class="form-control @error('email_username','login') is-invalid @enderror" name="email_username" value="{{ old('email_username') }}" required autofocus>

                            @error('email_username','login')
                            <div class="alert alert-danger mt-2" name="error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Contrasenya</label>

                            <input id="password" type="password" class="form-control @error('password','login') is-invalid @enderror" name="password" required autocomplete="current-password">

                            @error('password','login')
                            <div class="alert alert-danger mt-2" name="error">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- input i div per el recaptcha -->
                        @if (session('loginIntents') >= 3)
                        <input type="hidden" name="g-token" id="recaptchaToken">
                        <div class="g-recaptcha " data-sitekey="6LeoIrgpAAAAAECV9u6e49Mb9Og9yzg5g0XcIDM7" data-callback='onSubmit' data-action='submit'>Submit</div>
                        @error('g-token','login')
                        <div class="alert alert-danger mt-2" name="error">{{ $message }}</div>
                        @enderror
                        @endif


                        <div class="mb-3 mt-2">
                            <button type="submit" class="btn btn-primary">
                                Inici de sessió
                            </button>
                        </div>

                        <div class="mt-3 d-grid gap-2">
                            <a href="/login-google" class="btn btn-danger">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" height="24" width="100%">
                                    <path fill="#ffffff" d="M386 400c45-42 65-112 53-179H260v74h102c-4 24-18 44-38 57z"></path>
                                    <path fill="#ffffff" d="M90 341a192 192 0 0 0 296 59l-62-48c-53 35-141 22-171-60z"></path>
                                    <path fill="#ffffff" d="M153 292c-8-25-8-48 0-73l-63-49c-23 46-30 111 0 171z"></path>
                                    <path fill="#ffffff" d="M153 219c22-69 116-109 179-50l55-54c-78-75-230-72-297 55z"></path>
                                </svg> Registra't amb Google
                            </a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>


<script>
    function onSubmit(token) {
        document.getElementById("recaptchaToken").value = token;
    }
</script>

@endsection