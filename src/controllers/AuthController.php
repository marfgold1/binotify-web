<?php
namespace MusicApp\Controllers;

use MusicApp\Core\Controller;
use MusicApp\Core\Models\Validation;
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

    public function registerForm() {
        view('auth.register');
    }

    public function register() {
        $flash = ([
            'errors' => [],
            'values' => [
                'username' => $_POST['username'] ?? null,
                'email' => $_POST['email'] ?? null,
                'password' => $_POST['password'] ?? null
            ]
        ]);
        if (!isset($_POST['password_confirm']) || $_POST['password_confirm'] !== $_POST['password'])
            $flash['errors']['password_confirm'] = 'Password confirmation does not match';
        $user = new User();
        $user->set($flash['values']);
        $valid = $user->validate([
            'username' => [
                Validation::TYPE => Validation::T_USERNAME,
                Validation::REQUIRED,
                Validation::UNIQUE => User::class,
            ],
            'email' => [
                Validation::TYPE => Validation::T_EMAIL,
                Validation::REQUIRED,
                Validation::UNIQUE => User::class,
            ],
            'password' => [
                Validation::TYPE => Validation::T_STRING,
                Validation::REQUIRED,
                Validation::MIN => 8,
            ],
        ]);
        $flash['errors'] = array_merge($flash['errors'], $valid);
        $flash['values']['password_confirm'] = $_POST['password_confirm'] ?? null;
        if (count($flash['errors']) > 0) {
            back()->flash($flash);
            return;
        }
        $user->save();
        set('user', $user);
        route('home');
    }
}

?>