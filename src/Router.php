<?php
declare(strict_types=1);

namespace Baka;

/**
 * Minimal router. Supports {param} placeholders.
 * On no-match it renders the ghost 404 page.
 */
final class Router
{
    /** @var array<int, array{method:string, pattern:string, handler:callable|array}> */
    private array $routes = [];

    public function get(string $pattern, callable|array $handler): void
    {
        $this->add('GET', $pattern, $handler);
    }

    public function post(string $pattern, callable|array $handler): void
    {
        $this->add('POST', $pattern, $handler);
    }

    private function add(string $method, string $pattern, callable|array $handler): void
    {
        $this->routes[] = compact('method', 'pattern', 'handler');
    }

    public function dispatch(): void
    {
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $uri = rawurldecode(parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/');
        $uri = '/' . trim($uri, '/');
        if ($uri === '/') {
            $uri = '/';
        }

        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }
            $regex = $this->toRegex($route['pattern']);
            if (preg_match($regex, $uri, $m)) {
                $params = array_filter($m, 'is_string', ARRAY_FILTER_USE_KEY);
                $this->invoke($route['handler'], $params);
                return;
            }
        }

        $this->notFound();
    }

    private function toRegex(string $pattern): string
    {
        $regex = preg_replace('#\{([a-zA-Z_][a-zA-Z0-9_]*)\}#', '(?<$1>[^/]+)', $pattern);
        return '#^' . $regex . '$#';
    }

    private function invoke(callable|array $handler, array $params): void
    {
        if (is_array($handler)) {
            [$class, $action] = $handler;
            $handler = [new $class(), $action];
        }
        $result = $handler($params);
        if (is_string($result)) {
            echo $result;
        }
    }

    public function notFound(): void
    {
        http_response_code(404);
        echo View::render('pages/404', ['title' => '404 — Ghost Ate This Page']);
    }
}
