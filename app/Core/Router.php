<?php

declare(strict_types=1);

namespace App\Core;

final class Router
{
    private array $routes = [];
    private mixed $notFound = null;

    public function get(string $pattern, callable|array $handler): void
    {
        $this->add('GET', $pattern, $handler);
    }

    public function post(string $pattern, callable|array $handler): void
    {
        $this->add('POST', $pattern, $handler);
    }

    public function any(string $pattern, callable|array $handler): void
    {
        $this->add('GET', $pattern, $handler);
        $this->add('POST', $pattern, $handler);
    }

    public function add(string $method, string $pattern, callable|array $handler): void
    {
        $this->routes[] = [
            'method'  => strtoupper($method),
            'regex'   => $this->compile($pattern),
            'handler' => $handler,
        ];
    }

    public function fallback(callable|array $handler): void
    {
        $this->notFound = $handler;
    }

    /**
     * {slug} acepta cualquier segmento; {id:\d+} permite fijar el formato.
     */
    private function compile(string $pattern): string
    {
        $regex = preg_replace_callback(
            '#\{([a-zA-Z_]+)(?::([^}]+))?\}#',
            static function (array $m): string {
                $name = $m[1];
                $rule = $m[2] ?? '[^/]+';

                return '(?P<' . $name . '>' . $rule . ')';
            },
            $pattern
        );

        return '#^' . $regex . '$#u';
    }

    public function dispatch(Request $request): void
    {
        $path   = $request->path();
        $method = $request->method();

        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }

            if (preg_match($route['regex'], $path, $matches) !== 1) {
                continue;
            }

            $params = [];

            foreach ($matches as $key => $value) {
                if (!is_int($key)) {
                    $params[$key] = $value;
                }
            }

            $request->setParams($params);
            $this->run($route['handler'], $request, $params);

            return;
        }

        if ($this->notFound !== null) {
            $this->run($this->notFound, $request, []);

            return;
        }

        http_response_code(404);
        echo 'No encontrado';
    }

    private function run(callable|array $handler, Request $request, array $params): void
    {
        if (is_array($handler)) {
            [$class, $method] = $handler;
            $controller = new $class();
            $controller->$method($request, $params);

            return;
        }

        $handler($request, $params);
    }
}
