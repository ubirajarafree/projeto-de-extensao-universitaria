<?php

namespace Backend;

class Router
{

    private $routes = [];
    private $middlewares = [];

    public function __construct()
    { /*echo "Router instanciado\n";*/
    }

    // Adiciona uma nova rota
    public function add($route, $controller, $method, $httpMethod = 'GET', $middleware = null)
    {
        $this->routes[] = [
            'route' => $route,
            'controller' => $controller,
            'method' => $method,
            'httpMethod' => $httpMethod,
            'middleware' => $middleware,
        ];
    }

    // Adiciona uma nova rota POST
    public function post($route, $controllerMethod, $middleware = null)
    {
        $this->add($route, $controllerMethod[0], $controllerMethod[1], 'POST', $middleware);
    }

    public function middleware($route, $middleware)
    {
        $this->middlewares[$route] = $middleware;
    }

    public function run()
    {
        $url = isset($_GET['url']) ? '/' . $_GET['url'] : '/';
        $requestMethod = $_SERVER['REQUEST_METHOD'];

        foreach ($this->routes as $route) {
            $routePattern = preg_replace('/\{(\w+)\}/', '([^/]+)', $route['route']);
            if (preg_match('#^' . $routePattern . '$#', $url, $matches) && $route['httpMethod'] === $requestMethod) {
                $controller = $route['controller'];
                $method = $route['method'];

                if (class_exists($controller)) {
                    $controllerInstance = new $controller();

                    // Executa o middleware, se houver
                    if ($route['middleware']) {
                        $middleware = new $route['middleware']();
                        return $middleware->handle($_REQUEST, function ($request) use ($controllerInstance, $method, $matches) {
                            return $controllerInstance->$method(...array_slice($matches, 1));
                        });
                    }

                    return $controllerInstance->$method(...array_slice($matches, 1));
                } else {
                    echo "Classe $controller não encontrada\n";
                }
                return;
            }
        }
        echo "404 - Not Found\n";
    }
}
