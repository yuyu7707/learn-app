<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->middleware('guest')->name('home');

Route::middleware(['auth'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
});

require __DIR__.'/settings.php';
