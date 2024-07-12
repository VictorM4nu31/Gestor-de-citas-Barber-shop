<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agendar Cita</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/flowbite@1.6.3/dist/flowbite.min.js"></script>
</head>
<body class="flex items-center justify-center h-screen bg-gray-100">
    <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-lg">
        <!-- Mensaje de éxito o error -->
        @if(session('success'))
            <div id="successMessage" class="bg-green-100 text-green-700 p-4 rounded mb-4 text-center">
                {{ session('success') }}
            </div>
        @elseif(session('error'))
            <div id="errorMessage" class="bg-red-100 text-red-700 p-4 rounded mb-4 text-center">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('citas.store') }}" method="POST" id="citaForm">
            @csrf
            <input type="hidden" name="fecha" id="fecha" value="{{ $fecha }}" required>
            <div class="mb-4">
                <label for="nombre_completo" class="block text-gray-700">Nombre Completo</label>
                <input type="text" name="nombre_completo" id="nombre_completo" class="w-full border-gray-300 rounded p-2" value="{{ old('nombre_completo') }}" required>
                @error('nombre_completo')
                    <div class="text-red-500 mt-2 text-sm">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-4">
                <label for="numero_telefono" class="block text-gray-700">Número de Teléfono</label>
                <input type="text" name="numero_telefono" id="numero_telefono" class="w-full border-gray-300 rounded p-2" value="{{ old('numero_telefono') }}" required>
                @error('numero_telefono')
                    <div class="text-red-500 mt-2 text-sm">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-4">
                <label for="correo_electronico" class="block text-gray-700">Correo Electrónico</label>
                <input type="email" name="correo_electronico" id="correo_electronico" class="w-full border-gray-300 rounded p-2" value="{{ old('correo_electronico') }}" required>
                @error('correo_electronico')
                    <div class="text-red-500 mt-2 text-sm">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-4">
                <label for="id_servicio" class="block text-gray-700">Servicio</label>
                <select name="id_servicio" id="id_servicio" class="w-full border-gray-300 rounded p-2" required>
                    <option value="">Seleccione un servicio</option>
                    @foreach($servicios as $servicio)
                        <option value="{{ $servicio->id }}">{{ $servicio->nombre }}</option>
                    @endforeach
                </select>
                @error('id_servicio')
                    <div class="text-red-500 mt-2 text-sm">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-4">
                <label for="id_barbero" class="block text-gray-700">Barbero</label>
                <select name="id_barbero" id="id_barbero" class="w-full border-gray-300 rounded p-2" required>
                    <option value="">Seleccione un barbero</option>
                    @foreach($barberos as $barbero)
                        <option value="{{ $barbero->id }}">{{ $barbero->nombre_completo }}</option>
                    @endforeach
                </select>
                @error('id_barbero')
                    <div class="text-red-500 mt-2 text-sm">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-4">
                <label for="hora" class="block text-gray-700">Hora</label>
                <input type="time" name="hora" id="hora" class="w-full border-gray-300 rounded p-2" value="{{ old('hora') }}" required>
                @error('hora')
                    <div class="text-red-500 mt-2 text-sm">{{ $message }}</div>
                @enderror
            </div>
            <button type="submit" class="bg-blue-500 text-white p-2 rounded w-full">Agendar Cita</button>
        </form>
        <!-- Contador de tiempo -->
        <div class="mt-4 text-center">
            <span id="timer" class="text-red-500 font-bold text-lg">50</span> segundos restantes para completar la cita
        </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', (event) => {
                let timerElement = document.getElementById('timer');
                let timeLeft = 50;

                // Función de contador regresivo
                const countdown = setInterval(() => {
                    timeLeft--;
                    timerElement.textContent = timeLeft;
                    if (timeLeft <= 0) {
                        clearInterval(countdown);
                        window.location.href = "{{ route('calendar') }}";
                    }
                }, 1000);

                // Mostrar mensaje de éxito y redirigir después de 2 segundos
                @if(session('success'))
                    setTimeout(() => {
                        window.location.href = "{{ route('calendar') }}";
                    }, 2000);
                @endif
            });
        </script>
    </div>
</body>
</html>


