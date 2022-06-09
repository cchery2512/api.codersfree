<?php

use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\PostController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\RegisterController;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

Route::post('register', [RegisterController::class, 'store'])->name('api.v1.register');

/*
Route::get('categories', [CategoryController::class, 'index'])->name('api.v1.categories.index');
Route::post('categories', [CategoryController::class, 'store'])->name('api.v1.categories.store');
Route::get('categories/{category}', [CategoryController::class, 'show'])->name('api.v1.categories.show');
Route::put('categories', [CategoryController::class, 'update'])->name('api.v1.categories.update');
Route::delete('categories', [CategoryController::class, 'destroy'])->name('api.v1.categories.destroy');*/

Route::apiResource('categories', CategoryController::class)->names('api.v1.categories');
Route::apiResource('posts', PostController::class)->names('api.v1.posts');

Route::post('login', [LoginController::class, 'store'])->name('api.v1.login');

Route::get('prueba', function () {
    //Consulto la base de datos
    $regs = DB::table('users')
    ->orderBy('created_at', 'asc')
    ->get();
    //Agrupo los registros por hora
    $regs = $regs->groupBy(function($reg){
        return date('H',strtotime($reg->created_at));
    });
    //Sumo los registros por hora
    foreach ($regs as $key => $value) {
        $reg[$key] = $value->sum('id');
    }
    return $reg;
});