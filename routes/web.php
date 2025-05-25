<?php

use App\Http\Controllers\CompanyController;
use App\Http\Controllers\FastApiController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\Schedules\ScheduleController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\Sections\SectionController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\ShiftExchangeController;
use App\Http\Controllers\ShiftTypeController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\StatsController;
use App\Http\Controllers\AyudaController;
use App\Http\Controllers\FormsController;
use App\Http\Controllers\ExpectedHoursController;
use App\Http\Controllers\ScheduleStatsController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\UserNotificationsPreferencesController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\Users\UserController;
use App\Http\Controllers\ContactoController;
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

// devolver la vista contact en contact
Route::get('/estadisticas', function () {
    return view('estadisticas');
});

Route::get('/dashboard', function () {
    return view('dashboard');
});


Route::get('/employees-per-section', [StatsController::class, 'getEmployeesPerSection']);
Route::get('/shifthours-per-section-2025', [StatsController::class, 'getShiftsHoursPerSection2025']);
Route::get('/total-shifts-hours', [StatsController::class, 'getShiftsHours']);
Route::get('/satisfaction-per-section-per-month', [StatsController::class, 'satisfactionPerSectionPerMonth']);
Route::get('/satisfaccion', [StatsController::class, 'getSatisfaccion']);
Route::get('/total-employees', [StatsController::class, 'getTotalEmployees']);
Route::get('/total-shift-hours-accumulated', [StatsController::class, 'getTotalShiftHours']);
Route::get('/user/{id}/shift-distribution', [StatsController::class, 'getShiftDistribution']);
Route::get('/user/{id}/actual-vs-expected', [StatsController::class, 'getActualVsExpected']);
Route::get('/user-shift-exchanges', [StatsController::class, 'getUserShiftExchanges']);
Route::get('/user-holidays-evolution', [StatsController::class, 'getUserHolidaysEvolution']);
Route::get('/monthly-shift-satisfaction', [StatsController::class, 'getMonthlyShiftSatisfaction']);
Route::get('/satisfaction-user-vs-section', [StatsController::class, 'getMonthlySatisfactionComparison']);




Route::get('/ayuda', function () {
    return view('ayuda');
})->middleware(HistorialAccesosMiddleware::class)->name('Ayuda');

Route::get('/horario', [ScheduleController::class, 'index'])->middleware('auth');
Route::get('/estadisticashorario/{section}', [ScheduleStatsController::class, 'index']);
Route::get('/section-demand/{section}/{month}-{year}', [ScheduleStatsController::class, 'getDemandPerDay']);
Route::get('/section-holidays/{section}/{month}-{year}', [ScheduleStatsController::class, 'getHolidays']);
Route::get('/section-pending-holidays/{section}/{month}-{year}', [ScheduleStatsController::class, 'getPendingHolidays']);

Route::get('/formularios', [FormsController::class, 'index'])->middleware('auth')->name('forms.index');
Route::get('formularios/create', [FormsController::class, 'create'])->middleware('auth');
Route::get('/register-form/', [FormsController::class, 'create'])->middleware('auth');
Route::post('/register-form/', [FormsController::class, 'store'])->middleware('auth');
Route::delete('/formularios/{id}/delete', [FormsController::class, 'destroy'])->middleware('auth')->name('forms.destroy');
Route::get('/formularios/{id}/edit', [FormsController::class, 'edit'])->middleware('auth')->name('forms.edit');
Route::put('/formularios/{id}', [FormsController::class, 'update'])->middleware('auth')->name('forms.update');
Route::get('/formularios/{id}/show', [FormsController::class, 'show'])->middleware('auth')->name('forms.show');
Route::post('/formularios/{id}/submit', [FormsController::class, 'submit'])->middleware('auth')->name('forms.submit');
Route::post('/formularios/{id}/duplicar', [FormsController::class, 'duplicate'])->name('forms.duplicate');
Route::get('/formularios/respuestas', [FormsController::class, 'showAnswers'])->name('forms.answers');
Route::get('/formularios/respuestasUser', [FormsController::class, 'showAnswersUser'])->name('forms.answersuser');
Route::get('/formularios/{formId}/resultados', [FormsController::class, 'showResults'])->name('forms.showresults');
Route::get('/formularios/{id}/editar-respuestas', [FormsController::class, 'editResults'])->name('forms.editresults');
Route::put('/formularios/{id}/actualizar-respuestas', [FormsController::class, 'updateResults'])->name('forms.updateresults');

Route::get('/file/{id}/download', [FileController::class, 'download'])->name('file.download');
Route::get('/file/{id}/show', [FileController::class, 'show'])->name('file.show');

