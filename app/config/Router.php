<?php

class Router {
    protected $routes = [];

    /**
     * Adiciona uma nova rota ao mapeamento.
     * @param string $uri A URI amigável (ex: 'produto/cadastro').
     * @param string $controller O nome da classe do Controller (ex: 'ProdutoController').
     * @param string $method O método a ser chamado no Controller (ex: 'cadastro').
     * @param array $options Opções adicionais como middleware.
     */
    public function add(string $uri, string $controller, string $method, array $options = []) {
        // Remove barras iniciais/finais e garante consistência
        $uri = trim($uri, '/');
        $this->routes[$uri] = [
            'controller' => $controller,
            'method' => $method,
            'options' => $options
        ];
    }

    /**
     * Despacha (executa) a rota correspondente à URL atual.
     */
    public function dispatch() {
        // 1. Obtém a URI solicitada (limpa)
        $uri = $this->getCurrentUri();

        // 2. Verifica se a rota existe
        if (array_key_exists($uri, $this->routes)) {
            $route = $this->routes[$uri];

            // 3. Verifica e executa Middlewares
            if (isset($route['options']['middleware'])) {
                $middleware = $route['options']['middleware'];
                if ($middleware === 'AuthMiddleware' && !isset($_SESSION['user_logged']) || $_SESSION['user_logged'] !== true) {
                    // Se a autenticação falhar, redireciona para login
                    header('Location: /sugarbeat_admin/login');
                    exit;
                }
                // Adicione mais lógicas de middleware aqui (ex: AdminMiddleware)
            }

            // 4. Cria o Controller e chama o método
            $controllerName = $route['controller'];
            $methodName = $route['method'];
            
            // Verifica se a classe existe (garantia de segurança)
            if (class_exists($controllerName)) {
                $controller = new $controllerName();
                if (method_exists($controller, $methodName)) {
                    $controller->$methodName();
                    return;
                }
            }
        }

        // 5. Se a rota não foi encontrada, exibe 404
        http_response_code(404);
        echo "<h1>404 - Página Não Encontrada</h1>";
    }

    /**
     * Extrai e limpa a URI da requisição.
     * @return string A URI limpa.
     */
    protected function getCurrentUri(): string {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        // Remove o prefixo do projeto e barras (ex: /sugarbeat/produto/cadastro -> produto/cadastro)
        $uri = str_replace('/sugarbeat_admin', '', $uri);
        $uri = trim($uri, '/');
        return $uri;
    }
}