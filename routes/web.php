<?php

use App\Http\Controllers\CompanyController;
use App\Http\Controllers\FastApiController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\Schedules\ScheduleController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\Sections\SectionController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\Users\UserController;
use App\Http\Middleware\HistorialAccesosMiddleware;
use Illuminate\Support\Facades\Route;

// devolver la vista de welcome en home
Route::get('/', function () {
    return view('home');
});

// devolver la vista mi-area en my-area
Route::get('/menu', [MenuController::class, 'index'])->middleware('auth');
Route::get('/menu/{id}', [MenuController::class, 'indexAdmin'])->middleware('auth');

Route::get('/fastapi-schedule', [FastApiController::class, 'sendSchedule']);
Route::get('/fastapi-stats', [FastApiController::class, 'sendStats']);
Route::post('/fastapi-schedule', [FastApiController::class, 'receiveSchedule']);
Route::post('/fastapi-stats', [FastApiController::class, 'receiveStats']);

Route::get('/equipo', [TeamController::class, 'index'])->middleware('auth');
Route::get('/equipo/{id}', [TeamController::class, 'indexAdminTeam'])->middleware('auth');

Route::get('/search', SearchController::class);


// devolver la vista about en about
Route::get('/about-us', function () {
    return view('about-us');
});
// devolver la vista contact en contact
Route::get('/contact', function () {
    return view('contact');
});

Route::get('/ayuda', function () {
    return view('ayuda');
})->middleware(HistorialAccesosMiddleware::class)->name('Ayuda');

Route::get('/horario', [ScheduleController::class, 'index'])->middleware('auth');
Route::get('/horario/{id}', [ScheduleController::class, 'show'])->middleware('auth');
Route::get('/horario/{id_schedule}/turno/{id_shift}', [ScheduleController::class, 'showShift'])->middleware('auth');

Route::get('/horario/personal/{id}', [ScheduleController::class, 'showPersonal'])->middleware('auth');
Route::get('/horario/personal/{id_schedule}/turno/{id_shift}', [ScheduleController::class, 'showPersonalShift'])->middleware('auth');

Route::get('/stats', [ScheduleController::class, 'stats'])->middleware('auth');

Route::get('forms', function (){
    return view('forms');
});

Route::get('/login', [SessionController::class, 'create'])->middleware('guest')->name('login');
Route::post('/login', [SessionController::class, 'store'])->middleware('guest');
Route::post('/logout', [SessionController::class, 'destroy'])->middleware('auth');

Route::get('/users/{id}/edit', [UserController::class, 'edit'])->middleware('auth');
Route::patch('/users/{id}/edit', [UserController::class, 'update'])->middleware('auth');
Route::delete('/users/{id}/delete', [UserController::class, 'destroy'])->middleware('auth');

//no hay company hay q crear->se llama a company controller
Route::get('/register-company', [CompanyController::class, 'create'])->middleware('guest');
Route::post('/register-company', [CompanyController::class, 'store'])->middleware('guest');

Route::get('/register-user/', [UserController::class, 'create'])->middleware('auth');
Route::post('/register-user/', [UserController::class, 'store'])->middleware('auth');

Route::get('/register-section/', [SectionController::class, 'create'])->middleware('auth'); //CAMBIAR CONTROLLER
Route::post('/register-section/', [SectionController::class, 'store'])->middleware('auth');

Route::get('/sections/{id}/edit', [SectionController::class, 'edit'])->middleware('auth');
Route::patch('/sections/{id}/edit', [SectionController::class, 'update'])->middleware('auth');
Route::delete('/sections/{id}/delete', [SectionController::class, 'destroy'])->middleware('auth');

Route::get('/unread-notifications', function () {
    return auth()->user()->unreadNotifications->count();
})->middleware('auth');
