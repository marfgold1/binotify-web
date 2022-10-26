<?php
namespace MusicApp\Core;

class Sessions {
    const LAST_URL = 'last_url';
    const CURRENT_URL = 'current_url';
    const FLASH = 'flash';
    public static bool $autoReset = true;
    private static string $rollbackLastUrl = '';

    public function __construct() {
        if (self::$autoReset) {
            remove(Sessions::FLASH);
            remove('success');
            remove('errors');
        }
    }

    public function with(mixed $data, string $prefix='success') {
        set($prefix, $data);
    }

    public function flash(mixed $msg) {
        set(Sessions::FLASH, $msg);
    }

    public function withErrors(mixed $data) {
        $this->with($data, 'errors');
    }

    public static function rollback() {
        if (static::$rollbackLastUrl !== '') {
            set(Sessions::CURRENT_URL, get(Sessions::LAST_URL));
            set(Sessions::LAST_URL, static::$rollbackLastUrl);
        } else {
            if (has(Sessions::LAST_URL)) {
                set(Sessions::CURRENT_URL, get(Sessions::LAST_URL));
                remove(Sessions::LAST_URL);
            } else {
                remove(Sessions::CURRENT_URL);
            }
        }
    }

    public static function init() {
        session_start();

        function has(string $key) {
            return isset($_SESSION[$key]);
        }
        function get(string $key) {
            return $_SESSION[$key];
        }
        function set(string $key, $value) {
            $_SESSION[$key] = $value;
        }
        function remove(string $key) {
            unset($_SESSION[$key]);
        }
        function getFlash() {
            return get('flash') ?? null;
        }
        function hasErrors(string $key='') {
            if ($key === '') {
                return has('errors');
            } else {
                return has('errors') && isset(get('errors')[$key]);
            }
        }
        function getErrors(string $key='') {
            if ($key === '') {
                return get('errors') ?? null;
            } else {
                return get('errors')[$key] ?? null;
            }
        }

        if (has(self::CURRENT_URL) && get(self::CURRENT_URL) !== $_SERVER['REQUEST_URI']) {
            if (has(self::LAST_URL))
                static::$rollbackLastUrl = get(self::LAST_URL);
            set(self::LAST_URL, get(self::CURRENT_URL));
        }
        set(self::CURRENT_URL, $_SERVER['REQUEST_URI']);
    }
}
?>