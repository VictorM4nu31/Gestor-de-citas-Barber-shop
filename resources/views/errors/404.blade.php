<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Página no encontrada - MasterCut</title>
    <link rel="icon" type="image/png" href="{{ asset('img/logo.png') }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=dm-sans:400,500,600,700|dm-serif-display:400&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-secondary font-sans text-light antialiased">
    <main class="mx-auto flex min-h-screen max-w-3xl flex-col items-center justify-center px-4 text-center sm:px-8">
        <p class="eyebrow text-brass">Error 404</p>
        <h1 class="display-title mt-4 text-6xl text-light sm:text-8xl">Página no encontrada</h1>
        <p class="mt-6 max-w-md text-lg text-light/70">La silla que buscas no está aquí. Es posible que el enlace haya cambiado o ya no exista.</p>
        <div class="mt-10 flex flex-wrap justify-center gap-3">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-3 bg-brass px-6 py-4 font-bold text-secondary transition hover:bg-light">Volver al inicio <span aria-hidden="true">→</span></a>
            <a href="{{ route('login') }}" class="inline-flex items-center border border-light/40 px-6 py-4 font-bold text-light transition hover:border-light">{{ __('common.navigation.login') }}</a>
        </div>
    </main>
</body>
</html>
