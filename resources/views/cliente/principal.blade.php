@extends('layouts.app')

@section('title', 'Página de Diseño - Zablo')

@push('styles')
    <!--CSS especifico de la pagina-->
    @vite(['resources/css/principal.css'])
@endpush

@section('content')

@stop
@push('scripts')
    @vite(['resources/js/principal.js'])
@endpush