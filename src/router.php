<?php
// DO NOT CHANGE =====
namespace MusicApp;

use MusicApp\Controllers\AuthController;
use MusicApp\Core\Route;
// ===================

// Import controller here

// Define routes here
Route::get('/login', [AuthController::class, 'loginForm'])->name('auth.login');
Route::post('/login', [AuthController::class, 'login']);
?>