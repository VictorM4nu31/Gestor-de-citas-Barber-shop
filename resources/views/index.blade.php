<!-- resources/views/index.blade.php -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Index Page</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.4.1/flowbite.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
</head>

<body>
    <!-- Incluir Navbar -->
    @include('user.user-elements.navbar')
    <br>

    <!-- Hero Section -->
    <section class="bg-black text-white py-20">
        <div class="container mx-auto px-6 text-center">
            <h1 class="text-5xl font-bold mb-4">Bienvenido a Tu Barbería</h1>
            <p class="text-xl mb-8">Experimenta el mejor estilo para tu cabello</p>
            <a href="#" class="bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-full text-lg">Reserva Ahora</a>
        </div>
    </section>
    <br>

    <!-- Container -->
    <div class="relative flex flex-col items-center mx-auto lg:flex-row-reverse lg:max-w-5xl lg:mt-12 xl:max-w-6xl">
        <!-- Image Column -->
        <div class="w-full h-64 lg:w-1/2 lg:h-auto">
            <img class="h-full w-full object-cover" src="{{ asset('img/prueba.jpeg') }}" alt="Winding mountain road">
        </div>
        <div class="max-w-lg bg-white md:max-w-2xl md:z-10 md:shadow-lg md:absolute md:top-0 md:mt-48 lg:w-3/5 lg:left-0 lg:mt-20 lg:ml-20 xl:mt-24 xl:ml-12">
            <!-- Text Wrapper -->
            <div class="flex flex-col p-12 md:px-16">
                <h2 class="text-2xl font-medium uppercase text-red-600 lg:text-4xl">Master Cut Barber Shop</h2>
                <p class="mt-4 text-black">
                    Estilo y precisión en cada corte. Experimenta la excelencia en barbería.
                </p>
            </div>
            <!-- Close Text Wrapper -->
        </div>
        <!-- Close Text Column -->
    </div>


    <!-- Carrusel de Cortes de Cabello -->
    <section class="bg-gray-100 py-20">
        <div class="container mx-auto px-6">
            <h2 class="text-3xl font-bold text-center mb-8">Nuestros Trabajos</h2>
            <!-- carrusel -->
            @include('user.user-elements.gallery')
            <!-- fin de carrusel -->

        </div>
    </section>
    <br>
    <!-- Catálogo de Productos -->
    <section class="py-20">
        <div class="container mx-auto px-6">
            <h2 class="text-3xl font-bold text-center mb-8">Productos Destacados</h2>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                @include('user.user-elements.products')
            </div>
        </div>
    </section>

    @include('user.user-elements.footer')

    @stack('js')
</body>

</html>
<script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.4.1/flowbite.min.js"></script>
