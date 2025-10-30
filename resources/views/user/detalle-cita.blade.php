<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Cita Programada</h2>
        </div>
    </x-slot>

    <main class="container mx-auto px-4 py-8">
        <div class="bg-light p-8 rounded-lg shadow-lg w-full max-w-3xl">
            <h1 class="text-2xl font-bold mb-6 text-secondary">Cita Programada</h1>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Detalles de la Cita -->
                <div class="border border-metal rounded-lg p-4">
                    <h2 class="text-lg font-semibold mb-4 text-secondary">Detalles de la Cita</h2>
                    <p><span class="font-semibold">Fecha:</span> 04/05/2023</p>
                    <p><span class="font-semibold">Hora:</span> 10:00 AM</p>
                    <p><span class="font-semibold">Barbero:</span> John Doe</p>
                    <p><span class="font-semibold">Servicio:</span> Corte de cabello - $20</p>
                    <p><span class="font-semibold">Costo:</span> $20</p>
                </div>
                <!-- Acciones -->
                <div class="border border-metal rounded-lg p-4 text-center">
                    <h2 class="text-lg font-semibold mb-4 text-secondary">Acciones</h2>
                    <button class="w-full bg-light text-secondary border border-metal py-2 rounded-md mb-4">Reagendar Cita</button>
                    <button class="w-full bg-danger hover:bg-secondary text-light py-2 rounded-md">Cancelar Cita</button>
                </div>
            </div>
        </div>
    </main>
</x-app-layout>
