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
Route::apiResource('posts', PostController::class)->names('api.v1.posts');

Route::post('login', [LoginController::class, 'store'])->name('api.v1.login');

Route::get('prueba', function () {
    return config('services.cordersfree');
    $precision = 30;
    $x = 3.552713678800501e-15;
    $number = number_format($x, 30,'.', '');
    return bcdiv(round($number, 2), '1', 2);
    $days = [];
    $hours = [];
    $intervals = CarbonPeriod::days()->setTimezone('America/Panama')->create('2022-05-02 05:00:00', '2022-06-01 04:59:00');
    $intervals = CarbonInterval::hours()
                ->setTimezone('America/Panama')
                ->toPeriod(Carbon::parse('2022-06-10 00:00:00')->format('Y-m-d H:i'), Carbon::parse('2022-06-10 05:00:00')->format('Y-m-d H:i'));
    $dates = [];
    foreach ($intervals as $key => $date) {
        $dates[] = $date->format('H');
    }
    $dates = array_map("unserialize", array_unique(array_map("serialize", $dates)));
    foreach ($dates as $date) {
        $array[$date]['cantidad']          = 0;
        $array[$date]['total']             = 0;
        $hours = $array;
    }
    return $hours;


    //Consulto la base de datos
    $regs = DB::table('users')
    ->orderBy('created_at', 'asc')
    ->get();
    //Agrupo los registros por hora
    $regs = $regs->groupBy(function($reg){
        return Carbon::parse($reg->created_at)->setTimezone('America/Panama')->format('H');
        //return Carbon::parse($reg->created_at)->setTimezone('America/Panama')->format('l');
    });
    //return $regs;
    //Sumo los registros por hora
    $data = [];
    //foreach ($hours as $key0 => $hour) {
        foreach ($regs as $key => $value) {
            $hours[$key]['total'] = $value->sum('id');
            $hours[$key]['cantidad'] = $value->count();
        }
    //}
    return (array)$hours;
});
