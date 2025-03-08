@extends('adminlte::page')

@section('title', 'Usuarios')

@section('content_header')
    <h1>Usuarios</h1>
    {{ Breadcrumbs::render('usuarios') }}
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Table for Page 1</h3>
        </div>
        <div class="card-body">
            USERS CRUD
        </div>
    </div>
@stop