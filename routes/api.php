<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\LibroController;;
use App\Http\Controllers\AutorController;

Route::get('/libros', [LibroController::class, 'index']);
Route::get('/libros/{id}', [LibroController::class, 'show']);
Route::post('/libros', [LibroController::class, 'store']);
Route::put('/libros/{id}', [LibroController::class, 'update']);
Route::delete('/libros/{id}', [LibroController::class, 'destroy']);
Route::get('/autores', [AutorController::class, 'index']);
Route::post('autores', [AutorController::class, 'store']);
Route::put('/autores/{id}', [AutorController::class, 'update']);  
Route::delete('/autores/{id}', [AutorController::class, 'destroy']);