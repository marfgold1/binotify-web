<?php
namespace MusicApp\Controllers;

use MusicApp\Core\Controller;
use function MusicApp\Core\view;

class DaftarUserController extends Controller {
    public function user() {
        view('user');
    }
}
?>