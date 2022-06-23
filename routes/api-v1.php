<?php

use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\PostController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\RegisterController;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\DB;

Route::post('register', [RegisterController::class, 'store'])->name('api.v1.register');
Route::get('user/{id}', [RegisterController::class, 'show'])->name('api.v1.show');

/*
Route::get('categories', [CategoryController::class, 'index'])->name('api.v1.categories.index');
Route::post('categories', [CategoryController::class, 'store'])->name('api.v1.categories.store');
Route::get('categories/{category}', [CategoryController::class, 'show'])->name('api.v1.categories.show');
Route::put('categories', [CategoryController::class, 'update'])->name('api.v1.categories.update');
Route::delete('categories', [CategoryController::class, 'destroy'])->name('api.v1.categories.destroy');*/

Route::apiResource('categories', CategoryController::class)->names('api.v1.categories');
Route::apiResource('posts', PostController::class)->names('api.v1.posts')->middleware('auth');

Route::post('login', [LoginController::class, 'store'])->name('api.v1.login');

Route::get('prueba', function () {
    return auth()->user()->accessToken->access_token;
})->middleware('auth');
