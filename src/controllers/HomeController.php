<?php
namespace MusicApp\Controllers;

use MusicApp\Core\Controller;
use MusicApp\Models\User;

use function MusicApp\Core\back;
use function MusicApp\Core\send;
use function MusicApp\Core\view;

class HomeController extends Controller {
    public function index() {
        send(__DIR__ . '/HomeController.php');
    }
    public function get($id) {
        $user = User::get($id);
        if ($user === null) {
            back()->withErrors(['user' => 'User not found']);
        } else {
            view('auth.login', ['user' => $user])->with(['sess' => 'anjay']);
        }
    }
}
?>