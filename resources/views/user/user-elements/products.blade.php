<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <title>Document</title>
</head>

<body class="bg-gray-100">
    <div class="container mx-auto p-4 lg:h-screen flex items-center justify-center">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Producto 1: Máquina de Afeitar Eléctrica -->
            <div class="w-60 h-80 bg-gray-50 p-3 flex flex-col gap-1 rounded-2xl shadow-md">
                <div class="h-48 bg-gray-700 rounded-xl">
                    <img src="https://ejemplo.com/maquina-afeitar.jpg" alt="Máquina de Afeitar Eléctrica"
                        class="w-full h-full object-cover rounded-xl">
                </div>
                <div class="flex flex-col gap-4">
                    <div class="flex flex-row justify-between">
                        <div class="flex flex-col">
                            <span class="text-xl font-bold">Máquina de Afeitar Eléctrica Pro</span>
                            <p class="text-xs text-gray-700">Máquina de afeitar eléctrica de alta precisión con 5 cuchillas y sistema de limpieza automática.</p>
                        </div>
                        <span class="font-bold text-red-600">$123.45</span>
                    </div>
                    <button class="hover:bg-sky-700 text-gray-50 bg-sky-800 py-2 rounded-md flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 512 512">
                            <path
                                d="m397.78 316h-205.13a15 15 0 0 1 -14.65-11.67l-34.54-150.48a15 15 0 0 1 14.62-18.36h274.27a15 15 0 0 1 14.65 18.36l-34.6 150.48a15 15 0 0 1 -14.62 11.67zm-193.19-30h181.25l27.67-120.48h-236.6z">
                            </path>
                            <path
                                d="m222 450a57.48 57.48 0 1 1 57.48-57.48 57.54 57.54 0 0 1 -57.48 57.48zm0-84.95a27.48 27.48 0 1 0 27.48 27.47 27.5 27.5 0 0 0 -27.48-27.47z">
                            </path>
                            <path
                                d="m368.42 450a57.48 57.48 0 1 1 57.48-57.48 57.54 57.54 0 0 1 -57.48 57.48zm0-84.95a27.48 27.48 0 1 0 27.48 27.47 27.5 27.5 0 0 0 -27.48-27.47z">
                            </path>
                            <path
                                d="m158.08 165.49a15 15 0 0 1 -14.23-10.26l-25.71-77.23h-47.44a15 15 0 1 1 0-30h58.3a15 15 0 0 1 14.23 10.26l29.13 87.49a15 15 0 0 1 -14.23 19.74z">
                            </path>
                        </svg>
                        <span class="ml-2">Add to cart</span>
                    </button>
                </div>
            </div>

            <!-- Producto 2: Aceite para Barba -->
            <div class="w-60 h-80 bg-gray-50 p-3 flex flex-col gap-1 rounded-2xl shadow-md">
                <div class="h-48 bg-gray-700 rounded-xl">
                    <img src="https://ejemplo.com/aceite-barba.jpg" alt="Aceite para Barba"
                        class="w-full h-full object-cover rounded-xl">
                </div>
                <div class="flex flex-col gap-4">
                    <div class="flex flex-row justify-between">
                        <div class="flex flex-col">
                            <span class="text-xl font-bold">Aceite para Barba Suavizante</span>
                            <p class="text-xs text-gray-700">Aceite natural que hidrata, suaviza y promueve el crecimiento saludable del vello facial.</p>
                        </div>
                        <span class="font-bold text-red-600">$123.45</span>
                    </div>
                    <button class="hover:bg-sky-700 text-gray-50 bg-sky-800 py-2 rounded-md flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 512 512">
                            <path
                                d="m397.78 316h-205.13a15 15 0 0 1 -14.65-11.67l-34.54-150.48a15 15 0 0 1 14.62-18.36h274.27a15 15 0 0 1 14.65 18.36l-34.6 150.48a15 15 0 0 1 -14.62 11.67zm-193.19-30h181.25l27.67-120.48h-236.6z">
                            </path>
                            <path
                                d="m222 450a57.48 57.48 0 1 1 57.48-57.48 57.54 57.54 0 0 1 -57.48 57.48zm0-84.95a27.48 27.48 0 1 0 27.48 27.47 27.5 27.5 0 0 0 -27.48-27.47z">
                            </path>
                            <path
                                d="m368.42 450a57.48 57.48 0 1 1 57.48-57.48 57.54 57.54 0 0 1 -57.48 57.48zm0-84.95a27.48 27.48 0 1 0 27.48 27.47 27.5 27.5 0 0 0 -27.48-27.47z">
                            </path>
                            <path
                                d="m158.08 165.49a15 15 0 0 1 -14.23-10.26l-25.71-77.23h-47.44a15 15 0 1 1 0-30h58.3a15 15 0 0 1 14.23 10.26l29.13 87.49a15 15 0 0 1 -14.23 19.74z">
                            </path>
                        </svg>
                        <span class="ml-2">Add to cart</span>
                    </button>
                </div>
            </div>

            <!-- Producto 3: Brocha de Afeitar -->
            <div class="w-60 h-80 bg-gray-50 p-3 flex flex-col gap-1 rounded-2xl shadow-md">
                <div class="h-48 bg-gray-700 rounded-xl">
                    <img src="https://ejemplo.com/brocha-afeitar.jpg" alt="Brocha de Afeitar"
                        class="w-full h-full object-cover rounded-xl">
                </div>
                <div class="flex flex-col gap-4">
                    <div class="flex flex-row justify-between">
                        <div class="flex flex-col">
                            <span class="text-xl font-bold">Brocha de Afeitar Premium</span>
                            <p class="text-xs text-gray-700">Brocha hecha a mano con cerdas naturales para un afeitado suave y confortable.</p>
                        </div>
                        <span class="font-bold text-red-600">$49.99</span>
                    </div>
                    <button class="hover:bg-sky-700 text-gray-50 bg-sky-800 py-2 rounded-md flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 512 512">
                            <path
                                d="m397.78 316h-205.13a15 15 0 0 1 -14.65-11.67l-34.54-150.48a15 15 0 0 1 14.62-18.36h274.27a15 15 0 0 1 14.65 18.36l-34.6 150.48a15 15 0 0 1 -14.62 11.67zm-193.19-30h181.25l27.67-120.48h-236.6z">
                            </path>
                            <path
                                d="m222 450a57.48 57.48 0 1 1 57.48-57.48 57.54 57.54 0 0 1 -57.48 57.48zm0-84.95a27.48 27.48 0 1 0 27.48 27.47 27.5 27.5 0 0 0 -27.48-27.47z">
                            </path>
                            <path
                                d="m368.42 450a57.48 57.48 0 1 1 57.48-57.48 57.54 57.54 0 0 1 -57.48 57.48zm0-84.95a27.48 27.48 0 1 0 27.48 27.47 27.5 27.5 0 0 0 -27.48-27.47z">
                            </path>
                            <path
                                d="m158.08 165.49a15 15 0 0 1 -14.23-10.26l-25.71-77.23h-47.44a15 15 0 1 1 0-30h58.3a15 15 0 0 1 14.23 10.26l29.13 87.49a15 15 0 0 1 -14.23 19.74z">
                            </path>
                        </svg>
                        <span class="ml-2">Add to cart</span>
                    </button>
                </div>
            </div>

            <!-- Producto 4: Jabón de Afeitar -->
            <div class="w-60 h-80 bg-gray-50 p-3 flex flex-col gap-1 rounded-2xl shadow-md">
                <div class="h-48 bg-gray-700 rounded-xl">
                    <img src="https://ejemplo.com/jabon-afeitar.jpg" alt="Jabón de Afeitar"
                        class="w-full h-full object-cover rounded-xl">
                </div>
                <div class="flex flex-col gap-4">
                    <div class="flex flex-row justify-between">
                        <div class="flex flex-col">
                            <span class="text-xl font-bold">Jabón de Afeitar Clásico</span>
                            <p class="text-xs text-gray-700">Jabón de afeitar enriquecido con manteca de karité para una espuma rica y cremosa.</p>
                        </div>
                        <span class="font-bold text-red-600">$19.99</span>
                    </div>
                    <button class="hover:bg-sky-700 text-gray-50 bg-sky-800 py-2 rounded-md flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 512 512">
                            <path
                                d="m397.78 316h-205.13a15 15 0 0 1 -14.65-11.67l-34.54-150.48a15 15 0 0 1 14.62-18.36h274.27a15 15 0 0 1 14.65 18.36l-34.6 150.48a15 15 0 0 1 -14.62 11.67zm-193.19-30h181.25l27.67-120.48h-236.6z">
                            </path>
                            <path
                                d="m222 450a57.48 57.48 0 1 1 57.48-57.48 57.54 57.54 0 0 1 -57.48 57.48zm0-84.95a27.48 27.48 0 1 0 27.48 27.47 27.5 27.5 0 0 0 -27.48-27.47z">
                            </path>
                            <path
                                d="m368.42 450a57.48 57.48 0 1 1 57.48-57.48 57.54 57.54 0 0 1 -57.48 57.48zm0-84.95a27.48 27.48 0 1 0 27.48 27.47 27.5 27.5 0 0 0 -27.48-27.47z">
                            </path>
                            <path
                                d="m158.08 165.49a15 15 0 0 1 -14.23-10.26l-25.71-77.23h-47.44a15 15 0 1 1 0-30h58.3a15 15 0 0 1 14.23 10.26l29.13 87.49a15 15 0 0 1 -14.23 19.74z">
                            </path>
                        </svg>
                        <span class="ml-2">Add to cart</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
