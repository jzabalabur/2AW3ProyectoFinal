@extends('adminlte::page')

@section('title', 'Lista de Usuarios - ZabloAdmin')

@section('content_header')
    {{ Breadcrumbs::render('admin.usuarios') }}
@stop

@section('content')

<div class="mx-auto px-6">
    <h2 class="text-3xl font-bold text-gray-800 mb-6">{{ __('dashboard.usuarios_titu') }}</h2>
    
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
                        <th class="py-3 px-4 text-left">{{ __('dashboard.email') }}</th>
                        <th class="py-3 px-4 text-left">{{ __('dashboard.creado') }}</th>
                        <th class="py-3 px-4 text-right">
                            <a href="{{ route('admin.users.create') }}" 
                            class="inline-flex items-center bg-green-500 hover:bg-green-600 text-white text-sm px-3 py-2 rounded-lg shadow-md transition">
                                ➕ {{ __('dashboard.crear_registro') }}
                            </a>
                        </th>
                    </tr>
                </thead>
                <tbody class="text-gray-700">
                    @foreach ($users as $user)
                    <tr class="border-b border-gray-300 hover:bg-gray-100 transition duration-200">
                        <td class="py-3 px-4">{{ $user->id }}</td>
                        <td class="py-3 px-4">{{ $user->name }}</td>
                        <td class="py-3 px-4">{{ $user->email }}</td>
                        <td class="py-3 px-4">{{ $user->created_at->format('d/m/Y') }}</td>
                        <td class="py-3 px-4 flex justify-end space-x-2">
                            <a href="{{ route('admin.users.show', $user) }}" 
                                class="flex items-center bg-green-500 hover:bg-green-600 text-white text-sm px-3 py-2 rounded-lg shadow-md transition">
                                    👁️ {{ __('dashboard.ver') }}
                            </a>
                            
                            <a href="{{ route('admin.users.edit', $user) }}" 
                               class="flex items-center bg-blue-500 hover:bg-blue-600 text-white text-sm px-3 py-2 rounded-lg shadow-md transition">
                                ✏️ {{ __('dashboard.editar') }}
                            </a>
                            
                            <!-- Modal de Confirmación con Alpine.js y fallback -->
                            <div x-data="{ open: false, url: '' }">
                                <!-- Botón para abrir el modal -->
                                <button x-on:click="open = true; url = '{{ route('admin.users.destroy', $user) }}'" 
                                        data-modal-trigger
                                        data-modal-target="modal-{{ $user->id }}"
                                        data-action-url="{{ route('admin.users.destroy', $user) }}"
                                        class="inline-flex items-center bg-red-500 hover:bg-red-600 text-white text-sm px-3 py-2 rounded-lg shadow-md transition">
                                    🗑️ {{ __('dashboard.eliminar') }}
                                </button>

                                <!-- Modal con Alpine.js -->
                                <div x-show="open" 
                                     x-cloak
                                     class="fixed inset-0 bg-black bg-opacity-50 z-50 modal-overlay" 
                                     x-on:click="open = false">
                                    <div class="fixed inset-0 flex items-center justify-center z-50">
                                        <div class="bg-white rounded-lg shadow-lg p-6 w-11/12 max-w-md" x-on:click.stop>
                                            <h3 class="text-xl font-bold text-gray-800 mb-4">{{ __('dashboard.seguro') }}</h3>
                                            <p class="text-gray-700 mb-6">{{ __('dashboard.seguro_mensaje') }}</p>

                                            <div class="flex justify-end space-x-4">
                                                <button x-on:click="open = false" 
                                                        class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg shadow-md transition modal-cancel">
                                                    {{ __('dashboard.cancelar') }}
                                                </button>
                                                <form :action="url" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg shadow-md transition">
                                                        {{ __('dashboard.confirmar') }}
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal fallback sin Alpine.js (oculto por defecto) -->
                            <div id="modal-{{ $user->id }}" class="modal fixed inset-0 bg-black bg-opacity-50 z-50 modal-overlay" style="display: none;">
                                <div class="fixed inset-0 flex items-center justify-center z-50">
                                    <div class="bg-white rounded-lg shadow-lg p-6 w-11/12 max-w-md">
                                        <h3 class="text-xl font-bold text-gray-800 mb-4">{{ __('dashboard.seguro') }}</h3>
                                        <p class="text-gray-700 mb-6">{{ __('dashboard.seguro_mensaje') }}</p>

                                        <div class="flex justify-end space-x-4">
                                            <button class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg shadow-md transition modal-cancel">
                                                {{ __('dashboard.cancelar') }}
                                            </button>
                                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg shadow-md transition">
                                                    {{ __('dashboard.confirmar') }}
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

@push('css')
<style>
    [x-cloak] { display: none !important; }
</style>
@endpush

@push('js')
<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<script>
    // Fallback con JavaScript vanilla si Alpine.js no funciona
    document.addEventListener('DOMContentLoaded', function() {
        // Verificar si Alpine.js está cargado
        setTimeout(function() {
            if (typeof Alpine === 'undefined') {
                console.log('Alpine.js no detectado, usando JavaScript vanilla');
                // Implementar funcionalidad del modal sin Alpine.js
                initVanillaModals();
            }
        }, 100);
    });

    function initVanillaModals() {
        const deleteButtons = document.querySelectorAll('[data-modal-trigger]');
        
        deleteButtons.forEach(button => {
            button.addEventListener('click', function() {
                const modalId = this.getAttribute('data-modal-target');
                const modal = document.getElementById(modalId);
                const form = modal.querySelector('form');
                const actionUrl = this.getAttribute('data-action-url');
                
                if (form && actionUrl) {
                    form.setAttribute('action', actionUrl);
                }
                
                modal.style.display = 'flex';
                modal.classList.add('opacity-100');
            });
        });

        // Cerrar modales
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('modal-overlay') || e.target.classList.contains('modal-cancel')) {
                const modal = e.target.closest('.modal') || document.querySelector('.modal[style*="flex"]');
                if (modal) {
                    modal.style.display = 'none';
                    modal.classList.remove('opacity-100');
                }
            }
        });
    }
</script>
@endpush

@endsection