<?php
namespace MusicApp\Core;

use Closure;

class RouteItem {
    private array $path;
    private string $method;
    private array|Closure $callback;
    private string $name;

    public function __construct(string $path, string $method, array|callable $callback) {
        $this->path = array_filter(explode('/', $path));
        $this->method = $method;
        $this->callback = is_array($callback) ? $callback : Closure::fromCallable($callback);
    }

    public function name(string $name): RouteItem {
        $this->name = $name;
        return $this;
    }

    public function __get($name){
        return $this->$name;
    }

    public function invoke(...$args) {
        if (is_array($this->callback)) {
            $class = $this->callback[0];
            $class = new $class();
            return $class->{$this->callback[1]}(...$args);
        } else {
            return $this->callback->call($this, ...$args);
        }
    }

    public function isMatch(array &$requestPath) {
        if (count($this->path) !== count($requestPath))
            return false;
        if ($this->method !== $_SERVER['REQUEST_METHOD'] && $this->method !== 'ANY')
            return false;
        $args = [];
        $isMatching = true;
        foreach ($this->path as $p) {
            if ($p[0] == ':') {
                $args[substr($p, 1)] = urldecode(array_shift($requestPath));
            } else {
                if ($p !== array_shift($requestPath)) {
                    $isMatching = false;
                    break;
                }
            }
        }
        if ($isMatching)
            $this->invoke(...$args);
        return $isMatching;
    }

    public function getPath(array $args=[]): string {
        $path = [];
        foreach ($this->path as $p) {
            if ($p[0] == ':') {
                $p = $args[substr($p, 1)];
            }
            $path[] = $p;
        }
        return '/' . implode('/', $path);
    }
}

class Route {
    private static $routerList = [];
    private static $tempPrefix = '';

    public static function route () {
        $path = array_filter(explode('/', $_SERVER['REQUEST_URI']));
        $isMatched = false;
        foreach (static::$routerList as $router) {
            if ($router->isMatch($path)) {
                $isMatched = true;
                break;
            }
        }
        if (!$isMatched)
            http_response_code(404);
    }

    public static function go(string $routerName, array $args=[]) {
        foreach (static::$routerList as $router) {
            if ($router->name === $routerName) {
                header('Location: ' . $router->getPath($args));
                return;
            }
        }
        throw new \Exception("Router with name '$routerName' not found");
    }

    protected static function add(string $path, string $method, array|callable $callback) : RouteItem {
        $router = new RouteItem(static::$tempPrefix . $path, $method, $callback);
        static::$routerList[] = $router;
        return $router;
    }

    public static function get(string $path, array|callable $callback) : RouteItem {
        return static::add($path, 'GET', $callback);
    }
    
    public static function post(string $path, array|callable $callback) : RouteItem {
        return static::add($path, 'POST', $callback);
    }

    public static function any(string $path, array|callable $callback) : RouteItem {
        return static::add($path, 'ANY', $callback);
    }

    public static function put(string $path, array|callable $callback) : RouteItem {
        return static::add($path, 'PUT', $callback);
    }

    public static function delete(string $path, array|callable $callback) : RouteItem {
        return static::add($path, 'DELETE', $callback);
    }

    public static function patch(string $path, array|callable $callback) : RouteItem {
        return static::add($path, 'PATCH', $callback);
    }

    public static function group(string $prefix, callable $callback) {
        static::$tempPrefix = $prefix;
        $callback();
        static::$tempPrefix = '';
    }

    public static function resource(string $path, string $className, array $opts=[]) {
        $actions = ['index', 'create', 'show', 'edit', 'store', 'update', 'delete'];
        $urlpath = ['', '/create', '/:id', '/:id/edit', '', '/:id', '/:id'];
        $methods = ['GET', 'GET', 'GET', 'GET', 'POST', 'PUT', 'DELETE'];
        // frontend
        $only = $opts['only'] ?? [];
        foreach ($actions as $i => $action) {
            if (in_array($action, $only))
                self::{$methods[$i]}($path . $urlpath[$i], [$className, $action]);
        }
    }
}

?>