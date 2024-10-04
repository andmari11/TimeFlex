<?php

use App\Http\Controllers\RegisteredUserController;
use App\Http\Controllers\SessionController;
use Illuminate\Support\Facades\Route;

// devolver la vista de welcome en home
Route::get('/', function () {
    return view('welcome');
});
// devolver la vista mi-area en shifts
Route::get('/shifts', function () {
    return view('mi-area');
});
// devolver la vista about en about
Route::get('/about', function () {
    return view('about');
});
// devolver la vista contact en contact
Route::get('/contact', function () {
    return "Contáctanos mandando un correo a admin@timeflex.es";
});

Route::get('/login', [SessionController::class, 'create'])->middleware('guest');
Route::post('/login', [SessionController::class, 'store'])->middleware('guest');
Route::post('/logout', [SessionController::class, 'destroy'])->middleware('auth');

Route::get('/register', [RegisteredUserController::class, 'create'])->middleware('guest');
Route::post('/register', [RegisteredUserController::class, 'storeCompany'])->middleware('guest');

