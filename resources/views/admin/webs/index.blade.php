@extends('adminlte::page')

@section('title', 'Webs - ZabloAdmin')

@section('content_header')
    <h1>Webs</h1>
    {{ Breadcrumbs::render('webs') }}
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Table for Page 2</h3>
        </div>
        <div class="card-body">
         WEBS CRUD
        </div>
    </div>
@stop