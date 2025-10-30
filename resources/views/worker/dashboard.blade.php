<x-app-layout>
    <x-slot name="header">
        <h1 class="text-2xl font-semibold">Panel de Control</h1>
    </x-slot>

    <div id="view" class="h-full w-screen flex flex-row">
        <div class="bg-surface flex-grow text-secondary p-6">
            <!-- Contenido del panel de control -->
            @include('worker.appointment-manager')
        </div>
    </div>

</x-app-layout>
