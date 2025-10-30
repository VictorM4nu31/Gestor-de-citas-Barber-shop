<section id="home" class="py-16 bg-primary">
    <div class="container mx-auto px-4 lg:px-8 flex flex-col lg:flex-row items-center gap-8">
        <div class="lg:w-1/2 text-center lg:text-left">
            <p class="text-sm font-semibold text-light tracking-widest">PREMIUM BARBERING</p>
            <h1 class="mt-4 text-5xl lg:text-6xl font-extrabold text-light leading-tight">{{ $title ?? 'Crafted to Perfection' }}</h1>
            <p class="mt-6 text-lg text-graylight max-w-xl">{{ $subtitle ?? 'Experience the art of traditional barbering combined with modern techniques. Our master barbers deliver precision cuts and grooming services that define excellence.' }}</p>

            <div class="mt-8 flex justify-center lg:justify-start gap-4">
                <a href="#contact" class="inline-block bg-light text-primary font-medium py-3 px-6 rounded-md shadow">{{ $primary_cta ?? 'Book Appointment' }}</a>
                @if(isset($secondary_cta))
                    <a href="#services" class="inline-block bg-transparent border border-light text-light font-medium py-3 px-6 rounded-md">{{ $secondary_cta }}</a>
                @endif
            </div>
        </div>

        <div class="lg:w-1/2">
            <div class="w-full rounded-xl overflow-hidden shadow-lg">
                @if(isset($image))
                    <img src="{{ $image }}" alt="Hero" class="w-full object-cover h-96">
                @else
                    <img src="{{ asset('img/hero.jpg') }}" alt="Hero" class="w-full object-cover h-96">
                @endif
            </div>
        </div>
    </div>
</section>
