<?php

use App\Http\Controllers\BlockController;
use App\Http\Controllers\CollaboratorController;
use App\Http\Controllers\ConfinementBlockController;
use App\Http\Controllers\ConfinementController;
use App\Http\Controllers\ConfinementTextController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\ExamTextController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MasterController;
use App\Http\Controllers\MatrixController;
use App\Http\Controllers\MatrixDetailController;
use App\Http\Controllers\ModalityController;
use App\Http\Controllers\PDFController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\QuestionImportController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\RequireMasterKey;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        'message' => 'Welcome to the Exam Generator API',
    ]);
});

//Route::middleware([RequireMasterKey::class])->group(function () {
Route::post('/login', [LoginController::class, 'authenticate'])->name('login');
//});
Route::get('/exams/{exam_id}/master/{area}/pdf', [PDFController::class, 'generateMasterPdf']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/user', [UserController::class, 'show']);
    Route::get('/confinements/{confinement}/blocks', [ConfinementBlockController::class, 'byConfinement']);
    Route::get('/confinements/{confinement}/texts', [ConfinementTextController::class, 'byConfinement']);
    Route::get('confinements/{confinement}/export', [ConfinementController::class, 'exportBlocks']);
    Route::get('confinements/{confinement}/export/texts', [ConfinementController::class, 'exportTexts']);

    Route::get('questions', [QuestionController::class, 'index']);
    Route::get('questions/{id}', [QuestionController::class, 'show']);

    Route::apiResources([
        'modalities' => ModalityController::class,
        'matrices' => MatrixController::class,
        'levels' => LevelController::class,
        'blocks' => BlockController::class,
        'matrix_details' => MatrixDetailController::class,
        'confinements' => ConfinementController::class,
        'exams' => ExamController::class,
        'exam_texts' => ExamTextController::class,
        'confinement_blocks' => ConfinementBlockController::class,
        'confinement_texts' => ConfinementTextController::class,
        'collaborators' => CollaboratorController::class,
    ]);

    Route::post('/import-questions', [QuestionImportController::class, 'import']);
    Route::post('/reset-password', [UserController::class, 'resetPassword']);
    Route::post('/masters/generate', [MasterController::class, 'generate']);
});
