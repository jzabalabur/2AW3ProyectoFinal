@extends('adminlte::page')

@section('title', 'Detalles del Usuario - ZabloAdmin')

@section('content_header')
    {{ Breadcrumbs::render('usuarios.show', $user) }}
@stop

@section('content')

<div class="mx-auto px-6">
    <h2 class="text-3xl font-bold text-gray-800 mb-6">Detalles del Usuario</h2>
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
            <a href="{{ route('admin.users.index') }}" 
               class="inline-flex items-center bg-gray-500 hover:bg-gray-600 text-white text-sm px-3 py-2 rounded-lg shadow-md transition">
                ← Volver a la lista
            </a>
        </div>

        <!-- Información del usuario -->
        <div class="space-y-4">
            <div class="border-b border-gray-200 pb-4">
                <h3 class="text-lg font-semibold text-gray-700">ID</h3>
                <p class="text-gray-900">{{ $user->id }}</p>
            </div>

            <div class="border-b border-gray-200 pb-4">
                <h3 class="text-lg font-semibold text-gray-700">Nombre</h3>
                <p class="text-gray-900">{{ $user->name }}</p>
            </div>

            <div class="border-b border-gray-200 pb-4">
                <h3 class="text-lg font-semibold text-gray-700">Email</h3>
                <p class="text-gray-900">{{ $user->email }}</p>
            </div>

            <div class="border-b border-gray-200 pb-4">
                <h3 class="text-lg font-semibold text-gray-700">Fecha de Creación</h3>
                <p class="text-gray-900">{{ $user->created_at->format('d/m/Y H:i:s') }}</p>
            </div>

            <div class="border-b border-gray-200 pb-4">
                <h3 class="text-lg font-semibold text-gray-700">Última Actualización</h3>
                <p class="text-gray-900">{{ $user->updated_at->format('d/m/Y H:i:s') }}</p>
            </div>
        </div>

        <!-- Botones de acciones -->
        @if(auth()->user()->hasRole('administrador'))
        
        <div class="mt-6 flex space-x-4">
            <a href="{{ route('admin.users.edit', $user) }}" 
               class="inline-flex items-center bg-blue-500 hover:bg-blue-600 text-white text-sm px-3 py-2 rounded-lg shadow-md transition">
                ✏️ Editar
            </a>
            <!-- Modal de Confirmación -->
            <div x-data="{ open: false, url: '' }" x-cloak>
                <!-- Botón para abrir el modal -->
                <button x-on:click="open = true; url = '{{ route('admin.users.destroy', $user) }}'" 
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
            @endif
            <!--FIN modal-->
        </div>
    </div>
</div>

@endsection