@extends('adminlte::page')

@section('title', 'Crear Web - ZabloAdmin')

@section('content_header')
    {{ Breadcrumbs::render('usuarios.create') }}
@stop

@section('content')
<div class="mx-auto px-6">
    <h2 class="text-3xl font-bold text-gray-800 mb-6">Crear Nueva Web</h2>

    @if (session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
        {{ session('error') }}
    </div>
    @endif
    <div class="bg-white p-6 rounded-lg shadow-md">
        <!-- Formulario de creación -->
        <form action="{{ route('admin.webs.store') }}" method="POST">
            @csrf

            <!-- Campo: Nombre -->
            <div class="mb-4">
                <label for="name" class="block text-gray-700">Nombre</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" 
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Campo: URL -->
            <div class="mb-4">
                <label for="url" class="block text-gray-700">URL</label>
                <input type="url" name="url" id="url" value="{{ old('url') }}" 
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                @error('url')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

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