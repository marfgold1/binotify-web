<?php
// DO NOT CHANGE =====
namespace MusicApp;

use MusicApp\Controllers\AuthController;
use MusicApp\Controllers\LaguController;
use MusicApp\Core\Route;
// ===================

// Import controller here

// Define routes here
Route::get('/login', [AuthController::class, 'loginForm'])->name('auth.login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'registerForm'])->name('auth.register');
Route::post('/register', [AuthController::class, 'register']);
Route::group('/lagu', function() {
    Route::get('/tambah', [LaguController::class, 'tambahForm'])->name('lagu.tambah');
    Route::post('/tambah', [LaguController::class, 'tambah']);
    Route::get('/detail/:id', [LaguController::class, 'detail'])->name('lagu.detail');
});
?>