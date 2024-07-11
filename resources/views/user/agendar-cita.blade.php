<!-- resources/views/appointments.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agendar Cita</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-3xl">
        <h1 class="text-2xl font-bold mb-6">Agendar Cita</h1>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Calendario de Disponibilidad -->
            <div class="border rounded-lg p-4">
                <h2 class="text-lg font-semibold mb-4">Calendario de Disponibilidad</h2>
                <!-- Aquí se agregará el calendario posteriormente -->
            </div>
            <!-- Seleccionar Barbero y Servicio -->
            <div class="border rounded-lg p-4">
                <h2 class="text-lg font-semibold mb-4">Seleccionar Barbero y Servicio</h2>
                <div class="mb-4">
                    <label for="barbero" class="block text-sm font-medium text-gray-700">Barbero</label>
                    <select id="barbero" name="barbero" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                        <option>Seleccionar barbero</option>
                        <!-- Opciones de barberos -->
                    </select>
                </div>
                <div class="mb-4">
                    <label for="servicio" class="block text-sm font-medium text-gray-700">Servicio</label>
                    <select id="servicio" name="servicio" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                        <option>Seleccionar servicio</option>
                        <!-- Opciones de servicios -->
                    </select>
                </div>
                <div class="mb-4">
                    <label for="hora" class="block text-sm font-medium text-gray-700">Hora</label>
                    <input type="time" id="hora" name="hora" class="mt-1 block w-full pl-3 pr-3 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                </div>
                <p class="font-semibold mb-4">Costo estimado: <span>$20</span></p>
                <button class="w-full bg-black text-white py-2 rounded-md">Agendar Cita</button>
            </div>
        </div>
    </div>
</body>
</html>
