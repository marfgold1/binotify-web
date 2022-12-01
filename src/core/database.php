<?php
namespace MusicApp\Core;

use PDO;

class Database extends PDO {
    protected static array $cache_config;

    public function __construct(array $conf) {
        parent::__construct($conf['dsn'], $conf['username'], $conf['password']);
        $this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public static function init() : void {
        static::$cache_config = [
            'dsn' => $_ENV["DB_NS"] ?? (
                "mysql:host=" . ($_ENV["DB_HOST"] ?? "localhost") .
                ";port=" . ($_ENV["DB_PORT"] ?? 3306) .
                ";dbname=" . ($_ENV["DB_NAME"] ?? "musicphpapp")
            ),
            'username' => $_ENV["DB_USER"] ?? "musicphpapp",
            'password' => $_ENV["DB_PASS"] ?? "musicenjoyer"
        ];
    }

    public static function get($config=null) : Database {
        if (!is_null($config)) {
            static::$cache_config = $config;
        }
        return new Database(static::$cache_config);
    }
}
Database::init();

?>