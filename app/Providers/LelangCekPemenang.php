<?php

namespace App\Providers;

use App\Models\Bid;
use App\Models\Kategori;
use App\Models\Lelang;
use App\Models\Pemenang;
use App\Models\Struk;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Midtrans\Config;
use Midtrans\Transaction;

class LelangCekPemenang extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        $kategoris = Kategori::all();
        View::share('kategoris', $kategoris);
    }
}
