<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center flex-wrap">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Panel de Administración</h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-surface overflow-hidden shadow-sm sm:rounded-lg p-6 text-secondary">
                <div id="employees-section" class="">
                    @includeWhen(View::exists('admin.barberos.table-users'), 'admin.barberos.table-users')
                </div>
                <div id="services-section" class="hidden">
                    @includeWhen(View::exists('admin.servicios.manage-services'), 'admin.servicios.manage-services')
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const btnEmployees = document.getElementById('btn-employees');
            const btnServices = document.getElementById('btn-services');
            const employeesSection = document.getElementById('employees-section');
            const servicesSection = document.getElementById('services-section');

            btnEmployees.addEventListener('click', () => {
                employeesSection.classList.remove('hidden');
                servicesSection.classList.add('hidden');
            });

            btnServices.addEventListener('click', () => {
                servicesSection.classList.remove('hidden');
                employeesSection.classList.add('hidden');
            });
        });
    </script>
    @endpush
</x-app-layout>
