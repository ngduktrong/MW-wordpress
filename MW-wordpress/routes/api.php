<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PhimController;

Route::apiResource('phims', PhimController::class);
