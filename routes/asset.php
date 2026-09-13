<?php

use Illuminate\Support\Facades\Route;
use Jegex\Koboi\Http\Controllers\ScriptController;
use Jegex\Koboi\Http\Controllers\StyleController;

// Scripts & Styles...
Route::get('/scripts/{script}', ScriptController::class);
Route::get('/styles/{style}', StyleController::class);
