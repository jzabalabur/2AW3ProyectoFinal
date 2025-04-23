@extends('layouts.app')

@section('content')
<main class="container mx-auto px-6 py-8">

    <!-- Nombre del Usuario -->
    <h1 class="text-3xl font-bold text-gray-800 mb-6">
        {{ $user->name }}, Id: 
        {{ $user->id}}
    </h1>

    <!-- Lista de Webs Creadas -->
    <section>
        <h2 class="text-2xl font-semibold text-gray-700 mb-4">Tus páginas web</h2>

        <div class="space-y-4">
            @forelse ($webs as $web)
                <div class="flex items-center bg-white shadow-md rounded-md p-4">
                    <!-- Imagen del sitio web -->
                    <div class="w-32 h-20 bg-gray-200 rounded mr-4 flex-shrink-0 flex items-center justify-center text-sm text-gray-500">
                        Imagen no disponible
                    </div>

                    <!-- Info de la web -->
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-gray-800">{{ $web->name }}</h3>
                        <p class="text-sm text-gray-500">URL: {{ $web->url ?? 'Sin URL' }}</p>

                    </div>

                    <!-- Botones -->
                    <div class="flex gap-2">
                        <a href="{{ route('webs.edit', $web->id) }}"
                           class="px-4 py-2 text-sm text-white bg-blue-500 hover:bg-blue-600 rounded">Editar</a>

                        <form action="{{ route('webs.destroy', $web->id) }}" method="POST" onsubmit="return confirm('¿Eliminar esta web?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-4 py-2 text-sm text-white bg-red-500 hover:bg-red-600 rounded">Eliminar</button>
                        </form>
                    </div>
                </div>
            @empty
                <p class="text-gray-500">Todavía no has creado ninguna web.</p>
            @endforelse
        </div>
    </section>

</main>
@endsection
