<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

// Schedule translation alerts to run daily
Schedule::command('translation:alert --threshold=5')->daily();
Schedule::command('appointments:send-reminders')
    ->dailyAt('09:00')
    ->withoutOverlapping()
    ->onOneServer();
