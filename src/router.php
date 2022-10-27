<?php
// DO NOT CHANGE =====
namespace MusicApp;

use MusicApp\Controllers\HomeController;
use MusicApp\Core\Route;
// ===================

// Import controller here

// Define routes here
Route::group('home', function() {
    Route::get('/', [HomeController::class, 'index']);
    Route::get('/about', function() {
        phpinfo();
    });
    Route::get('/:id', [HomeController::class, 'get']);
});

?>