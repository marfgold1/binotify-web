<?php
namespace MusicApp\Core;

class Redirect {
    public static function init() {
        function redirect(string $url) : Sessions {
            header("Location: $url");
            return new Sessions();
        }

        function back() : Sessions {
            if (has(Sessions::LAST_URL)) {
                header('Location: ' . get(Sessions::LAST_URL));
                Sessions::rollback();
            } else {
                header('Location: /');
            }
            return new Sessions();
        }

        function route(string $routeName, array $args=[]) : Sessions {
            Route::go($routeName, $args);
            return new Sessions();
        }

        function view(string $viewName, array $args=[]) : Sessions {
            View::render($viewName, $args);
            return new Sessions();
        }

        function send(string $filename) : Sessions {
            if(file_exists($filename)){
                // Rollback sessions (prevent multiple download)
                Sessions::rollback();
                // Get mimetype of the file
                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                header('Content-Type: ' . finfo_file($finfo, $filename));
                finfo_close($finfo);
                // Set referred filename and attachment disposition
                header('Content-Disposition: attachment; filename='.basename($filename));
                // Set file length for proper download
                header('Content-Length: ' . filesize($filename));
                // Cleanup and send file
                flush();
                readfile($filename);
            } else {
                throw new \Exception("File does not exist");
            }
            return new Sessions();
        }
    }
}
?>