<?php

use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('order')->group(function () {
    Route::post('/step1', [OrderController::class, 'step1']);
    Route::post('/step2', [OrderController::class, 'step2']);
    Route::post('/step3', [OrderController::class, 'step3']);
    Route::post('/get-restaurants-by-meal', [OrderController::class, 'getRestaurantsByMeal']);
    Route::post('/get-deshes-by-order', [OrderController::class, 'getListFoodsByOrder']);
    Route::post('/preview', [OrderController::class, 'getPreviewOrder']);
});
