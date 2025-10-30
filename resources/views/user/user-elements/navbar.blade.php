<nav class="bg-secondary text-light border-b border-metal w-100 px-8 md:px-auto">
    <div class="md:h-16 h-28 mx-auto md:px-4 container flex items-center justify-between flex-wrap md:flex-nowrap">
        <!-- Logo -->
        <div class="md:order-1">
            <!-- Logo Image -->
            <img src="{{ asset('img/logo.png') }}" class="h-10 w-10" alt="Logo">
        </div>
        <div class="text-light order-3 w-full md:w-auto md:order-2">
            <ul class="flex font-semibold justify-between">
                <!-- Active Link = text-red-500
                Inactive Link = hover:text-red-500 -->
                <li class="md:px-4 md:py-2 text-primary"><a href="#">Inicio</a></li>
                <li class="md:px-4 md:py-2 hover:text-primary"><a href="#footer">Contacto</a></li>
            </ul>
        </div>
        <div class="order-2 md:order-3">
            <a href="{{ route('login') }}" class="px-4 py-2 bg-primary hover:bg-secondary text-light rounded-xl flex items-center gap-2">
                <!-- Heroicons - Login Solid -->
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M3 3a1 1 0 011 1v12a1 1 0 11-2 0V4a1 1 0 011-1zm7.707 3.293a1 1 0 010 1.414L9.414 9H17a1 1 0 110 2H9.414l1.293 1.293a1 1 0 01-1.414 1.414l-3-3a1 1 0 010-1.414l3-3a1 1 0 011.414 0z" clip-rule="evenodd" />
                </svg>
                <span>Login</span>
            </a>
        </div>
    </div>
</nav>
