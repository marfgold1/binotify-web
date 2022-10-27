<?php
namespace MusicApp\Controllers;

use MusicApp\Core\Controller;
use MusicApp\Models\User;

use function MusicApp\Core\back;
use function MusicApp\Core\route;
use function MusicApp\Core\set;
use function MusicApp\Core\view;

class AuthController extends Controller {
    public function loginForm() {
        view('auth.login');
    }

    public function login() {
        $flash = ([
            'errors' => [],
            'values' => [
                'username' => $_POST['username'] ?? '',
                'password' => $_POST['password'] ?? ''
            ]
        ]);
        if (!isset($_POST['username']))
            $flash['errors']['username'] = 'Username is required';
        if (!isset($_POST['password']))
            $flash['errors']['password'] = 'Password is required';
        if (count($flash['errors']) > 0) {
            back()->with($flash);
            return;
        }
        $users = User::find('username = ?', [$_POST['username']], 'LIMIT 1');
        if ($users) {
            $user = $users[0];
            if (password_verify($_POST['password'], $user->password)) {
                set('user', $user);
                route('home');
            } else {
                $flash['errors']['password'] = 'Password is incorrect.';
                back()->flash($flash);
            }
        } else {
            $flash['errors']['username'] = 'Username is not found!';
            back()->flash($flash);
        }
    }
}

?>