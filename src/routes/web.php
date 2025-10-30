<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\CosmeticController;

Route::get('/', [CosmeticController::class, 'index'])->name('cosmetics.index');
Route::get('/cosmetics/{id}', [CosmeticController::class, 'show'])->name('cosmetics.show');
Route::get('/sync-cosmetics', [CosmeticController::class, 'sync'])->name('cosmetics.sync');
