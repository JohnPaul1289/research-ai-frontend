<?php

class Router {
    private array $routes = [];
    private array $middlewares = [];

    public function addMiddleware(callable $callback): void {
        $this->middlewares[] = $callback;
    }

    public function get(string $path, callable $callback): void {
        $this->routes['GET'][$path] = $callback;
    }

    public function post(string $path, callable $callback): void {
        $this->routes['POST'][$path] = $callback;
    }

    public function dispatch(string $uri, string $method): void {
        $parsedUrl = parse_url($uri);
        $path = $parsedUrl['path'] ?? '/';
        
        // Strip the subdirectory path up to 'public' for XAMPP
        $pos = strpos($path, '/public');
        if ($pos !== false) {
            $path = substr($path, $pos + 7);
        }
        if ($path === '') {
            $path = '/';
        }

        // Run all middlewares before checking routes
        foreach ($this->middlewares as $middleware) {
            call_user_func($middleware, $path);
        }

        if (isset($this->routes[$method][$path])) {
            call_user_func($this->routes[$method][$path]);
        } else {
            http_response_code(404);
            echo "404 Not Found";
        }
    }
}
