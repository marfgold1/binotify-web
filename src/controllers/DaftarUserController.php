<?php
namespace MusicApp\Controllers;

use MusicApp\Core\Controller;
use MusicApp\Core\Database;
use MusicApp\Models\User;

use function MusicApp\Core\remove;
use function MusicApp\Core\view;

class DaftarUserController extends Controller {
    public function user() {
        view('user');
    }
}
?>