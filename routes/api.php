<?php

use App\Http\Controllers\Api\SertifikatController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
| All routes here are stateless and prefixed with /api automatically.
| Consumed by the external profiling-siswa-web system.
*/

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/sertifikat', [SertifikatController::class, 'index']);
});
