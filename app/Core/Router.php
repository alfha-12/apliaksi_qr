<?php
namespace App\Core;

class Router
{
    private $routes = [];

    public function get(string $path, string $handler)
    {
        $this->routes['GET'][$path] = $handler;
    }

    public function post(string $path, string $handler)
    {
        $this->routes['POST'][$path] = $handler;
    }

    public function dispatch()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        
        // Remove the application directory when it runs under XAMPP.
        $config = require __DIR__ . '/../../config/app.php';
        $basePath = rtrim(parse_url($config['url'], PHP_URL_PATH) ?: '', '/');
        $applicationPath = preg_replace('#/public$#', '', $basePath);
        foreach (array_filter([$basePath, $applicationPath]) as $path) {
            if ($uri === $path || strpos($uri, $path . '/') === 0) {
                $uri = substr($uri, strlen($path)) ?: '/';
                break;
            }
        }
        $uri = rtrim($uri, '/') ?: '/';

        if (isset($this->routes[$method][$uri])) {
            $this->callHandler($this->routes[$method][$uri]);
            return;
        }

        // Try pattern matching for dynamic routes
        foreach ($this->routes[$method] ?? [] as $route => $handler) {
            $pattern = preg_replace('/\{([a-zA-Z_]+)\}/', '([^/]+)', $route);
            $pattern = "#^{$pattern}$#";
            
            if (preg_match($pattern, $uri, $matches)) {
                array_shift($matches);
                $this->callHandler($handler, $matches);
                return;
            }
        }

        http_response_code(404);
        echo "<h1>404 - Halaman Tidak Ditemukan</h1>";
        echo "<p>URI: {$uri}</p>";
    }

    private function callHandler(string $handler, array $params = [])
    {
        list($controllerName, $method) = explode('@', $handler);
        $controllerClass = "App\\Controllers\\{$controllerName}";
        
        if (!class_exists($controllerClass)) {
            die("Controller tidak ditemukan: {$controllerClass}");
        }
        
        $controller = new $controllerClass();
        
        if (!method_exists($controller, $method)) {
            die("Method tidak ditemukan: {$method}");
        }
        
        call_user_func_array([$controller, $method], $params);
    }
}
