@props(['currentLocale' => app()->getLocale(), 'availableLocales' => ['es', 'en']])

<div {{ $attributes->merge(['class' => 'relative inline-block text-left']) }} x-data="{ open: false }">
    <div>
        <button @click="open = !open"
                @keydown.escape="open = false"
                type="button"
                class="inline-flex items-center justify-center w-full px-3 py-2 text-sm font-medium text-white bg-secondary hover:bg-secondary/80 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary rounded-md transition-colors duration-200"
                id="language-menu-button"
                aria-expanded="false"
                aria-haspopup="true"
                :aria-expanded="open">

            {{-- Current language flag and name --}}
            <span class="flex items-center space-x-2">
                @if($currentLocale === 'es')
                    <span class="text-lg" aria-hidden="true">🇪🇸</span>
                    <span class="hidden sm:inline">{{ __('common.language.spanish') }}</span>
                @else
                    <span class="text-lg" aria-hidden="true">🇺🇸</span>
                    <span class="hidden sm:inline">{{ __('common.language.english') }}</span>
                @endif
            </span>

            {{-- Dropdown arrow --}}
            <svg class="w-4 h-4 ml-2 -mr-1 transition-transform duration-200"
                 :class="{ 'rotate-180': open }"
                 xmlns="http://www.w3.org/2000/svg"
                 viewBox="0 0 20 20"
                 fill="currentColor"
                 aria-hidden="true">
                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
            </svg>
        </button>
    </div>

    {{-- Dropdown menu --}}
    <div x-show="open"
         x-transition:enter="transition ease-out duration-100"
         x-transition:enter-start="transform opacity-0 scale-95"
         x-transition:enter-end="transform opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="transform opacity-100 scale-100"
         x-transition:leave-end="transform opacity-0 scale-95"
         @click.outside="open = false"
         class="absolute right-0 z-50 w-48 mt-2 origin-top-right bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
         role="menu"
         aria-orientation="vertical"
         aria-labelledby="language-menu-button"
         tabindex="-1">

        <div class="py-1" role="none">
            @foreach($availableLocales as $locale)
                @if($locale !== $currentLocale)
                    <a href="{{ route('language.switch', $locale) }}"
                       class="group flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 focus:bg-gray-100 focus:text-gray-900 focus:outline-none transition-colors duration-150"
                       role="menuitem"
                       tabindex="-1"
                       @click="open = false">

                        <span class="flex items-center space-x-3">
                            @if($locale === 'es')
                                <span class="text-lg" aria-hidden="true">🇪🇸</span>
                                <span>{{ __('common.language.spanish') }}</span>
                            @else
                                <span class="text-lg" aria-hidden="true">🇺🇸</span>
                                <span>{{ __('common.language.english') }}</span>
                            @endif
                        </span>

                        {{-- Hover indicator --}}
                        <svg class="w-4 h-4 ml-auto opacity-0 group-hover:opacity-100 transition-opacity duration-150"
                             xmlns="http://www.w3.org/2000/svg"
                             viewBox="0 0 20 20"
                             fill="currentColor">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                        </svg>
                    </a>
                @endif
            @endforeach
        </div>
    </div>
</div>
