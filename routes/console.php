<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

use Illuminate\Support\Facades\Schedule;
use App\Services\LelangService;
use Illuminate\Support\Facades\Log;
// routes/console.php
Schedule::call(function () {
    app(LelangService::class)->handle();
})->everyMinute()->name('lelang-service')->withoutOverlapping();

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');
