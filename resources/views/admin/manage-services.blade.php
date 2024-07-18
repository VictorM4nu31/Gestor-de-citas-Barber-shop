<!-- resources/views/admin/manage-services.blade.php -->
<body class="bg-gray-100 p-6">
    <div class="container mx-auto">
        <div class="bg-white shadow-md rounded-lg p-6">
            <h1 class="text-2xl font-bold mb-4">Gestión de Servicios</h1>
            <!-- Formulario para agregar nuevos servicios -->
            <form id="service-form"  method="POST" class="mb-6">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="service-name" class="block text-sm font-medium text-gray-700">Nombre del Servicio</label>
                        <input type="text" name="name" id="service-name" class="mt-1 p-2 block w-full border rounded-md shadow-sm focus:ring-red-500 focus:border-red-500" required>
                    </div>
                    <div>
                        <label for="service-cost" class="block text-sm font-medium text-gray-700">Costo</label>
                        <input type="number" name="cost" id="service-cost" class="mt-1 p-2 block w-full border rounded-md shadow-sm focus:ring-red-500 focus:border-red-500" required>
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="bg-red-500 text-white p-2 rounded-md hover:bg-red-700">Agregar Servicio</button>
                    </div>
                </div>
            </form>
            <!-- Tabla de servicios -->
            <table class="min-w-full bg-white">
                <thead>
                    <tr>
                        <th class="py-2 px-4 bg-black text-white">Servicio</th>
                        <th class="py-2 px-4 bg-black text-white">Costo</th>
                        <th class="py-2 px-4 bg-black text-white">Acciones</th>
                    </tr>
                </thead>
                <tbody id="service-table-body">
                    <!-- Aquí se mostrarán los servicios -->
                </tbody>
            </table>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const serviceForm = document.getElementById('service-form');
            const servicesTableBody = document.getElementById('service-table-body');
            let editMode = false;
            let editRow = null;

            serviceForm.addEventListener('submit', function (event) {
                event.preventDefault();

                const serviceName = document.getElementById('service-name').value;
                const serviceCost = document.getElementById('service-cost').value;

                if (editMode) {
                    // Editar el servicio existente
                    editRow.children[0].textContent = serviceName;
                    editRow.children[1].textContent = serviceCost;
                    editMode = false;
                    editRow = null;
                } else {
                    // Agregar un nuevo servicio
                    const newRow = document.createElement('tr');
                    newRow.innerHTML = `
                        <td class="py-2 px-4 border-b">${serviceName}</td>
                        <td class="py-2 px-4 border-b">${serviceCost}</td>
                        <td class="py-2 px-4 border-b">
                            <button class="bg-red-500 text-white p-1 rounded-md hover:bg-red-700 mr-2">Eliminar</button>
                            <button class="bg-blue-500 text-white p-1 rounded-md hover:bg-blue-700">Editar</button>
                        </td>
                    `;
                    servicesTableBody.appendChild(newRow);
                }

                // Limpiar el formulario
                serviceForm.reset();
            });

            servicesTableBody.addEventListener('click', function (event) {
                if (event.target.textContent === 'Eliminar') {
                    event.target.closest('tr').remove();
                }

                if (event.target.textContent === 'Editar') {
                    editRow = event.target.closest('tr');
                    document.getElementById('service-name').value = editRow.children[0].textContent;
                    document.getElementById('service-cost').value = editRow.children[1].textContent;
                    editMode = true;
                }
            });
        });
        </script>

</body>
