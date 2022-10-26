<?php
namespace MusicApp\Core;

class View {
    public static function render($view, $data=[]) {
        $view = str_replace('.', '/', $view);
        $view = __DIR__ . '/../views/' . $view . '.php';
        if (file_exists($view)) {
            extract($data);
            require_once $view;
        } else {
            throw new \Exception("View file does not exist");
        }
    }
}
?>