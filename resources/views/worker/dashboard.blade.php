<!-- resources/views/admin/dashboard.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <!-- Incluye los estilos de Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.4.1/flowbite.min.css" rel="stylesheet" />
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body class="font-poppins antialiased bg-black text-white">
    <div id="view" class="h-full w-screen flex flex-row" x-data="{ sidenav: true }">
        <button
            @click="sidenav = true"
            class="p-2 border-2 bg-red-600 rounded-md border-red-700 shadow-lg text-white focus:bg-red-700 focus:outline-none absolute top-0 left-0 sm:hidden"
        >
            <svg class="w-5 h-5 fill-current" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path>
            </svg>
        </button>
        <div
            id="sidebar"
            class="bg-red-800 h-screen md:block shadow-xl px-3 w-30 md:w-60 lg:w-60 overflow-x-hidden transition-transform duration-300 ease-in-out"
            x-show="sidenav"
            @click.away="sidenav = false"
        >
            <div class="space-y-6 md:space-y-10 mt-10">
                <h1 class="font-bold text-4xl text-center md:hidden text-white">M<span class="text-red-400">.</span></h1>
                <h1 class="hidden md:block font-bold text-sm md:text-xl text-center text-white">MasterCut<span class="text-red-400">.</span></h1>
                <div id="profile" class="space-y-3">
                    <img
                        src="https://images.unsplash.com/photo-1628157588553-5eeea00af15c?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=880&q=80"
                        alt="Avatar user"
                        class="w-10 md:w-16 rounded-full mx-auto"
                    />
                    <div>
                        <h2 class="font-medium text-xs md:text-sm text-center text-red-400">Eduard Pantazi</h2>
                        <p class="text-xs text-gray-300 text-center">Trabajador</p>
                    </div>
                </div>
                <div id="menu" class="flex flex-col space-y-2">
                    <a href="#" class="text-sm font-medium text-white py-2 px-2 hover:bg-red-700 hover:text-white hover:scale-105 rounded-md transition duration-150 ease-in-out">
                        <svg class="w-6 h-6 fill-current inline-block" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path d="M11 17a1 1 0 001.447.894l4-2A1 1 0 0017 15V9.236a1 1 0 00-1.447-.894l-4 2a1 1 0 00-.553.894V17zM15.211 6.276a1 1 0 000-1.788l-4.764-2.382a1 1 0 00-.894 0L4.789 4.488a1 1 0 000 1.788l4.764 2.382a1 1 0 00.894 0l4.764-2.382zM4.447 8.342A1 1 0 003 9.236V15a1 1 0 00.553.894l4 2A1 1 0 009 17v-5.764a1 1 0 00-.553-.894l-4-2z"></path>
                        </svg>
                        <span>Gestionar Citas</span>
                    </a>
                </div>
            </div>
        </div>
        <div class="bg-white flex-grow text-black p-6">
            <h1 class="text-4xl font-semibold mb-6">Dashboard</h1>
            <!-- Contenido del dashboard -->
            @include('worker.appointment-manager')
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const serviceForm = document.getElementById('service-form');
            const servicesTableBody = document.getElementById('services-table-body');

            serviceForm.addEventListener('submit', function (event) {
                event.preventDefault();

                const serviceName = document.getElementById('service-name').value;
                const serviceDescription = document.getElementById('service-description').value;
                const serviceCost = document.getElementById('service-cost').value;

                const newRow = document.createElement('tr');
                newRow.innerHTML = `
                    <td class="py-2 px-4">${serviceName}</td>
                    <td class="py-2 px-4">${serviceDescription}</td>
                    <td class="py-2 px-4">${serviceCost}</td>
                    <td class="py-2 px-4">
                        <button class="bg-red-500 text-white px-2 py-1 rounded-md hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-600">Eliminar</button>
                    </td>
                `;

                servicesTableBody.appendChild(newRow);

                // Limpiar el formulario
                serviceForm.reset();
            });

            servicesTableBody.addEventListener('click', function (event) {
                if (event.target.tagName === 'BUTTON') {
                    event.target.closest('tr').remove();
                }
            });
        });
        </script>

</body>
</html>
