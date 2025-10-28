<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center flex-wrap">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Bienvenido') }}
            </h2>
            <div class="flex space-x-4 mt-2 md:mt-0">
                <button id="show-agendar-cita" class="bg-black text-white py-2 px-4 rounded-md">Agendar Cita</button>
                <button id="show-detalle-cita" class="border-2 border-black text-black py-2 px-4 rounded-md">Ver Citas Programadas</button>
            </div>
        </div>
    </x-slot>

    <div class="relative min-h-screen bg-cover bg-center" style="background-image: url('/img/imagen de fondo.jpg');">
        <div class="py-12 flex justify-center">
            <div id="agendar-cita" class="max-w-7xl mx-auto sm:px-6 lg:px-8 bg-white bg-opacity-90 p-6 rounded-lg shadow-md hidden w-full md:w-auto">
                @include('user.agendar-cita')
            </div>
            <div id="detalle-cita" class="max-w-7xl mx-auto sm:px-6 lg:px-8 bg-white bg-opacity-90 p-6 rounded-lg shadow-md hidden w-full md:w-auto">
                @include('user.detalle-cita')
            </div>
        </div>
    </div>

    <script>
        document.getElementById('show-agendar-cita').addEventListener('click', function() {
            document.getElementById('agendar-cita').classList.remove('hidden');
            document.getElementById('detalle-cita').classList.add('hidden');
        });

        document.getElementById('show-detalle-cita').addEventListener('click', function() {
            document.getElementById('detalle-cita').classList.remove('hidden');
            document.getElementById('agendar-cita').classList.add('hidden');
        });
    </script>
</x-app-layout>
