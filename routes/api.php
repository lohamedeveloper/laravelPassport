<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;


Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class,'register']);
Route::get('user/{id}', [AuthController::class, 'user']);
Route::post('edit/{id}', [AuthController::class, 'edit']);
Route::get('all', [AuthController::class, 'all']);
Route::delete('delete/{id}', [AuthController::class, 'delete']);
