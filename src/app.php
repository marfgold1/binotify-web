<?php
// Entry point for the application - bootstrapper
// DO NOT CHANGE EVERYTHING IN THIS FILE!!!
declare(strict_types=1);
namespace MusicApp;

use MusicApp\Core\Route;

spl_autoload_register(function ($class_name) {
    $incl_path = explode('\\', $class_name);
    array_shift($incl_path);
    $file_name = array_pop($incl_path);
    $incl_path = array_map(fn($x) => strtolower($x), $incl_path);
    $incl_path = implode('/', $incl_path);
    include_once __DIR__ . '/' . $incl_path . '/' . $file_name . '.php';
});

include_once __DIR__ . '/core/controller.php';
include_once __DIR__ . '/core/models/model.php';
include_once __DIR__ . '/core/route.php';
include_once __DIR__ . '/router.php';

Route::route();
?>