Route::post('horario/{id}/edit/shift-type/create', [ShiftTypeController::class, 'store'])->middleware('auth');
Route::get('horario/{id}/edit/shift-type/create', [ShiftTypeController::class, 'create'])->middleware('auth');
Route::get('/horario/{id}/edit/shift-type/{id_st}/edit', [ShiftTypeController::class, 'edit'])->middleware('auth');
Route::patch('/horario/{id}/edit/shift-type/{id_st}/edit', [ShiftTypeController::class, 'update'])->middleware('auth');
Route::delete('/horario/{id}/edit/shift-type/{id_st}/delete', [ShiftTypeController::class, 'destroy'])->middleware('auth');

Route::get('/horario-registrar', [ScheduleController::class, 'create'])->middleware('auth');
Route::post('/horario-registrar', [ScheduleController::class, 'store'])->middleware('auth');
Route::get('/horario/{id}/edit', [ScheduleController::class, 'edit'])->middleware('auth');
Route::patch('/horario/{id}/edit', [ScheduleController::class, 'update'])->middleware('auth');
Route::delete('/horario/{id}/delete', [ScheduleController::class, 'destroy'])->middleware('auth');
Route::get('/horario/{id}/optimize', [FastApiController::class, 'sendSchedule'])->middleware('auth');
Route::get('/horario/{id}/regenerate-shifts', [ScheduleController::class, 'regenerateShifts'])->middleware('auth');

Route::get('/horario/{id}', [ScheduleController::class, 'show'])->middleware('auth');
Route::get('/horario/{id_schedule}/turno/{id_shift}', [ScheduleController::class, 'showShift'])->middleware('auth');
Route::get('/horario/{id_schedule}/user/{id_user}', [ScheduleController::class, 'showUser'])->middleware('auth');

Route::get('/horario/personal/{id}', [ScheduleController::class, 'showPersonal'])->middleware('auth');
Route::get('/horario/personal/{id_schedule}/turno/{id_shift}', [ScheduleController::class, 'showPersonalShift'])->middleware('auth');

Route::get('/shift-exchange/{id_schedule}/turno/{id_shift_someone}', [ShiftExchangeController::class, 'select'])->middleware('auth');
Route::post('/shift-exchange', [ShiftExchangeController::class, 'createExchange'])->middleware('auth');
Route::post('/shift-assign', [ShiftExchangeController::class, 'assignShift'])->middleware('auth');
Route::post('/shift-exchange/cancel/{id}', [ShiftExchangeController::class, 'cancelExchange'])->middleware('auth');
Route::post('/shift-exchange/accept/{id}', [ShiftExchangeController::class, 'acceptExchange'])->middleware('auth');
Route::get('/shift-exchange/{id_schedule}/turno/{id_shift_someone}/{id_shift_mine}', [ShiftExchangeController::class, 'exchange'])->middleware('auth');
Route::post('/shift-exchange-admin', [ShiftExchangeController::class, 'createExchangeAdmin'])->middleware('auth');
Route::get('/shift-exchange/{id_schedule}/worker/{workerSelected_id}/turno/{id_shift_someone}/{id_shift_mine}', [ShiftExchangeController::class, 'selectAdmin'])->middleware('auth');
Route::get('/shift-exchange/{id_schedule}/worker/{workerSelected_id}/turno/{id_shift_someone}', [ShiftExchangeController::class, 'selectAssign'])->middleware('auth');


Route::get('/stats', [ScheduleController::class, 'stats'])->middleware('auth');


Route::get('forms', function (){
    return view('forms');
});

Route::get('/notificationspanel', [NotificationController::class, 'index'])->name('notifications.panel');
Route::post('/save-notifications-preferences', [UserNotificationsPreferencesController::class, 'update'])->middleware('auth');
Route::get('/get-notifications-preferences', [UserNotificationsPreferencesController::class, 'getPreferences'])->middleware('auth');


Route::post('/expected-hours', [ExpectedHoursController::class, 'storeOrUpdate']);
Route::get('/expected-hours/section', [ExpectedHoursController::class, 'getBySection']);
Route::post('/expected-hours/store-or-update', [ExpectedHoursController::class, 'storeOrUpdate']);

Route::post('/ayuda', [AyudaController::class, 'store'])->name('ayuda.store');

Route::post('/contact', [ContactoController::class, 'store'])->name('contact.store');

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

Route::get('/perfil', [UserController::class, 'profileEdit'])->middleware('auth')->name('profileEdit');
Route::patch('/perfil', [UserController::class, 'profileUpdate'])->name('profileUpdate');

Route::get('/register-section/', [SectionController::class, 'create'])->middleware('auth');
Route::post('/register-section/', [SectionController::class, 'store'])->middleware('auth');

Route::get('/sections/{id}/edit', [SectionController::class, 'edit'])->middleware('auth');
Route::patch('/sections/{id}/edit', [SectionController::class, 'update'])->middleware('auth');
Route::delete('/sections/{id}/delete', [SectionController::class, 'destroy'])->middleware('auth');

Route::get('/unread-notifications', [NotificationController::class, 'getUnreadNotifications'])->middleware('auth');

Route::get('/export-csv', [ExportController::class, 'export'])->middleware('auth');

