@extends('adminlte::page')

@section('title', __('page_titles.webs'))

@section('content_header')
    <h1>{{ __('dashboard.webs') }}</h1>
    {{ Breadcrumbs::render('webs') }}
@stop

@section('content')
<div class="mx-auto px-6">
    <h2 class="text-3xl font-bold text-gray-800 mb-6">{{ __('dashboard.webs_titu') }}</h2>
    
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

    <div class="bg-white p-4 rounded-lg shadow-md">
        <div class="overflow-x-auto">
            <table class="w-full border-collapse border border-gray-300">
                <thead class="bg-gray-800 text-white">
                    <tr>
                        <th class="py-3 px-4 text-left">ID</th>
                        <th class="py-3 px-4 text-left">{{ __('dashboard.nombre') }}</th>
                        <th class="py-3 px-4 text-left">URL</th>
                        <th class="py-3 px-4 text-left">{{ __('dashboard.creado') }}</th>
                        <th class="py-3 px-4 text-right">
                        <!--    
                        <a href="{{ route('admin.webs.create') }}" 
                            class="inline-flex items-center bg-green-500 hover:bg-green-600 text-white text-sm px-3 py-2 rounded-lg shadow-md transition">
                            ➕ {{ __('dashboard.crear_registro') }}
                        </a>
                        
                        </th>
                    </tr>
                </thead>
                <tbody class="text-gray-700">
                    @foreach ($webs as $web)
                    <tr class="border-b border-gray-300 hover:bg-gray-100 transition duration-200">
                        <td class="py-3 px-4">{{ $web->id }}</td>
                        <td class="py-3 px-4">{{ $web->name }}</td>
                        <td class="py-3 px-4">{{ $web->url }}</td>
                        <td class="py-3 px-4">{{ $web->created_at->format('d/m/Y') }}</td>
                        <td class="py-3 px-4 flex justify-end space-x-2">
                            <a href="{{ route('admin.webs.show', $web) }}" 
                                class="flex items-center bg-green-500 hover:bg-green-600 text-white text-sm px-3 py-2 rounded-lg shadow-md transition">
                                    👁️ {{ __('dashboard.ver') }}
                            </a>
                            <a href="{{ route('admin.webs.edit', $web) }}" 
                               class="flex items-center bg-blue-500 hover:bg-blue-600 text-white text-sm px-3 py-2 rounded-lg shadow-md transition">
                                ✏️ {{ __('dashboard.editar') }}
                            </a>
                            <a href="{{ route('admin.webs.pages.index', $web) }}" 
                                class="flex items-center bg-green-500 hover:bg-green-600 text-white text-sm px-3 py-2 rounded-lg shadow-md transition">
                                    📄 Ver Paginas
                            </a>
                                        <!-- Modal de Confirmación -->
                                        <div x-data="{ open: false, url: '' }" x-cloak>
                                            <!-- Botón para abrir el modal -->
                                            <button x-on:click="open = true; url = '{{ route('admin.webs.destroy', $web) }}'" 
                                                    class="inline-flex items-center bg-red-500 hover:bg-red-600 text-white text-sm px-3 py-2 rounded-lg shadow-md transition">
                                                🗑️ {{ __('dashboard.eliminar') }}
                                            </button>

                                            <!-- Overlay del modal -->
                                            <div x-show="open" class="fixed inset-0 bg-black bg-opacity-50 z-50" x-on:click="open = false"></div>

                                            <!-- Contenido del modal -->
                                            <div x-show="open" class="fixed inset-0 flex items-center justify-center z-50">
                                                <div class="bg-white rounded-lg shadow-lg p-6 w-11/12 max-w-md">
                                                    <h3 class="text-xl font-bold text-gray-800 mb-4">{{ __('dashboard.seguro') }}</h3>
                                                    <p class="text-gray-700 mb-6">{{ __('dashboard.seguro_mensaje') }}</p>

                                                    <!-- Botones de acción -->
                                                    <div class="flex justify-end space-x-4">
                                                        <button x-on:click="open = false" 
                                                                class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg shadow-md transition">
                                                                {{ __('dashboard.cancelar') }}
                                                        </button>
                                                        <form :action="url" method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" 
                                                                    class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg shadow-md transition">
                                                                    {{ __('dashboard.eliminar') }}
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!--FIN modal-->
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@stop