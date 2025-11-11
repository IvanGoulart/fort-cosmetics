<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// 🕒 Executar a sincronização da produtos novos 
Schedule::command('sync:cosmetics-new')->everyMinute();

// 🕒 Executar a sincronização da loja 
Schedule::command('sync:cosmetics-shop')->everyMinute();
