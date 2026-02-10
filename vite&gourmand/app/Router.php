<?php

declare(strict_types=1);

namespace App;

final class Router
{
    private array $routes = [];

    public function get(string $pattern, array $handler): void
    {
        $this->add('GET', $pattern, $handler);
    }

    public function post(string $pattern, array $handler): void
    {
        $this->add('POST', $pattern, $handler);
    }

    private function add(string $method, string $pattern, array $handler): void
    {
        $regex = $this->patternToRegex($pattern);
        $this->routes[] = [$method, $pattern, $regex, $handler];
    }

    public function dispatch(string $method, string $uri): void
    {
        $path = parse_url($uri, PHP_URL_PATH) ?? '/';
        foreach ($this->routes as [$routeMethod, $pattern, $regex, $handler]) {
            if ($routeMethod !== $method) {
                continue;
            }
            if (preg_match($regex, $path, $matches)) {
                $params = array_filter(
                    $matches,
                    fn($k) => is_string($k),
                    ARRAY_FILTER_USE_KEY
                );
                [$class, $action] = $handler;
                $controller = new $class();
                $controller->$action(...array_values($params));
                return;
            }
        }

        http_response_code(404);
        view('pages/404');
    }

    private function patternToRegex(string $pattern): string
    {
        $pattern = rtrim($pattern, '/');
        if ($pattern === '') {
            $pattern = '/';
        }
        $regex = preg_replace('/\{(\w+)\}/', '(?P<$1>[^/]+)', $pattern);
        return '#^' . $regex . '/?$#';
    }
}
