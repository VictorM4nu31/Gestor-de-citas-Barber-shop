<!-- resources/views/appointments.blade.php -->

<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight mb-4 sm:mb-0">
                {{ __('Bienvenido') }}
            </h2>
            <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-4">
                <button class="bg-black text-white py-2 px-4 rounded-md w-full sm:w-auto">Agendar Cita</button>
                <button class="bg-white text-black py-2 px-4 rounded-md border border-gray-300 w-full sm:w-auto">Ver Citas Programadas</button>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @include('user.agendar-cita')
        </div>
    </div>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @include('user.detalle-cita')
        </div>
    </div>
</x-app-layout>
