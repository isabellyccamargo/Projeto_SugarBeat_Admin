<?php

class Router
{
    protected $routes = [];

    public function add(string $uri, string $controller, string $method, array $options = [])
    {
        $uri = trim($uri, '/');
        $this->routes[$uri] = [
            'controller' => $controller,
            'method' => $method,
            'options' => $options
        ];
    }

    public function dispatch()
    {
        $uri = $this->getCurrentUri();

        $matchedRoute = null;
        $args = [];

        if (array_key_exists($uri, $this->routes)) {
            $matchedRoute = $this->routes[$uri];
        } else {
            $uriParts = explode('/', $uri);

            if (count($uriParts) >= 2) {
                $baseUri = $uriParts[0];
                if (array_key_exists($baseUri, $this->routes)) {
                    $matchedRoute = $this->routes[$baseUri];
                    $args[] = $uriParts[1] ?? null;
                }
                if (count($uriParts) >= 3) {
                    $methodUri = $uriParts[0] . '/' . $uriParts[1];
                    if (array_key_exists($methodUri, $this->routes)) {
                        $matchedRoute = $this->routes[$methodUri];
                        $args[] = $uriParts[2] ?? null;
                    }
                }
            }
        }

        if ($matchedRoute) {

            $controllerName = $matchedRoute['controller'];
            $methodName = $matchedRoute['method'];

            if (class_exists($controllerName)) {

                $controller = null;

                $factoryName = $controllerName . 'Factory';

                if (class_exists($factoryName) && method_exists($factoryName, 'create')) {
                    $controller = $factoryName::create(); 
                } 
                
                if ($controller === null) {
                    try {
                        $controller = new $controllerName();
                    } catch (\ArgumentCountError $e) {
                        http_response_code(500);
                        die("Erro de Configuração: O Controller '{$controllerName}' requer dependências, mas sua Factory ('{$factoryName}') não foi definida ou encontrada.");
                    }
                }

                if ($controller && method_exists($controller, $methodName)) {

                    call_user_func_array([$controller, $methodName], $args);
                    return;
                }
            } else {
                error_log("Controller não encontrado: " . $controllerName);
            }
        }

        http_response_code(404);
        echo "<h1>404 - Página Não Encontrada</h1>";
    }

    protected function getCurrentUri(): string
    {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        $basePath = dirname($_SERVER['SCRIPT_NAME']);

        if ($basePath !== '\\' && $basePath !== '/') {
            $basePath = rtrim($basePath, '/') . '/';
        } else {
            $basePath = '/';
        }

        $uri = str_replace($basePath, '', $uri);

        $uri = trim($uri, '/');

        return $uri;
    }
}
