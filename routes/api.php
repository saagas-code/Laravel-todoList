<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('unauthenticated', function(){
    return ['error' => 'Usuário não logado!'];
})->name('login');


Route::get('/users', [UserController::class, 'getAll']);
Route::get('/users/{id}', [UserController::class, 'getOne']);

//categories
Route::get('/categories', [CategoryController::class, 'getAll']);
Route::get('/categories/{id}', [CategoryController::class, 'getOne']);
Route::post('/categories', [CategoryController::class, 'create']);

Route::get('/tasks', [TaskController::class, 'getAll']);
Route::get('/tasks/{id}', [TaskController::class, 'getOne']);

Route::middleware('auth:sanctum')->post('/tasks', [TaskController::class, 'create']);

Route::post('/tasks/editar/{id}', [TaskController::class, 'edit']);
Route::post('/tasks/check/{id}', [TaskController::class, 'check']);

Route::post('/tasks/delete/{id}', [TaskController::class, 'delete']);


// SANCTUM
Route::post('/login', [LoginController::class, 'login']);
Route::middleware('auth:sanctum')->post('/request', [LoginController::class, 'accountRequest']);
Route::middleware('auth:sanctum')->get('/logout', [LoginController::class, 'logout']);
Route::post('/register', [LoginController::class, 'register']);




