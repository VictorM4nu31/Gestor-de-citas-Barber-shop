<?php

use App\Providers\AppServiceProvider;
use App\Providers\PageTitleServiceProvider;
use App\Providers\TranslationCacheServiceProvider;

return [
    AppServiceProvider::class,
    PageTitleServiceProvider::class,
    TranslationCacheServiceProvider::class,
];
