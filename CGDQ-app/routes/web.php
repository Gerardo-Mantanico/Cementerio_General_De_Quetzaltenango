<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;



// Route::get('/', function () {
//     return view('welcome');
// })->name('home');

Route::get('/', function () {
    return redirect()->route('login');
})->name('home');


Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('dashboard-admin', 'dashboard-admin')
    ->middleware(['auth', 'verified'])
    ->name('dashboard-admin');

Route::view('dashboard-aux', 'dashboard-aux')
    ->middleware(['auth', 'verified'])
    ->name('dashboard-aux');

Route::view('dashboard-auditor', 'dashboard-auditor')
    ->middleware(['auth', 'verified'])
    ->name('dashboard-auditor');

Route::view('dashboard-user', 'dashboard-user')
    ->middleware(['auth', 'verified'])
    ->name('dashboard-user');


Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

require __DIR__.'/auth.php';
