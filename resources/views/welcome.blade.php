@extends('layouts.app')

@section('title', 'Zablo - Crea tu web profesional')

@push('styles')
    <!--CSS específico de la página-->
    @vite(['resources/css/welcome.css'])
@endpush

@section('content')
<main class="container mx-auto px-6 py-12">

    <!-- Hero Section -->
    <div class="flex flex-col md:flex-row items-center justify-between gap-12">

        <!-- Texto de bienvenida -->
        <div class="md:w-1/2 text-center md:text-left">
            <h1 class="title-message">            
            {{ __('welcome.titular') }} <span class="text-blue-400">{{ __('welcome.digital') }}</span>
            </h1>
            <p class="welcome-message">
            {{ __('welcome.message') }}
            </p>
            <div class="flex flex-col sm:flex-row gap-4 mt-8">
                <a href="#cta" class="bg-blue-600 hover:bg-blue-700 transition-all text-white py-3 px-8 rounded-lg text-xl font-bold">
                {{ __('welcome.p1') }}
                </a>
                <a href="#features" class="bg-transparent border-2 border-white text-white hover:bg-white/10 transition-all py-3 px-8 rounded-lg text-xl">
                {{ __('welcome.p2') }}
                </a>
            </div>
        </div>

    <svg viewBox="0 0 400 400" xmlns="http://www.w3.org/2000/svg">
        <style>
          
      @keyframes rotate1 {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }
    @keyframes rotate2 {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(-360deg); }
    }
    @keyframes pulse {
      0%, 100% { opacity: 0.9; }
      50% { opacity: 0.7; }
    }
    @keyframes scale {
      0%, 100% { transform: scale(1); }
      50% { transform: scale(0.9); }
    }
      
      .circle1 {
        fill: none;
        stroke: #00BFFF;
        stroke-width: 8;
        animation: pulse 4s infinite ease-in-out, rotate1 15s infinite linear, scale 8s infinite ease-in-out;
        transform-origin: 170px 220px;
      }
      .circle2 {
        fill: none;
        stroke: #4169E1;
        stroke-width: 8;
        animation: pulse 5s infinite ease-in-out, rotate2 12s infinite linear, scale 7s infinite ease-in-out;
        transform-origin: 200px 160px;
      }
      .circle3 {
        fill: none;
        stroke: #FF1493;
        stroke-width: 8;
        animation: pulse 6s infinite ease-in-out, rotate1 10s infinite linear, scale 9s infinite ease-in-out;
        transform-origin: 230px 220px;
      }
      
      @keyframes moveToCenter1 {
        0% { transform: translate(0, 0); opacity: 1; }
        80% { opacity: 1; }
        100% { transform: translate(100px, 100px); opacity: 0; }
      }
      @keyframes moveToCenter2 {
        0% { transform: translate(0, 0); opacity: 1; }
        80% { opacity: 1; }
        100% { transform: translate(-100px, 100px); opacity: 0; }
      }
      @keyframes moveToCenter3 {
        0% { transform: translate(0, 0); opacity: 1; }
        80% { opacity: 1; }
        100% { transform: translate(-100px, -100px); opacity: 0; }
      }
      @keyframes moveToCenter4 {
        0% { transform: translate(0, 0); opacity: 1; }
        80% { opacity: 1; }
        100% { transform: translate(100px, -100px); opacity: 0; }
      }
      @keyframes moveToCenter5 {
        0% { transform: translate(0, 0); opacity: 1; }
        80% { opacity: 1; }
        100% { transform: translate(150px, 0); opacity: 0; }
      }
      @keyframes moveToCenter6 {
        0% { transform: translate(0, 0); opacity: 1; }
        80% { opacity: 1; }
        100% { transform: translate(-150px, 0); opacity: 0; }
      }
      @keyframes moveToCenter7 {
        0% { transform: translate(0, 0); opacity: 1; }
        80% { opacity: 1; }
        100% { transform: translate(0, 150px); opacity: 0; }
      }
      @keyframes moveToCenter8 {
        0% { transform: translate(0, 0); opacity: 1; }
        80% { opacity: 1; }
        100% { transform: translate(0, -150px); opacity: 0; }
      }
      
      .icon {
        animation-duration: 3s;
        animation-iteration-count: infinite;
        animation-timing-function: ease-in-out;
      }
    </style>
    
    <!-- Central Logo -->
    <g opacity="0.7">
        <circle class="circle1" cx="170" cy="220" r="100" />
        <circle class="circle2" cx="200" cy="160" r="100" />
        <circle class="circle3" cx="230" cy="220" r="100" />
    </g>
    
    <!-- Web Icons -->
    <g id="html-icon" class="icon" style="animation-name: moveToCenter1;">
      <g transform="translate(50, 50)">
        <polygon points="0,0 30,0 27,27 15,30 3,27" fill="#E44D26" />
        <polygon points="15,3 15,27.5 24.5,25 27,3" fill="#F16529" />
        <polygon points="10,8 20,8 19.5,12 10.5,12 11,16 19,16 18,22 15,23 12,22 11.5,19" fill="#FFFFFF" />
      </g>
    </g>
    
    <g id="css-icon" class="icon" style="animation-name: moveToCenter2; animation-delay: 0.4s;">
      <g transform="translate(50, 350)">
        <polygon points="0,0 30,0 27,27 15,30 3,27" fill="#1572B6" />
        <polygon points="15,3 15,27.5 24.5,25 27,3" fill="#33A9DC" />
        <polygon points="22,8 8,8 8.5,13 17,13 16.5,16 9,16 9.5,21 15,22 20.5,21 21,17" fill="#FFFFFF" />
      </g>
    </g>
    
    <g id="js-icon" class="icon" style="animation-name: moveToCenter3; animation-delay: 0.8s;">
      <g transform="translate(350, 350)">
        <rect x="0" y="0" width="30" height="30" fill="#F7DF1E" />
        <path d="M7.5,23.5 C8,25 9,26 11,26 C13,26 14,25 14,23.5 C14,21.5 12,21 10,20 C7,18.5 5,17 5,14 C5,10 8,8 11,8 C14,8 16,9.5 17,12 L14,14 C13.5,12.5 12.5,12 11,12 C9.5,12 8.5,13 8.5,14 C8.5,15.5 9.5,16 12,17 C15.5,18.5 17.5,20 17.5,23.5 C17.5,28 14,28 11,28 C7,28 5,26 4,23 Z" fill="#000000" />
        <path d="M20,23.5 L22.5,22 C23.5,24 24.5,25 26.5,25 C28,25 29,24.5 29,23.5 C29,22.5 28,22 26.5,21.5 L25.5,21 C23,20 21.5,19 21.5,16 C21.5,13.5 23.5,11.5 26.5,11.5 C28.5,11.5 30,12.5 31,14.5 L28.5,16 C28,14.5 27,14 26.5,14 C25.5,14 25,14.5 25,15.5 C25,16.5 25.5,17 27,17.5 L28,18 C31,19 32.5,20 32.5,23 C32.5,26 30,27.5 26.5,27.5 C23,27.5 21,26 20,23.5 Z" fill="#000000" />
      </g>
    </g>
    
    <g id="responsive-icon" class="icon" style="animation-name: moveToCenter4; animation-delay: 1.2s;">
      <g transform="translate(350, 50)">
        <rect x="0" y="5" width="25" height="15" rx="2" fill="#5A6378" />
        <rect x="3" y="8" width="19" height="9" fill="#B8C2CC" />
        <rect x="15" y="20" width="15" height="10" rx="1" fill="#5A6378" />
        <rect x="17" y="22" width="11" height="6" fill="#B8C2CC" />
      </g>
    </g>
    
    <g id="seo-icon" class="icon" style="animation-name: moveToCenter5; animation-delay: 1.6s;">
      <g transform="translate(20, 200)">
        <circle cx="13" cy="13" r="10" fill="none" stroke="#5A6378" stroke-width="2" />
        <line x1="20" y1="20" x2="28" y2="28" stroke="#5A6378" stroke-width="3" />
        <circle cx="13" cy="13" r="5" fill="#B8C2CC" />
      </g>
    </g>
    
    <g id="cloud-icon" class="icon" style="animation-name: moveToCenter6; animation-delay: 2.0s;">
      <g transform="translate(370, 200)">
        <path d="M25,15 C25,11.5 22,8.5 18.5,8.5 C16.5,3.5 10.5,1 6,4.5 C2.5,7.5 2,13 5,16.5 C2.5,18 2,22 4.5,24 C6.5,26 10,26 12,24 L23,24 C26.5,24 29,21 29,18 C29,16.5 28,15 25,15 Z" fill="#B8C2CC" stroke="#5A6378" stroke-width="1" />
      </g>
    </g>
    
    <g id="database-icon" class="icon" style="animation-name: moveToCenter7; animation-delay: 2.4s;">
      <g transform="translate(200, 20)">
        <ellipse cx="15" cy="7" rx="15" ry="7" fill="#B8C2CC" stroke="#5A6378" stroke-width="1" />
        <path d="M0,7 L0,23 C0,27 7,31 15,31 C23,31 30,27 30,23 L30,7" fill="none" stroke="#5A6378" stroke-width="1" />
        <ellipse cx="15" cy="23" rx="15" ry="7" fill="none" stroke="#5A6378" stroke-width="1" />
      </g>
    </g>
    
    <g id="mobile-icon" class="icon" style="animation-name: moveToCenter8; animation-delay: 2.8s;">
      <g transform="translate(200, 370)">
        <rect x="7" y="0" width="16" height="30" rx="3" fill="#B8C2CC" stroke="#5A6378" stroke-width="1" />
        <rect x="10" y="5" width="10" height="16" fill="#FFFFFF" />
        <circle cx="15" cy="25" r="2" fill="#FFFFFF" />
      </g>
    </g>
  </svg>

        <!-- Imagen animada
        <div class="md:w-1/2 flex justify-center">
            <img src="https://media.giphy.com/media/ZgTR3UQ9XAWDvqy9jv/giphy.gif" alt="Web creation animation" class="max-w-xs md:max-w-md">
        </div>-->
    </div>

    <!-- Carrusel de webs mejorado -->
    <section class="mt-24 mb-24">
        <h2 class="text-3xl font-semibold text-center text-white mb-8">SITIOS CREADOS CON ZABLO</h2>
        
        <div class="border-4 border-white rounded-lg p-6 overflow-hidden h-72 relative bg-black/30 backdrop-blur-sm shadow-[0_0_15px_rgba(255,255,255,0.5)]">
            <div id="web-carousel" class="flex flex-col animate-scroll">
                @foreach($webs as $web)
                    <a href="http://{{ $web->url }}" target="_blank" 
                    class="text-white hover:text-blue-300 transition-colors mb-6 text-center block w-full" 
                    style="text-shadow: 0 0 10px rgba(255,255,255,0.5);">
                        {{ $web->url }}
                    </a>
                @endforeach
                
                @foreach($webs as $web)
                    <a href="http://{{ $web->url }}" target="_blank" 
                    class="text-white hover:text-blue-300 transition-colors mb-6 text-center block w-full" 
                    style="text-shadow: 0 0 10px rgba(255,255,255,0.5);">
                        {{ $web->url }}
                    </a>
                @endforeach
            </div>
        </div>
    </section> 

    <!-- Características -->
    <section id="features" class="mb-24">
        <h2 class="text-4xl font-semibold text-center text-white mb-12">{{ __('welcome.why') }}
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-white/10 backdrop-blur-sm p-8 rounded-lg shadow-lg border border-white/20 hover:transform hover:scale-105 transition-all">
                <div class="bg-blue-600 text-white w-14 h-14 rounded-full flex items-center justify-center mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
                <h3 class="text-2xl font-semibold text-white mb-4">{{ __('welcome.c1') }}</h3>
                <p class="text-white/80">{{ __('welcome.c1a') }}</p>
            </div>
            <div class="bg-white/10 backdrop-blur-sm p-8 rounded-lg shadow-lg border border-white/20 hover:transform hover:scale-105 transition-all">
                <div class="bg-blue-600 text-white w-14 h-14 rounded-full flex items-center justify-center mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z" />
                    </svg>
                </div>
                <h3 class="text-2xl font-semibold text-white mb-4">{{ __('welcome.c2') }}</h3>
                <p class="text-white/80">{{ __('welcome.c2a') }}</p>
            </div>
            <div class="bg-white/10 backdrop-blur-sm p-8 rounded-lg shadow-lg border border-white/20 hover:transform hover:scale-105 transition-all">
                <div class="bg-blue-600 text-white w-14 h-14 rounded-full flex items-center justify-center mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <h3 class="text-2xl font-semibold text-white mb-4">{{ __('welcome.c3') }}</h3>
                <p class="text-white/80">{{ __('welcome.c3a') }}</p>
            </div>
        </div>
    </section>

    <!-- Sección de Testimonios con fotos más realistas -->
    <section class="mb-24">
        <h2 class="text-4xl font-semibold text-center text-white mb-12">{{ __('welcome.opiniones') }}</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-white/10 backdrop-blur-sm p-8 rounded-lg shadow-lg border border-white/20 relative hover:transform hover:scale-105 transition-all">
                <div class="absolute -top-6 -left-6">
                    <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="Carlos Mendoza" class="rounded-full w-20 h-20 object-cover border-4 border-blue-600">
                </div>
                <div class="pt-12">
                    <p class="text-white/90 italic mb-6">{{ __('welcome.op1') }}</p>
                    <h4 class="font-semibold text-white text-xl">Carlos Mendoza</h4>
                    <p class="text-blue-400">{{ __('welcome.op1a') }}</p>
                    <div class="flex mt-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-500" viewBox="0 0 20 20" fill="currentColor">
                          <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-500" viewBox="0 0 20 20" fill="currentColor">
                          <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-500" viewBox="0 0 20 20" fill="currentColor">
                          <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-500" viewBox="0 0 20 20" fill="currentColor">
                          <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-500" viewBox="0 0 20 20" fill="currentColor">
                          <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                    </div>
                </div>
            </div>
            <div class="bg-white/10 backdrop-blur-sm p-8 rounded-lg shadow-lg border border-white/20 relative hover:transform hover:scale-105 transition-all">
                <div class="absolute -top-6 -left-6">
                    <img src="https://randomuser.me/api/portraits/women/44.jpg" alt="Laura Jiménez" class="rounded-full w-20 h-20 object-cover border-4 border-blue-600">
                </div>
                <div class="pt-12">
                    <p class="text-white/90 italic mb-6">{{ __('welcome.op2') }}</p>
                    <h4 class="font-semibold text-white text-xl">Laura Jiménez</h4>
                    <p class="text-blue-400">{{ __('welcome.op2a') }}</p>
                    <div class="flex mt-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-500" viewBox="0 0 20 20" fill="currentColor">
                          <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-500" viewBox="0 0 20 20" fill="currentColor">
                          <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-500" viewBox="0 0 20 20" fill="currentColor">
                          <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-500" viewBox="0 0 20 20" fill="currentColor">
                          <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-500" viewBox="0 0 20 20" fill="currentColor">
                          <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                    </div>
                </div>
            </div>
            <div class="bg-white/10 backdrop-blur-sm p-8 rounded-lg shadow-lg border border-white/20 relative hover:transform hover:scale-105 transition-all">
                <div class="absolute -top-6 -left-6">
                    <img src="https://randomuser.me/api/portraits/men/65.jpg" alt="Andrés Salgado" class="rounded-full w-20 h-20 object-cover border-4 border-blue-600">
                </div>
                <div class="pt-12">
                    <p class="text-white/90 italic mb-6">{{ __('welcome.op3') }}</p>
                    <h4 class="font-semibold text-white text-xl">Andrés Salgado</h4>
                    <p class="text-blue-400">{{ __('welcome.op3a') }}</p>
                    <div class="flex mt-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-500" viewBox="0 0 20 20" fill="currentColor">
                          <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-500" viewBox="0 0 20 20" fill="currentColor">
                          <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-500" viewBox="0 0 20 20" fill="currentColor">
                          <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-500" viewBox="0 0 20 20" fill="currentColor">
                          <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-500" viewBox="0 0 20 20" fill="currentColor">
                          <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Llamada a la acción mejorada -->
    <section id="cta" class="mb-16 text-center py-16 bg-white/5 backdrop-blur-sm rounded-xl border border-white/10">
        <h2 class="text-4xl font-semibold text-white mb-6">{{ __('welcome.f1') }}</h2>
        <p class="text-white/80 text-xl max-w-2xl mx-auto mb-8">{{ __('welcome.f1a') }}</p>
        <div class="flex flex-col sm:flex-row justify-center gap-6">
            <a href="#register" class="bg-blue-600 hover:bg-blue-700 transition-all text-white py-4 px-10 rounded-lg text-xl font-bold">
            {{ __('welcome.f2') }}
            </a>
            <a href="#demo" class="bg-white hover:bg-gray-100 transition-all text-blue-700 py-4 px-10 rounded-lg text-xl font-bold">
            {{ __('welcome.f3') }}
            </a>
        </div>
    </section>
    --}}
</main>
@endsection
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const carousel = document.getElementById('web-carousel');
    let scrollSpeed = 1; // velocidad en píxeles
    let paused = false;

    carousel.addEventListener('mouseenter', () => paused = true);
    carousel.addEventListener('mouseleave', () => paused = false);

    function scroll() {
        if (!paused) {
            carousel.scrollTop += scrollSpeed;

            // Reiniciar scroll para bucle infinito
            if (carousel.scrollTop >= carousel.scrollHeight / 2) {
                carousel.scrollTop = 0;
            }
        }
        requestAnimationFrame(scroll);
    }

    // Inicializar
    scroll();
});
</script>
@endpush
