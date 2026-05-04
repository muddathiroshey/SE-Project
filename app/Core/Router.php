<?php

namespace App\Core;

use App\Controllers\BidController;

class Router
{
    private array $routes = [];

    public function get(string $uri, array $action): void
    {
        $this->routes['GET'][$uri] = $action;
    }

    public function post(string $uri, array $action): void
    {
        $this->routes['POST'][$uri] = $action;
    }

    public function dispatch(string $requestUri, string $method): void
    {
        $path = parse_url($requestUri, PHP_URL_PATH);
        $action = $this->routes[$method][$path] ?? null;

        if (!$action) {
            http_response_code(404);
            echo "404 Not Found bro";
            return;
        }

        [$controller, $handler] = $action;
        $instance = new $controller();
        $instance->$handler();
    }

}
