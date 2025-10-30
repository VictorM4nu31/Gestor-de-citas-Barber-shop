<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ver Servicio</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-surface">
    <header class="bg-secondary text-light">
        <div class="container mx-auto px-4 py-4 flex justify-between items-center">
            <a href="/" class="text-xl font-bold text-light">Barbería</a>
            <nav class="space-x-4">
                <a href="{{ route('servicios.index') }}" class="bg-primary hover:bg-secondary text-light py-2 px-4 rounded">Volver a la Lista</a>
            </nav>
        </div>
    </header>

    <main class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-semibold mb-6 text-secondary">Ver Servicio</h1>
        <div class="bg-light border border-metal rounded-lg shadow-md p-6">
            <div class="mb-4">
                <strong class="text-secondary">Nombre:</strong>
                <p class="text-muted">{{ $servicio->nombre }}</p>
            </div>
            <div class="mb-4">
                <strong class="text-secondary">Descripción:</strong>
                <p class="text-muted">{{ $servicio->descripcion }}</p>
            </div>
            <div class="mb-4">
                <strong class="text-secondary">Duración:</strong>
                <p class="text-muted">{{ $servicio->duracion }} minutos</p>
            </div>
            <div class="mb-4">
                <strong class="text-secondary">Precio:</strong>
                <p class="text-muted">${{ $servicio->precio }}</p>
            </div>
            @if ($servicio->foto)
                <div class="mt-2">
                    <img src="{{ asset('storage/' . $servicio->foto) }}" alt="Foto de {{ $servicio->nombre }}" class="w-32 h-32 object-cover rounded-md border border-metal">
                </div>
            @endif
        </div>
    </main>
</body>
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Detalle del Servicio</h2>
    </x-slot>

    <main class="container mx-auto px-4 py-8">
        <div class="bg-surface p-6 rounded shadow">
            <h1 class="text-2xl font-semibold mb-4">{{ $servicio->nombre }}</h1>
            <p class="mb-2">{{ $servicio->descripcion }}</p>
            <p class="mb-2">Duración: {{ $servicio->duracion }} minutos</p>
            <p class="mb-2">Precio: ${{ $servicio->precio }}</p>
            @if ($servicio->foto)
                <img src="{{ asset('storage/' . $servicio->foto) }}" alt="Foto de {{ $servicio->nombre }}" class="w-64 h-64 object-cover rounded">
            @endif
        </div>
    </main>
</x-app-layout>

