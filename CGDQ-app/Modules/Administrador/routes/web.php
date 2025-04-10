<?php

use Illuminate\Support\Facades\Route;
use Modules\Administrador\Http\Controllers\AdministradorController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('administrador', AdministradorController::class)->names('administrador');
});
