
<nav class="flex items-center justify-end gap-4">

<!-- Los dos siguientes botones se eliminaran antes del despliegue, dado que solo tienen que aparecer con la sesion iniciada -->
<a href="{{ route('home') }}"
                class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] text-[#1b1b18] border border-transparent hover:border-[#19140035] dark:hover:border-[#3E3E3A] rounded-sm text-sm leading-normal"
            >
                Inicio (Boton temporal)
            </a>
<a href="{{ route('diseno') }}"
                class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] text-[#1b1b18] border border-transparent hover:border-[#19140035] dark:hover:border-[#3E3E3A] rounded-sm text-sm leading-normal"
            >
                Diseño (Boton temporal)
            </a>
<a href="{{ route('perfil') }}"
                class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] text-[#1b1b18] border border-transparent hover:border-[#19140035] dark:hover:border-[#3E3E3A] rounded-sm text-sm leading-normal"
            >
                Perfil (Boton temporal)
            </a>
            <a
                href="{{ route('admin.dashboard') }}"
                class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] border-[#19140035] hover:border-[#1915014a] border text-[#1b1b18] dark:border-[#3E3E3A] dark:hover:border-[#62605b] rounded-sm text-sm leading-normal"
            >
                Dashboard (Boton temporal)
            </a>
<!--FIN botones a eliminar-->

        @if (Route::has('login'))
        @auth
            <!-- Botón de Logout -->
            <form method="POST" action="{{ route('logout') }}" class="inline">
                @csrf
                <button
                    type="submit"
                    class="inline-block px-5 py-1.5 bg-red-500 text-white border border-red-600 hover:bg-red-600 rounded-sm text-sm leading-normal"
                >
                    Cerrar sesión
                </button>
            </form>
        @else
            <a
                href="{{ route('login') }}"
                class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] text-[#1b1b18] border border-transparent hover:border-[#19140035] dark:hover:border-[#3E3E3A] rounded-sm text-sm leading-normal"
            >
                Log in
            </a>

            @if (Route::has('register'))
                <a
                    href="{{ route('register') }}"
                    class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] border-[#19140035] hover:border-[#1915014a] border text-[#1b1b18] dark:border-[#3E3E3A] dark:hover:border-[#62605b] rounded-sm text-sm leading-normal">
                    Register
                </a>
            @endif
        @endauth
@endif
</nav>

<p>Esto es el componente header y se encuentra en: resources/views/partials/header.blade.php</p>

