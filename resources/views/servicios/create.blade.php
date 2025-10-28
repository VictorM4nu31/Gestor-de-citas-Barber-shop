<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Servicio</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100">
    <header class="bg-gray-800 text-white">
        <div class="container mx-auto px-4 py-4 flex justify-between items-center">
            <a href="/" class="text-xl font-bold">Barbería</a>
            <nav class="space-x-4">
                <a href="{{ route('servicios.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white py-2 px-4 rounded">Volver a la Lista</a>
            </nav>
        </div>
    </header>

    <main class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-semibold mb-6">Crear Servicio</h1>
        <form action="{{ route('servicios.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div class="mb-4">
                    <label for="nombre" class="block text-sm font-medium text-gray-700">Nombre</label>
                    <input type="text" id="nombre" name="nombre" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50" required>
                </div>
                <div class="mb-4">
                    <label for="descripcion" class="block text-sm font-medium text-gray-700">Descripción</label>
                    <textarea id="descripcion" name="descripcion" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50" required></textarea>
                </div>
                <div class="mb-4">
                    <label for="duracion" class="block text-sm font-medium text-gray-700">Duración (minutos)</label>
                    <input type="number" id="duracion" name="duracion" min="1" step="1" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50" required>
                </div>
                <div class="mb-4">
                    <label for="precio" class="block text-sm font-medium text-gray-700">Precio</label>
                    <input type="number" id="precio" name="precio" step="0.01" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50" required>
                </div>
                <div class="mb-4">
                    <label for="foto" class="block text-sm font-medium text-gray-700">Foto</label>
                    <input type="file" id="foto" name="foto" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                    <p class="mt-2 text-sm text-gray-500">Tamaño máximo: 2MB. Formatos permitidos: jpeg, png, jpg.</p>
                    <!-- Mensaje de error para la foto -->
                    @if ($errors->has('foto'))
                        <p class="mt-2 text-sm text-red-500">{{ $errors->first('foto') }}</p>
                    @endif
                </div>
            </div>
            <div class="mb-4">
                <button type="submit" class="bg-green-500 hover:bg-green-600 text-white py-2 px-4 rounded">Guardar</button>
            </div>
        </form>
    </main>
</body>
</html>
