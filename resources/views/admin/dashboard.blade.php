<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <!-- Incluye los estilos de Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.4.1/flowbite.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body class="font-poppins antialiased">
    <div id="view" class="h-full w-screen flex flex-row" x-data="{ sidenav: true }">
        <button
            @click="sidenav = true"
            class="p-2 border-2 bg-white rounded-md border-gray-200 shadow-lg text-gray-500 focus:bg-teal-500 focus:outline-none focus:text-white absolute top-0 left-0 sm:hidden"
        >
            <svg
                class="w-5 h-5 fill-current"
                fill="currentColor"
                viewBox="0 0 20 20"
                xmlns="http://www.w3.org/2000/svg"
            >
                <path
                    fill-rule="evenodd"
                    d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"
                    clip-rule="evenodd"
                ></path>
            </svg>
        </button>
        <div
            id="sidebar"
            class="bg-white h-screen md:block shadow-xl px-3 w-30 md:w-60 lg:w-60 overflow-x-hidden transition-transform duration-300 ease-in-out"
            x-show="sidenav"
            @click.away="sidenav = false"
        >
            <div class="space-y-6 md:space-y-10 mt-10">
                <h1 class="font-bold text-4xl text-center md:hidden">D<span class="text-teal-600">.</span></h1>
                <h1 class="hidden md:block font-bold text-sm md:text-xl text-center">Dashwind<span class="text-teal-600">.</span></h1>
                <div id="profile" class="space-y-3">
                    <img
                        src="https://images.unsplash.com/photo-1628157588553-5eeea00af15c?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=880&q=80"
                        alt="Avatar user"
                        class="w-10 md:w-16 rounded-full mx-auto"
                    />
                    <div>
                        <h2 class="font-medium text-xs md:text-sm text-center text-teal-500">Eduard Pantazi</h2>
                        <p class="text-xs text-gray-500 text-center">Administrator</p>
                    </div>
                </div>
                <div id="menu" class="flex flex-col space-y-2">
                    <a
                        href="#"
                        class="text-sm font-medium text-gray-700 py-2 px-2 hover:bg-teal-500 hover:text-white hover:scale-105 rounded-md transition duration-150 ease-in-out"
                    >
                        <svg
                            class="w-6 h-6 fill-current inline-block"
                            fill="currentColor"
                            viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path
                                d="M11 17a1 1 0 001.447.894l4-2A1 1 0 0017 15V9.236a1 1 0 00-1.447-.894l-4 2a1 1 0 00-.553.894V17zM15.211 6.276a1 1 0 000-1.788l-4.764-2.382a1 1 0 00-.894 0L4.789 4.488a1 1 0 000 1.788l4.764 2.382a1 1 0 00.894 0l4.764-2.382zM4.447 8.342A1 1 0 003 9.236V15a1 1 0 00.553.894l4 2A1 1 0 009 17v-5.764a1 1 0 00-.553-.894l-4-2z"
                            ></path>
                        </svg>
                        <span>Gestionar Citas</span>
                    </a>
                    <a
                        href="#table-user"
                        class="text-sm font-medium text-gray-700 py-2 px-2 hover:bg-teal-500 hover:text-white hover:scale-105 rounded-md transition duration-150 ease-in-out"
                    >
                        <svg
                            class="w-6 h-6 fill-current inline-block"
                            fill="currentColor"
                            viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path
                                d="M4 3a1 1 0 000 2h12a1 1 0 100-2H4zM3 7a1 1 0 011-1h4a1 1 0 110 2H4a1 1 0 01-1-1zM3 11a1 1 0 011-1h10a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h8a1 1 0 110 2H4a1 1 0 01-1-1z"
                            ></path>
                        </svg>
                        <span>Gestionar Empleados</span>
                    </a>
                    <a
                        href="#"
                        class="text-sm font-medium text-gray-700 py-2 px-2 hover:bg-teal-500 hover:text-white hover:scale-105 rounded-md transition duration-150 ease-in-out"
                    >
                        <svg
                            class="w-6 h-6 fill-current inline-block"
                            fill="currentColor"
                            viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path
                                d="M4 3a1 1 0 000 2h12a1 1 0 100-2H4zM3 7a1 1 0 011-1h4a1 1 0 110 2H4a1 1 0 01-1-1zM3 11a1 1 0 011-1h10a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h8a1 1 0 110 2H4a1 1 0 01-1-1z"
                            ></path>
                        </svg>
                        <span>Gestionar Servicios</span>
                    </a>
                    <a
                        href="#"
                        class="text-sm font-medium text-gray-700 py-2 px-2 hover:bg-teal-500 hover:text-white hover:scale-105 rounded-md transition duration-150 ease-in-out"
                    >
                        <svg
                            class="w-6 h-6 fill-current inline-block"
                            fill="currentColor"
                            viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path
                                d="M10 2a6 6 0 00-6 6c0 4.2 4 7.33 5.65 8.45a1.3 1.3 0 001.42 0C12 15.33 16 12.2 16 8a6 6 0 00-6-6z"
                            ></path>
                        </svg>
                        <span>Gestionar Productos</span>
                    </a>
                </div>
            </div>
        </div>
        <div class="bg-gray-100 flex-grow text-gray-700 p-6">
            <h1 class="text-4xl font-semibold mb-6">Dashboard</h1>
            <!-- Contenido del dashboard -->
            @include('admin.table-users')
        </div>
    </div>
</body>
</html>
