@extends('adminlte::page')

@section('title', 'Detalles de la página - ZabloAdmin')

@section('content_header')
    {{ Breadcrumbs::render('pages.show', $web, $page) }}
@stop

@section('content')

<div class="mx-auto px-6">
    <h2 class="text-3xl font-bold text-gray-800 mb-6">Detalles de la página {{$page->type}} de {{$web->url}}</h2>
    @if (session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
        {{ session('success') }}
    </div>
    @endif
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

        <!-- Información de la página -->
        TO DO

        <!-- Botones de acciones -->
        <div class="mt-6 flex space-x-4">
            <a href="{{ route('admin.webs.edit', $web) }}" 
               class="inline-flex items-center bg-blue-500 hover:bg-blue-600 text-white text-sm px-3 py-2 rounded-lg shadow-md transition">
                ✏️ Editar
            </a>
            <!-- Modal de Confirmación -->
            <div x-data="{ open: false, url: '' }" x-cloak>
                <!-- Botón para abrir el modal -->
                <button x-on:click="open = true; url = '{{ route('admin.webs.destroy', $web) }}'" 
                        class="inline-flex items-center bg-red-500 hover:bg-red-600 text-white text-sm px-3 py-2 rounded-lg shadow-md transition">
                    🗑️ Eliminar
                </button>

                <!-- Overlay del modal -->
                <div x-show="open" class="fixed inset-0 bg-black bg-opacity-50 z-50" x-on:click="open = false"></div>

                <!-- Contenido del modal -->
                <div x-show="open" class="fixed inset-0 flex items-center justify-center z-50">
                    <div class="bg-white rounded-lg shadow-lg p-6 w-11/12 max-w-md">
                        <h3 class="text-xl font-bold text-gray-800 mb-4">¿Estás seguro?</h3>
                        <p class="text-gray-700 mb-6">Esta acción eliminará al usuario permanentemente. ¿Deseas continuar?</p>

                        <!-- Botones de acción -->
                        <div class="flex justify-end space-x-4">
                            <button x-on:click="open = false" 
                                    class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg shadow-md transition">
                                Cancelar
                            </button>
                            <form :action="url" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg shadow-md transition">
                                    Eliminar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!--FIN modal-->
        </div>
    </div>
</div>

@endsection