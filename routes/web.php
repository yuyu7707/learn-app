<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->middleware('guest')->name('home');

Route::middleware(['auth'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');

    Route::livewire('goal', 'pages::goal.edit')->name('goal.edit');

    Route::livewire('terms', 'pages::terms.index')->name('terms.index');
    Route::livewire('terms/{term}', 'pages::terms.show')->name('terms.show');
    Route::livewire('terms/{term}/quiz', 'pages::terms.quiz')->name('terms.quiz');
});

require __DIR__.'/settings.php';
