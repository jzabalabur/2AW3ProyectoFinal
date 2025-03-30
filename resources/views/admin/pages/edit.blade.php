@extends('adminlte::page')

@section('title', 'Editar Página - ZabloAdmin')

@section('content_header')
    {{ Breadcrumbs::render('pages.edit', $web, $page) }}
@stop

@section('content')
<div class="mx-auto px-6">
    <h2 class="text-3xl font-bold text-gray-800 mb-6">Editar página {{$page->type}} de {{$web->url}}</h2>

    @if (session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
        {{ session('error') }}
    </div>
    @endif
    <div class="bg-white p-6 rounded-lg shadow-md">
        <!-- Botón para volver a la lista -->
        <div class="mb-6">
            <a href="{{ route('admin.webs.pages.index', $web) }}" 
               class="inline-flex items-center bg-gray-500 hover:bg-gray-600 text-white text-sm px-3 py-2 rounded-lg shadow-md transition">
                ← Volver a la lista
            </a>
        </div>

        <!-- Formulario de edición -->
        <form action="{{ route('admin.webs.pages.update', ['web' => $web, 'page' => $page]) }}" method="POST">
            @csrf
            @method('PUT')

            TO DO

            <!-- Botones de acciones -->
            <div class="mt-6 flex space-x-4">
                <button type="submit" 
                        class="inline-flex items-center bg-green-500 hover:bg-green-600 text-white text-sm px-3 py-2 rounded-lg shadow-md transition">
                    💾 Guardar Cambios
                </button>
                <a href="{{ route('admin.webs.pages.index', ['web' => $web, 'page' => $page]) }}" 
                   class="inline-flex items-center bg-gray-500 hover:bg-gray-600 text-white text-sm px-3 py-2 rounded-lg shadow-md transition">
                    🚫 Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
@endsection