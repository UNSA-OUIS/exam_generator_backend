<?php

use App\Models\MasterKey;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth:sanctum', 'superadmin'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    });
});

Route::middleware([MasterKey::class])->group(function () {});
