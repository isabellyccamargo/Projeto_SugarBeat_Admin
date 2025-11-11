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

            echo "<pre>🚨 DEBUG DE ROTA CASADA 🚨\n";
            echo "URI (Correta): **" . $uri . "**\n";
            echo "Controller Casado: **" . $controllerName . "**\n";
            echo "Método Casado: **" . $methodName . "**\n";
            echo "Argumentos Casados: " . print_r($args, true);
            echo "\n</pre>";

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
        // 1. Obtém o caminho da URL (ex: /sugarbeat_admin/produto/historico)
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        // 2. Obtém o caminho do script (ex: /sugarbeat_admin)
        $basePath = dirname($_SERVER['SCRIPT_NAME']);
        
        // Se o basePath não for apenas '/' ou '\', remove-o da URI
        if ($basePath !== '/' && $basePath !== '\\') {
            // Garante que o basePath (ex: /sugarbeat_admin) seja removido da URI
            $uri = str_replace($basePath, '', $uri);
        }

        // 3. Remove barras extras no início ou fim
        $uri = trim($uri, '/');

        return $uri;
    }
}
