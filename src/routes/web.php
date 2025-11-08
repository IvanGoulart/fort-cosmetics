<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\CosmeticController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\TransactionController;

Route::get('/register', [RegisteredUserController::class, 'create'])->middleware('guest')->name('register');
Route::post('/register', [RegisteredUserController::class, 'store'])->middleware('guest');
Route::get('/usuarios', [App\Http\Controllers\UserController::class, 'index'])->name('users.index');
Route::get('/usuarios/{id}', [App\Http\Controllers\UserController::class, 'show'])->name('users.show');

Route::get('/login', [LoginController::class, 'showLoginForm'])->middleware('guest')->name('login');
Route::post('/login', [LoginController::class, 'login'])->middleware('guest');
Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth')->name('logout');
Route::get('/', [CosmeticController::class, 'index'])->name('cosmetics.index');
Route::get('/cosmetics/{id}', [CosmeticController::class, 'show'])->name('cosmetics.show');
Route::get('/sync-cosmetics', [CosmeticController::class, 'sync'])->name('cosmetics.sync');

Route::middleware(['auth'])->group(function () {
    Route::get('/meus-cosmeticos', [ShopController::class, 'index'])->name('my.cosmetics');
    Route::post('/comprar/{id}', [ShopController::class, 'buy'])->name('buy');
    Route::post('/devolver/{id}', [ShopController::class, 'refund'])->name('refund');
    Route::get('/historico', [TransactionController::class, 'index'])->name('transactions.index');
});


