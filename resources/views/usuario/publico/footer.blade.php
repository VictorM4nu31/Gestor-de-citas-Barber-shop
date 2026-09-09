

<footer id="footer" class="relative bg-secondary text-muted border-t border-accent pt-8 pb-6">
    <div class="container mx-auto px-4">
        <div class="flex flex-wrap text-left lg:text-left">
            <div class="w-full lg:w-6/12 px-4">
                <h4 class="text-3xl font-semibold text-light">{{ __('common.company.name') }}</h4>
                <h5 class="text-lg mt-0 mb-2 text-muted">
                    {{ __('common.company.tagline') }}
                </h5>
                <div class="mt-6 lg:mb-0 mb-6">
                    <button
                        class="bg-light text-primary shadow-lg font-normal h-10 w-10 items-center justify-center align-center rounded-full outline-none focus:outline-none mr-2"
                        type="button" aria-label="Twitter">
                        <i class="fab fa-twitter" aria-hidden="true"></i></button><button
                        class="bg-light text-primary shadow-lg font-normal h-10 w-10 items-center justify-center align-center rounded-full outline-none focus:outline-none mr-2"
                        type="button" aria-label="Facebook">
                        <i class="fab fa-facebook-square" aria-hidden="true"></i></button><button
                        class="bg-light text-primary shadow-lg font-normal h-10 w-10 items-center justify-center align-center rounded-full outline-none focus:outline-none mr-2"
                        type="button" aria-label="Instagram">
                        <i class="fab fa-instagram" aria-hidden="true"></i></button>
                </div>
            </div>
            <div class="w-full lg:w-6/12 px-4">
                <div class="flex flex-wrap items-top mb-6">
                    <div class="w-full lg:w-4/12 px-4 ml-auto">
                        <span class="block uppercase text-white text-sm font-semibold mb-2">{{ __('common.footer.find_us') }}</span>
                        <ul class="list-unstyled">
                            <li>
                                <p class="text-muted font-semibold block pb-2 text-sm">{{ __('common.footer.address_line1') }}</p>
                            </li>
                            <li>
                                <p class="text-muted font-semibold block pb-2 text-sm">{{ __('common.footer.address_line2') }}</p>
                            </li>
                        </ul>
                    </div>
                    <div class="w-full lg:w-4/12 px-4">
                        <span class="block uppercase text-white text-sm font-semibold mb-2">{{ __('common.footer.contact_us') }}</span>
                        <ul class="list-unstyled">
                            <li>
                                <p class="text-muted font-semibold block pb-2 text-sm">{{ __('common.footer.phone') }}</p>
                            </li>
                            <li>
                                <p class="text-muted font-semibold block pb-2 text-sm break-all">{{ __('common.footer.email') }}</p>
                            </li>
                        </ul>
                    </div>
                    <div class="w-full lg:w-4/12 px-4">
                        <span class="block uppercase text-white text-sm font-semibold mb-2">{{ __('common.footer.hours') }}</span>
                        <ul class="list-unstyled">
                            <li>
                                <p class="text-muted font-semibold block pb-2 text-sm">{{ __('common.footer.hours_weekdays') }}</p>
                            </li>
                            <li>
                                <p class="text-muted font-semibold block pb-2 text-sm">{{ __('common.footer.hours_saturday') }}</p>
                            </li>
                            <li>
                                <p class="text-muted font-semibold block pb-2 text-sm">{{ __('common.footer.hours_sunday') }}</p>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <hr class="my-6 border-metal">
        <div class="flex flex-wrap items-center md:justify-between justify-center">
            <div class="w-full md:w-4/12 px-4 mx-auto text-center">
                <div class="text-sm text-muted font-semibold py-1">
                    {{ __('common.footer.book_online') }} <a href="https://www.mastercutbarber.com/reservas"
                        class="text-light hover:text-primary">{{ __('common.footer.website') }}</a>
                    <br>
                    {{ __('common.footer.copyright', ['year' => date('Y')]) }}
                </div>
            </div>
        </div>
    </div>
</footer>