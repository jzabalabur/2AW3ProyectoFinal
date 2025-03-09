@extends('adminlte::page')

@section('title', 'Crear Usuario')

@section('content_header')
    {{ Breadcrumbs::render('usuarios.create') }}
@stop

@section('content')
<div class="mx-auto px-6">
    <h2 class="text-3xl font-bold text-gray-800 mb-6">Crear Nuevo Usuario</h2>

    @if (session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
        {{ session('error') }}
    </div>
    @endif
    <div class="bg-white p-6 rounded-lg shadow-md">
        <!-- Formulario de creación -->
        <form action="{{ route('admin.users.store') }}" method="POST">
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

            <!-- Campo: Email -->
            <div class="mb-4">
                <label for="email" class="block text-gray-700">Email</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" 
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Campo: Contraseña -->
            <div class="mb-4">
                <label for="password" class="block text-gray-700">Contraseña</label>
                <input type="password" name="password" id="password" 
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                @error('password')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Campo: Confirmar Contraseña -->
            <div class="mb-4">
                <label for="password_confirmation" class="block text-gray-700">Confirmar Contraseña</label>
                <input type="password" name="password_confirmation" id="password_confirmation" 
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>

            <!-- Botones de acciones -->
            <div class="mt-6 flex space-x-4">
                <button type="submit" 
                        class="inline-flex items-center bg-green-500 hover:bg-green-600 text-white text-sm px-3 py-2 rounded-lg shadow-md transition">
                    💾 Guardar
                </button>
                <a href="{{ route('admin.users.index') }}" 
                   class="inline-flex items-center bg-gray-500 hover:bg-gray-600 text-white text-sm px-3 py-2 rounded-lg shadow-md transition">
                    🚫 Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
@endsection