<?php

use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(['auth:sanctum'])->group(function () {

    Route::post('/projects/list',[ProjectController::class,'list']); 
    Route::post('/projects/create',[ProjectController::class,'create']);  
    Route::post('/project/get',[ProjectController::class,'get']); 
    Route::post('/project/edit',[ProjectController::class,'edit']);

    Route::post('/task/create',[TaskController::class,'create']);
    Route::post('/task/start',[TaskController::class,'start']);
    Route::post('/task/pause',[TaskController::class,'pause']);
    Route::post('/task/setWorkUser',[TaskController::class,'setWorkUser']);
    Route::post('/task/done',[TaskController::class,'done']);
});