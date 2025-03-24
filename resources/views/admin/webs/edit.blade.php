@extends('adminlte::page')

@section('title', 'Editar Web - ZabloAdmin')

@section('content_header')
    {{ Breadcrumbs::render('webs.edit', $web) }}
@stop

@section('content')
<div class="mx-auto px-6">
    <h2 class="text-3xl font-bold text-gray-800 mb-6">Editar Web</h2>

    @if (session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
        {{ session('error') }}
    </div>
    @endif
    <div class="bg-white p-6 rounded-lg shadow-md">
        <!-- Botón para volver a la lista -->
        <div class="mb-6">
            <a href="{{ route('admin.webs.index') }}" 
               class="inline-flex items-center bg-gray-500 hover:bg-gray-600 text-white text-sm px-3 py-2 rounded-lg shadow-md transition">
                ← Volver a la lista
            </a>
        </div>

        <!-- Formulario de edición -->
        <form action="{{ route('admin.webs.update', $web) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="space-y-4">
                <!-- Campo: Nombre -->
                <div class="border-b border-gray-200 pb-4">
                    <label for="name" class="text-lg font-semibold text-gray-700">Nombre</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $web->name) }}" 
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Campo: URL -->
                <div class="border-b border-gray-200 pb-4">
                    <label for="url" class="text-lg font-semibold text-gray-700">url</label>
                    <input type="url" name="url" id="url" value="{{ old('url', $web->url) }}" 
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    @error('url')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

            </div>

            <!-- Botones de acciones -->
            <div class="mt-6 flex space-x-4">
                <button type="submit" 
                        class="inline-flex items-center bg-green-500 hover:bg-green-600 text-white text-sm px-3 py-2 rounded-lg shadow-md transition">
                    💾 Guardar Cambios
                </button>
                <a href="{{ route('admin.webs.show', $web) }}" 
                   class="inline-flex items-center bg-gray-500 hover:bg-gray-600 text-white text-sm px-3 py-2 rounded-lg shadow-md transition">
                    🚫 Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
@endsection