<?php

use App\Http\Controllers\CompanyController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SessionController;
use App\Models\Company;
use Illuminate\Support\Facades\Route;

// devolver la vista de welcome en home
Route::get('/', function () {
    return view('home');
});
// devolver la vista mi-area en shifts
Route::get('/shifts', function () {
    return view('mi-area');
})->middleware('auth');
// devolver la vista about en about
Route::get('/about-us', function () {
    return view('about-us');
});
// devolver la vista contact en contact
Route::get('/contact', function () {
    return view('contact');
});

Route::get('/login', [SessionController::class, 'create'])->middleware('guest')->name('login');
Route::post('/login', [SessionController::class, 'store'])->middleware('guest');
Route::post('/logout', [SessionController::class, 'destroy'])->middleware('auth');

Route::get('/register/', [UserController::class, 'create'])->middleware('auth');
Route::post('/register/', [UserController::class, 'store'])->middleware('auth');

//no hay company hay q crear->se llama a company controller
Route::get('/register-company', [CompanyController::class, 'create'])->middleware('guest');
Route::post('/register-company', [CompanyController::class, 'store'])->middleware('guest');

