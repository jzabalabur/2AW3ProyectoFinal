<footer class="bg-black py-8 foot">
    <div class="container mx-auto px-6 text-center">
        <!-- Logo -->
        <div class="flex justify-center">
            <a href="{{ route('home') }}" class="flex items-center">
                <img src="{{ asset('images/logo_pequeño.png') }}" alt="Zablo" class="w-22 h-16"> <!-- Tamaño fijo -->
            </a>
        </div>

        <!-- Texto debajo del logo -->
        <p class="mt-4 text-gray-400 text-sm">
        {{ __('general.texto_footer') }}        
    </p>
    </div>
</footer>