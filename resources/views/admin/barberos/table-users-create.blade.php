<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Barbero</title>
    <!-- Incluye los estilos de Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.4.1/flowbite.min.css" rel="stylesheet" />
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>

<body class="bg-white text-black">
    <header class="bg-black text-white sm:rounded-lg shadow-md">
        <div class="container mx-auto px-4 py-4 flex justify-between items-center">
            <a class="text-xl font-bold text-white">Barbería</a>
            <nav class="space-x-4">
                <a href="{{ route('admin.dashboard') }}"
                    class="bg-red-700 hover:bg-gray-700 text-white py-2 px-4 rounded-md">Volver a la Lista</a>
            </nav>
        </div>
    </header>

    <main class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-semibold mb-6">Crear Barbero</h1>
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg bg-black text-white">
            <!-- Comienza el formulario aquí -->
            <form action="{{ route('barberos.store') }}" method="POST" enctype="multipart/form-data" class="p-6">
                @csrf
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div class="mb-4">
                        <label for="nombre_completo" class="block text-sm font-medium text-white">Nombre Completo</label>
                        <input type="text" id="nombre_completo" name="nombre_completo"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50 text-black"
                            required>
                    </div>
                    <div class="mb-4">
                        <label for="email" class="block text-sm font-medium text-white">Email</label>
                        <input type="email" id="email" name="email"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50 text-black"
                            required>
                    </div>
                    <div class="mb-4">
                        <label for="password" class="block text-sm font-medium text-white">Contraseña</label>
                        <input type="password" id="password" name="password"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50 text-black"
                            required>
                        @if ($errors->has('password'))
                            <span class="text-red-500 text-sm">{{ $errors->first('password') }}</span>
                        @endif
                    </div>
                    <div class="mb-4">
                        <label for="password_confirmation" class="block text-sm font-medium text-white">Confirmar Contraseña</label>
                        <input type="password" id="password_confirmation" name="password_confirmation"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50 text-black"
                            required>
                        @if ($errors->has('password_confirmation'))
                            <span class="text-red-500 text-sm">{{ $errors->first('password_confirmation') }}</span>
                        @endif
                    </div>
                    <div class="mb-4">
                        <label for="telefono" class="block text-sm font-medium text-white">Teléfono</label>
                        <input type="text" id="telefono" name="telefono"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50 text-black">
                    </div>
                    <div class="mb-4">
                        <label for="especialidad" class="block text-sm font-medium text-white">Especialidad</label>
                        <input type="text" id="especialidad" name="especialidad"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50 text-black"
                            required>
                    </div>
                    <div class="mb-4">
                        <label for="experiencia" class="block text-sm font-medium text-white">Experiencia</label>
                        <textarea id="experiencia" name="experiencia"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50 text-black"
                            required></textarea>
                    </div>
                    <div class="mb-4">
                        <label for="foto" class="block text-sm font-medium text-white">Foto</label>
                        <input type="file" id="foto" name="foto"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50 text-black">
                        <p class="mt-2 text-sm text-gray-500">Tamaño máximo: 2MB. Formatos permitidos: jpeg, png, jpg.</p>
                        <!-- Mensaje de error para la foto -->
                        @if ($errors->has('foto'))
                            <p class="mt-2 text-sm text-red-500">{{ $errors->first('foto') }}</p>
                        @endif
                    </div>
                </div>
                <div class="mb-4">
                    <button type="submit"
                        class="bg-green-500 hover:bg-green-600 text-white py-2 px-4 rounded">Guardar</button>
                </div>
            </form>
        </div>
    </main>
</body>

</html>
