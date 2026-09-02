<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Schedule::command('sessions:cleanup')->everyMinute()->withoutOverlapping();
Schedule::command('app:check-router-health')->everyFiveMinutes()->withoutOverlapping();
Schedule::command('mpesa:reconcile')->everyFiveMinutes()->withoutOverlapping();
Schedule::command('notifications:send-expiry')->dailyAt('08:00')->withoutOverlapping();
