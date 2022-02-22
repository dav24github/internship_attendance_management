<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\GenerarPdfController;
use App\Http\Controllers\Api\AsistenciaController;
use App\Http\Controllers\Api\PracticanteController;
use App\Http\Controllers\Api\FaltaController;
use App\Http\Controllers\Api\PermisoController;
use App\Http\Controllers\Api\InactividadController;
use App\Http\Controllers\Api\HorarioController;

use Illuminate\Support\Facades\Route;


Route::group(['middleware' => 'api', 'prefix' => 'auth'], function ($router) {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('me', [AuthController::class, 'me']);
});

Route::group(['middleware' => 'api', 'namespace' => 'Api'], function ($router) {

    //Informe route
    Route::post('get-informe', [GenerarPdfController::class, 'getInforme']);
    Route::post('total-retraso-pdf', [GenerarPdfController::class, 'totalRetraso']);
    Route::post('dias-trabajo-nro-pdf', [GenerarPdfController::class, 'DiasTrabajados']);
    Route::post('asistencias-nro-pdf', [GenerarPdfController::class, 'nroAsistenciasPracticante']);
    Route::post('faltas-nro-pdf', [GenerarPdfController::class, 'nroFaltasPracticante']);
    Route::post('permisos-nro-pdf', [GenerarPdfController::class, 'nroPermisosPracticante']);

    //practicante route
    Route::get('findPracticanteHistorial', [PracticanteController::class, 'searchHistorial']);
    Route::get('findPracticante', [PracticanteController::class, 'search']);
    Route::get('get-practicantes', [PracticanteController::class, 'getPracticantes']);
    Route::get('get-historial-practicantes', [PracticanteController::class, 'indexHistorial']);
    Route::get('practicantes', [PracticanteController::class, 'index']); //index
    Route::get('practicantes/{id}', [PracticanteController::class, 'show']);
    Route::patch('practicantes/{id}', [PracticanteController::class, 'update']);
    Route::post('practicantes', [PracticanteController::class, 'store']);

    Route::get('faltas-info/{id}', [FaltaController::class, 'faltasPracticante']);
    Route::get('permisos-info/{id}', [PermisoController::class, 'permisosPracticante']);
    Route::get('asistencias-info/{id}', [AsistenciaController::class, 'asistenciasPracticante']);
    Route::get('practicante-info/{id}', [PracticanteController::class, 'showPracticante']);
    Route::get('faltas-nro/{id}', [FaltaController::class, 'nroFaltasPracticante']);
    Route::get('asistencias-nro/{id}', [AsistenciaController::class, 'nroAsistenciasPracticante']);
    Route::get('permisos-nro/{id}', [PermisoController::class, 'nroPermisosPracticante']);
    Route::get('dias-trabajo-nro/{id}', [AsistenciaController::class, 'DiasTrabajados']);
    Route::get('total-retraso/{id}', [AsistenciaController::class, 'totalRetraso']);

    //Inactividad route
    Route::post('add-inactividad', [InactividadController::class, 'inactividadStore']);
    Route::patch('inactividad-edit/{id}', [InactividadController::class, 'inactividadUpdate']);
    Route::delete('inactividad-delete/{id}', [InactividadController::class, 'inactividadDelete']);
    Route::get('inactividades/{id}', [InactividadController::class, 'show']); //index
    Route::get('get-inactividades', [InactividadController::class, 'getInactividades']);
    Route::post('inactividad-search-month', [InactividadController::class, 'InactividadMonth']);
    Route::get('findInactividad', [InactividadController::class, 'search']);

    //permiso route
    Route::post('add-permiso', [PermisoController::class, 'permisoStore']);
    Route::patch('permiso-edit/{id}', [PermisoController::class, 'permisoUpdate']);
    Route::delete('permiso-delete/{id}', [PermisoController::class, 'permisoDelete']);
    Route::get('get-permisos', [PermisoController::class, 'getPermisos']);
    Route::get('permiso/{id}', [PermisoController::class, 'permiso']);
    Route::get('findPermiso', [PermisoController::class, 'search']);
    Route::post('permiso-search-month', [PermisoController::class, 'permisoMonth']);
    Route::post('permiso-search-date', [PermisoController::class, 'permisoDate']);

    //horario route
    Route::get('findHorario', [HorarioController::class, 'search']);
    Route::get('get-horarios', [HorarioController::class, 'getHorarios']);
    Route::get('horarios', [HorarioController::class, 'index']); //index
    Route::post('horarios', [HorarioController::class, 'store']);
    Route::patch('horarios/{id}', [HorarioController::class, 'update']);
    Route::delete('horarios/{id}', [HorarioController::class, 'destroy']);
    Route::get('horario-info/{id}', [HorarioController::class, 'horarioInfo']);
    Route::get('horario-details/{id}', [HorarioController::class, 'horarioDetails']);
    Route::get('horario-details-more/{id}', [HorarioController::class, 'horarioDetailsMore']);
    Route::get('horario-details-info/{id}', [HorarioController::class, 'horarioDetailsInfo']);
    Route::get('Horario_details/{id}', [HorarioController::class, 'detailsHorario']);

    //asistencia route
    Route::get('findAsistenciaHistorial', [AsistenciaController::class, 'searchHistorial']);
    Route::get('findAsistencia', [AsistenciaController::class, 'search']);
    Route::get('get-asistencias', [AsistenciaController::class, 'getAsistencias']);
    Route::get('get-historial-asistencias', [AsistenciaController::class, 'indexHistorial']);

    Route::post('add-asistencia-manual', [AsistenciaController::class, 'asistenciaStoreManual']);
    Route::post('add-asistencia', [AsistenciaController::class, 'asistenciaStore']);
    // Route::get('asistencia-info/{id}','OrderController@asistenciaInfo');
    Route::delete('asistencia-delete/{id}', [AsistenciaController::class, 'asistenciaDelete']);
    Route::post('asistencia-search-date', [AsistenciaController::class, 'asistenciaDate']);
    Route::post('asistencia-search-month', [AsistenciaController::class, 'asistenciaMonth']);

    //retraso route
    Route::get('retrasos', [AsistenciaController::class, 'allRetrasos']);
    Route::post('retraso-date', [AsistenciaController::class, 'retrasoDate']);
    Route::post('retraso-month', [AsistenciaController::class, 'retrasoMonth']);

    //falta route
    Route::get('findFaltaHistorial', [FaltaController::class, 'searchHistorial']);
    Route::get('findFalta', [FaltaController::class, 'search']);
    Route::get('get-faltas', [FaltaController::class, 'getFaltas']);
    Route::get('get-historial-faltas', [FaltaController::class, 'indexHistorial']);

    Route::get('update-faltas', [FaltaController::class, 'faltasStore']);
    Route::delete('falta-delete/{id}', [FaltaController::class, 'faltaDelete']);
    Route::post('falta-search-date', [FaltaController::class, 'faltasDate']);
    Route::post('falta-search-month', [FaltaController::class, 'faltasMonth']);
    Route::get('historial-faltas', [FaltaController::class, 'historialFaltas']);

    //home page
    Route::get('today-asistencias', [AsistenciaController::class, 'todayAsistencias']);
    // Route::get('today-sell','OrderController@todaySell');
    // Route::get('today-due','OrderController@todayDue');
    // Route::get('today-expense','OrderController@todayExpense');
});