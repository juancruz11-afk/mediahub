<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\FileController;

Route::get('/', function () {
    return view('welcome');
});

// Agrupamos las rutas de la API bajo el middleware web para usar la protección CSRF y Rate Limiting
Route::middleware(['throttle:10,1'])->group(function () {
    // Rutas de Videos
    Route::post('/api/fetch-info', [MediaController::class, 'fetchInfo']);
    Route::post('/api/download/start', [MediaController::class, 'startDownload']);
    Route::get('/api/download/status/{jobId}', [MediaController::class, 'checkStatus']);
    
    // Rutas de Archivos
    Route::post('/api/file/convert', [FileController::class, 'uploadAndConvert']);
    Route::get('/api/file/status/{jobId}', [FileController::class, 'checkStatus']);
});