<?php
namespace MusicApp\Controllers;

use MusicApp\Core\Controller;
use MusicApp\Models\User;

use function MusicApp\Core\view;

class DaftarUserController extends Controller {
    public function user() {
        $users = User::find('', [], '');
        view('user', ['users' => $users]);
    }
}
?>