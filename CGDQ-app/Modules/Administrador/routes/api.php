<?php

use Illuminate\Support\Facades\Route;
use Modules\Administrador\Http\Controllers\AdministradorController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('administrador', AdministradorController::class)->names('administrador');
});
