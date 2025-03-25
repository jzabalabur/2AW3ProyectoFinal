<header class="bg-[#F0F0F0] py-4 sticky top-0 z-50">
    <nav class="container mx-auto flex items-center justify-between px-6">
        <!-- Logo a la izquierda -->
        <div class="flex items-center">
            <a href="{{ route('home') }}" class="flex items-center">
                <img src="{{ asset('images/logo_sinFondo.png') }}" alt="Zablo" class="w-50 h-16"> <!-- Tamaño fijo -->
            </a>
        </div>

        <!-- Botones a la derecha (visible en desktop) -->
        <div class="hidden md:flex items-center gap-4">
            <!-- Botones -->
            <a href="{{ route('diseno') }}"
               class="inline-block px-5 py-1.5 text-gray-700 border border-transparent hover:border-gray-300 rounded-sm text-sm leading-normal transition-colors">
               {{ __('general.disenyo') }}
            </a>
            <a href="{{ route('perfil') }}"
               class="inline-block px-5 py-1.5 text-gray-700 border border-transparent hover:border-gray-300 rounded-sm text-sm leading-normal transition-colors">
               {{ __('general.perfil') }}
            </a>



            <!-- Botones de autenticación -->
            @if (Route::has('login'))
                @auth
                <!--FALTA IMPLEMENTAR CAN PARA QUE ESTE BOTON SOLO SEA VISIBLE AL LOGUEARSE CON UN ADMIN-->
                <a href="{{ route('admin.dashboard') }}"
                    class="inline-block px-5 py-1.5 text-gray-700 border border-transparent hover:border-gray-300 rounded-sm text-sm leading-normal transition-colors">
                    Dashboard
                </a>

                    <!-- Botón de Logout -->
                     
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit"
                                class="inline-block px-5 py-1.5 bg-purple-500 text-white border border-purple-600 hover:bg-purple-600 rounded-sm text-sm leading-normal transition-colors">
                                {{ __('general.logout') }}
                        </button>
                    </form>
                @else
                    <!-- Botón de Log in -->
                    <a href="{{ route('login') }}"
                       class="inline-block px-5 py-1.5 text-gray-700 border border-transparent hover:border-gray-300 rounded-sm text-sm leading-normal transition-colors">
                       {{ __('general.login') }}
                    </a>

                    <!-- Botón de Register -->
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}"
                           class="inline-block px-5 py-1.5 text-gray-700 border border-transparent hover:border-gray-300 rounded-sm text-sm leading-normal transition-colors">
                           {{ __('general.registro') }}
                        </a>
                    @endif
                @endauth
            @endif
                    <!-- Botón Euskera (activo si locale es 'eu') -->
        <a href="?lang=eu" class="{{ app()->getLocale() === 'eu' ? 'active' : '' }}">
            EU
        </a>
        <p> | </p>
        <!-- Botón Español (activo si locale es 'es') -->
        <a href="?lang=es" class="{{ app()->getLocale() === 'es' ? 'active' : '' }}">
            ES
        </a>
        </div>
       
</div>

        <!-- Ícono de hamburguesa (visible en móvil y tablet) -->
        <div class="md:hidden">
            <button id="menu-toggle" class="text-gray-700 focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
                </svg>
            </button>
        </div>
    </nav>

    <!-- Menú desplegable (visible en móvil y tablet) -->
    <div id="mobile-menu" class="hidden md:hidden bg-[#F0F0F0] px-6 py-4">
        
        <a href="{{ route('diseno') }}"
           class="block px-5 py-1.5 text-gray-700 border border-transparent hover:border-gray-300 rounded-sm text-sm leading-normal transition-colors mb-2">
           {{ __('general.disenyo') }}
        </a>
        <a href="{{ route('perfil') }}"
           class="block px-5 py-1.5 text-gray-700 border border-transparent hover:border-gray-300 rounded-sm text-sm leading-normal transition-colors mb-2">
           {{ __('general.perfil') }}
        </a>


        @if (Route::has('login'))
            @auth
            <!--FALTA IMPLEMENTAR CAN PARA QUE ESTE BOTON SOLO SEA VISIBLE AL LOGUEARSE CON UN ADMIN-->
            <a href="{{ route('admin.dashboard') }}"
                class="block px-5 py-1.5 text-gray-700 border border-transparent hover:border-gray-300 rounded-sm text-sm leading-normal transition-colors mb-2">
                Dashboard
            </a>

                <!-- Botón de Logout -->
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit"
                            class="block w-full px-5 py-1.5 bg-purple-500 text-white border border-purple-600 hover:bg-purple-600 rounded-sm text-sm leading-normal transition-colors">
                            {{ __('general.logout') }}
                    </button>
                </form>
            @else
                <!-- Botón de Log in -->
                <a href="{{ route('login') }}"
                   class="block px-5 py-1.5 text-gray-700 border border-transparent hover:border-gray-300 rounded-sm text-sm leading-normal transition-colors mb-2">
                   {{ __('general.login') }}
                </a>

                <!-- Botón de Register -->
                @if (Route::has('register'))
                    <a href="{{ route('register') }}"
                       class="block px-5 py-1.5 text-gray-700 border border-transparent hover:border-gray-300 rounded-sm text-sm leading-normal transition-colors mb-2">
                       {{ __('general.registro') }}
                    </a>
                @endif
            @endauth
        @endif
        <!-- Botón Euskera (activo si locale es 'eu') -->
        <a href="?lang=eu" class="{{ app()->getLocale() === 'eu' ? 'active' : '' }}">
            EU
        </a>
        <p> | </p>
        <!-- Botón Español (activo si locale es 'es') -->
        <a href="?lang=es" class="{{ app()->getLocale() === 'es' ? 'active' : '' }}">
            ES
        </a>
    </div>

</header>

<!-- Script para el menú desplegable -->
<script>
    document.getElementById('menu-toggle').addEventListener('click', function() {
        const mobileMenu = document.getElementById('mobile-menu');
        mobileMenu.classList.toggle('hidden');
    });
</script>