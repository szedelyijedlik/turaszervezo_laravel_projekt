<?php

use App\Http\Controllers\ParticipantController;
use App\Http\Controllers\ToursController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('tours', ToursController::class);
Route::get('tours/{tour}/participants', [ToursController::class]);

Route::apiResource('participants', ParticipantController::class);
