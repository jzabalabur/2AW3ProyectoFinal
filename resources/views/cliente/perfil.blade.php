@extends('layouts.app')

@section('title', 'Perfil de Usuario')

@push('styles')
    <!--CSS especifico de la pagina-->
    @vite(['resources/css/perfil.css'])
@endpush

@section('content')
    <h1>Perfil de Usuario</h1>
    <p>Esta es la página de perfil del cliente.</p>
@stop
