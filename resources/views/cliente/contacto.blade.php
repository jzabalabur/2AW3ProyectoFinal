@extends('layouts.app')

@section('title', 'Página de Diseño - Zablo')

@push('styles')
    <!--CSS especifico de la pagina-->
    @vite(['resources/css/contacto.css'])
@endpush

@section('content')

@stop
@push('scripts')
    @vite(['resources/js/contacto.js'])
@endpush