@extends('layouts.master')
@section('title', 'Home')

@section('content')
@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif
@endsection