<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use \Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Schedule::command('app:get-weekly-report')->weekly();

Schedule::command('app:get-monthly-report')->monthly();

Schedule::command('app:get-yearly-report')->yearly();
