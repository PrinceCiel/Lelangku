<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

use Illuminate\Support\Facades\Schedule;
use App\Services\LelangService;

Schedule::call(function () {
    for ($i = 0; $i < 5; $i++) {
        app(LelangService::class)->handle();
        \Log::info('SCHEDULER MASUK ke-' . ($i + 1) . ' ' . now());
        if ($i < 4) { // jangan sleep di iterasi terakhir
            sleep(60);
        }
    }
})->everyFiveMinutes();
// Schedule::call(function () {
//     \Log::info('SCHEDULER MASUK ' . now());
// })->everyMinute();

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');
