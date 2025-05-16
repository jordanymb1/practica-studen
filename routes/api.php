<?php

use Illuminate\Http\Request;
use App\Http\Controllers\EstudianteController;
use App\Http\Controllers\ParaleloController;
use Illuminate\Support\Facades\Route;

/*Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');*/

Route::prefix('paralelo')->group(function(){
    Route::get('/index',[ParaleloController::class,'index']);
    Route::post('/store',[ParaleloController::class,'store']);
    Route::get('/show/{id}',[ParaleloController::class,'show']);
    Route::put('/update/{id}',[ParaleloController::class,'update']);
    Route::delete('/delete/{id}',[ParaleloController::class,'destroy']);
});

Route::prefix('student')->group(function(){
    Route::get('/index',[EstudianteController::class,'index']);
    Route::post('/store',[EstudianteController::class,'store']);
    Route::get('/show/{id}',[EstudianteController::class,'show']);
    Route::put('/update/{id}',[EstudianteController::class,'update']);
    Route::delete('/delete/{id}',[EstudianteController::class,'destroy']);
});