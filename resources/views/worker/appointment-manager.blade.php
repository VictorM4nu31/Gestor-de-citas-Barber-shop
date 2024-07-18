<!-- resources/views/components/appointment-manager.blade.php -->

<div class="bg-white p-4 sm:p-6 md:p-8 rounded-lg shadow-lg w-full max-w-3xl mx-auto">
    <h1 class="text-2xl sm:text-3xl font-bold mb-4 sm:mb-6 text-black">Gestión de Citas</h1>

    <div class="space-y-4">
        <!-- Lista de Citas -->
        <div class="border rounded-lg p-4">
            <h2 class="text-xl font-semibold mb-4 text-black">Citas Programadas</h2>
            <ul class="space-y-4">
                <li class="border-b pb-2">
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center">
                        <div>
                            <p class="font-semibold">04/05/2023 - 10:00 AM</p>
                            <p>Barbero: John Doe</p>
                            <p>Servicio: Corte de cabello</p>
                        </div>
                        <div class="mt-2 sm:mt-0">
                            <button class="bg-black text-white px-3 py-1 rounded-md text-sm mr-2">Aceptar</button>
                            <button class="bg-red-500 text-white px-3 py-1 rounded-md text-sm">Cancelar</button>
                        </div>
                    </div>
                </li>
                <li class="border-b pb-2">
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center">
                        <div>
                            <p class="font-semibold">05/05/2023 - 2:00 PM</p>
                            <p>Barbero: Jane Smith</p>
                            <p>Servicio: Afeitado</p>
                        </div>
                        <div class="mt-2 sm:mt-0">
                            <button class="bg-black text-white px-3 py-1 rounded-md text-sm mr-2">Aceptar</button>
                            <button class="bg-red-500 text-white px-3 py-1 rounded-md text-sm">Cancelar</button>
                        </div>
                    </div>
                </li>
                <!-- Más citas aquí -->
            </ul>
        </div>
    </div>
</div>
