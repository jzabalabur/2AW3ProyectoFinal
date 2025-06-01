@extends('adminlte::page')

@section('title', 'Editar Usuario - ZabloAdmin')

@section('content_header')
    {{ Breadcrumbs::render('admin.usuarios.edit', $user) }}
@stop

@section('content')
<div class="mx-auto px-6">
    <h2 class="text-3xl font-bold text-gray-800 mb-6">{{ __('dashboard.editar') }}</h2>

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
                ← {{ __('dashboard.volver') }}
            </a>
        </div>

        <!-- Formulario de edición -->
        <form action="{{ route('admin.users.update', $user) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="space-y-4">
                <!-- Campo: Nombre -->
                <div class="border-b border-gray-200 pb-4">
                    <label for="name" class="text-lg font-semibold text-gray-700">{{ __('dashboard.nombre') }}</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" 
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Campo: Email -->
                <div class="border-b border-gray-200 pb-4">
                    <label for="email" class="text-lg font-semibold text-gray-700">{{ __('dashboard.email') }}</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" 
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Campo: Contraseña (opcional) -->
                <div class="border-b border-gray-200 pb-4">
                    <label for="password" class="text-lg font-semibold text-gray-700">{{ __('dashboard.pass') }}</label>
                    <input type="password" name="password" id="password" 
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    @error('password')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Campo: Confirmar Contraseña -->
                <div class="border-b border-gray-200 pb-4">
                    <label for="password_confirmation" class="text-lg font-semibold text-gray-700">{{ __('dashboard.pass_confirm') }}</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" 
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
            </div>

            <!-- Botones de acciones -->
            <div class="mt-6 flex space-x-4">
                <button type="submit" 
                        class="inline-flex items-center bg-green-500 hover:bg-green-600 text-white text-sm px-3 py-2 rounded-lg shadow-md transition">
                    💾 {{ __('dashboard.guardar') }}
                </button>
                <a href="{{ route('admin.users.show', $user) }}" 
                   class="inline-flex items-center bg-gray-500 hover:bg-gray-600 text-white text-sm px-3 py-2 rounded-lg shadow-md transition">
                    🚫 {{ __('dashboard.cancelar') }}
                </a>
            </div>
        </form>
    </div>
</div>
@endsection