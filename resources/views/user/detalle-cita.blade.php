<!-- resources/views/appointment-details.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cita Programada</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-3xl">
        <h1 class="text-2xl font-bold mb-6">Cita Programada</h1>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Detalles de la Cita -->
            <div class="border rounded-lg p-4">
                <h2 class="text-lg font-semibold mb-4">Detalles de la Cita</h2>
                <p><span class="font-semibold">Fecha:</span> 04/05/2023</p>
                <p><span class="font-semibold">Hora:</span> 10:00 AM</p>
                <p><span class="font-semibold">Barbero:</span> John Doe</p>
                <p><span class="font-semibold">Servicio:</span> Corte de cabello - $20</p>
                <p><span class="font-semibold">Costo:</span> $20</p>
            </div>
            <!-- Acciones -->
            <div class="border rounded-lg p-4 text-center">
                <h2 class="text-lg font-semibold mb-4">Acciones</h2>
                <button class="w-full bg-white text-black border py-2 rounded-md mb-4">Reagendar Cita</button>
                <button class="w-full bg-red-500 text-white py-2 rounded-md">Cancelar Cita</button>
            </div>
        </div>
    </div>
</body>
</html>
