@extends('adminlte::page')

@section('title', 'Crear Página - ZabloAdmin')

@section('content_header')
    {{ Breadcrumbs::render('pages.create', $web) }}
@stop

@section('content')
<div class="mx-auto px-6">
    <h2 class="text-3xl font-bold text-gray-800 mb-6">Crear Nueva Pagina</h2>

    @if (session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
        {{ session('error') }}
    </div>
    @endif
    <div class="bg-white p-6 rounded-lg shadow-md">
        <!-- Formulario de creación -->
        <form action="{{ route('admin.pages.store') }}" method="POST">
            @csrf



            <!-- Botones de acciones -->
            <div class="mt-6 flex space-x-4">
                <button type="submit" 
                        class="inline-flex items-center bg-green-500 hover:bg-green-600 text-white text-sm px-3 py-2 rounded-lg shadow-md transition">
                    💾 Guardar
                </button>
                <a href="{{ route('admin.webs.index') }}" 
                   class="inline-flex items-center bg-gray-500 hover:bg-gray-600 text-white text-sm px-3 py-2 rounded-lg shadow-md transition">
                    🚫 Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
@endsection