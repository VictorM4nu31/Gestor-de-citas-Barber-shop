<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center flex-wrap">
            <h2 class="font-semibold text-xl text-white leading-tight">Panel de Administración</h2>
        </div>
    </x-slot>


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
