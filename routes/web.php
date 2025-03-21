<?php

use App\Http\Controllers\WaluteController;
use App\Http\Controllers\Wettercontroller;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('welcome');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('dashboard');
    })->name('dashboard');
});

Route::get('/wetter', [Wettercontroller::class, 'wetter']);

Route::match(['get', 'post'], '/walute', [WaluteController::class, 'walute']);

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
