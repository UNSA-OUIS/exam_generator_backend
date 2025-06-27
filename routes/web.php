<?php

use App\Http\Controllers\BlockController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MatrixController;
use App\Http\Controllers\MatrixDetailController;
use App\Http\Controllers\ProcessController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\RequireMasterKey;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        'message' => 'Welcome to the Exam Generator API',
    ]);
});

//Route::middleware([RequireMasterKey::class])->group(function () {
Route::post('/login', [LoginController::class, 'authenticate'])->name('login');
//});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/user', [UserController::class, 'show']);

    Route::apiResources([
        'processes' => ProcessController::class,
        'matrices' => MatrixController::class,
        'levels' => LevelController::class,
        'blocks' => BlockController::class,
        'matrix_details' => MatrixDetailController::class,
    ]);

    Route::post('/reset-password', [UserController::class, 'resetPassword']);
});
