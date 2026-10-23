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

// 管理画面（運営者のみ）
Route::middleware(['auth', 'can:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::livewire('terms', 'pages::admin.terms.index')->name('terms.index');
    Route::livewire('terms/create', 'pages::admin.terms.form')->name('terms.create');
    Route::livewire('terms/{term}/edit', 'pages::admin.terms.form')->name('terms.edit');
});

require __DIR__.'/settings.php';
