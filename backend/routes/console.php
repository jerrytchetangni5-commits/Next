<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');


Schedule::command('notify:deadlines')
    ->dailyAt('11:30')
    ->withoutOverlapping();



Schedule::command('scholarships:sync')
    ->cron('0 3 */2 * *')  // Tous les 2 jours à 3h du matin
    ->withoutOverlapping()
    ->runInBackground();