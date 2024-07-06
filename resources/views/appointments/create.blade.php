<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agendar Cita</title>
    @vite(['resources/js/app.js', 'resources/css/app.css'])
</head>
<body class="bg-gray-100">
    <div class="container mx-auto mt-10">
        <div class="bg-white p-5 rounded-lg shadow-lg max-w-md w-full mx-auto">
            <h2 class="text-2xl mb-4">Agendar Cita</h2>
            <form method="POST" action="{{ url('/appointments') }}">
                @csrf
                <div class="mb-4">
                    <label for="appointmentDate" class="block text-sm font-medium text-gray-700">Fecha</label>
                    <input type="text" name="date" id="appointmentDate" value="{{ $date }}" class="block w-full mt-1 rounded-md border-gray-300 shadow-sm" readonly>
                </div>
                <div class="mb-4">
                    <label for="appointmentTime" class="block text-sm font-medium text-gray-700">Hora</label>
                    <input type="time" name="time" id="appointmentTime" class="block w-full mt-1 rounded-md border-gray-300 shadow-sm">
                </div>
                <div class="mb-4">
                    <label for="appointmentDescription" class="block text-sm font-medium text-gray-700">Descripción</label>
                    <textarea name="description" id="appointmentDescription" class="block w-full mt-1 rounded-md border-gray-300 shadow-sm"></textarea>
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
