@extends('adminlte::page')

@section('title', 'Dashboard - ZabloAdmin')

@section('content_header')
    {{ Breadcrumbs::render('admin.dashboard') }}
    <h1 class="text-3xl font-bold text-gray-800">Dashboard de Administración</h1>
    <p class="text-gray-600 mt-2">Bienvenido al panel de administración. Desde aquí puedes gestionar los usuarios y las webs registradas en el sistema.</p>
@stop

@section('content')
<div class="mx-auto px-6">
    <!-- Contenedor de tarjetas en vertical -->
    <div class="space-y-6">
        <!-- Tarjeta para Usuarios -->
        <a href="{{ route('admin.users.index') }}" 
           class="block bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-200">
            <div class="flex items-center space-x-4">
                <!-- Icono de Usuarios -->
                <div class="bg-blue-500 p-4 rounded-full">
                    <i class="fas fa-users text-white text-2xl"></i>
                </div>
                <!-- Texto -->
                <div>
                    <h2 class="text-xl font-bold text-gray-800">Usuarios</h2>
                    <p class="text-gray-600 mt-2">
                        Gestiona los usuarios del sistema. Aquí puedes ver, crear, editar y eliminar usuarios, así como asignar roles y permisos.
                    </p>
                </div>
            </div>
        </a>

        <!-- Tarjeta para Webs -->
        <a href="{{ route('admin.webs.index') }}" 
           class="block bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-200">
            <div class="flex items-center space-x-4">
                <!-- Icono de Webs -->
                <div class="bg-green-500 p-4 rounded-full">
                    <i class="fas fa-globe text-white text-2xl"></i>
                </div>
                <!-- Texto -->
                <div>
                    <h2 class="text-xl font-bold text-gray-800">Webs</h2>
                    <p class="text-gray-600 mt-2">
                        Administra las webs registradas. Desde aquí puedes gestionar dominios, configuraciones y estadísticas de las webs.
                    </p>
                </div>
            </div>
        </a>
    </div>
</div>
@endsection