<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $pageTitle ?? config('app.name', 'Laravel') }}</title>
        
        <!-- Favicon -->
        <link rel="icon" type="image/png" href="{{ asset('img/logo.png') }}"

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {{-- Permite que las vistas añadan estilos adicionales con @push('styles') --}}
    @stack('styles')
    </head>
    <body class="font-sans antialiased bg-background text-secondary">
        <div class="min-h-screen bg-surface">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-secondary text-white border-b border-accent">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
        {{-- Permite que las vistas añadan scripts adicionales con @push('scripts') --}}
        @stack('scripts')
    </body>
</html>
