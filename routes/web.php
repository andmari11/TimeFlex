<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/shifts', function () {
    return view('mi-area');
});

Route::get('/about', function () {
    return view('about');
});
