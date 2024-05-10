@extends('layouts.master')

@section('title', 'Home')

@auth
@section('scripts')
@vite(['resources/js/home.js'])
@endsection
@endauth
@section('content')



@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
<div class="alert alert-danger">{{ session('error') }}</div>
@endif








@endsection