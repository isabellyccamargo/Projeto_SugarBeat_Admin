<?php

class Router
{
    protected $routes = [];

    /**
     * Adiciona uma nova rota ao mapeamento.
     * @param string $uri A URI amigável (ex: 'produto/cadastro').
     * @param string $controller O nome da classe do Controller (ex: 'ProdutoController').
     * @param string $method O método a ser chamado no Controller (ex: 'cadastro').
     * @param array $options Opções adicionais como middleware.
     */
    public function add(string $uri, string $controller, string $method, array $options = [])
    {
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
    // Router.php - Função dispatch()

    // app/config/Router.php

    public function dispatch()
    {
        // 1. Obtém a URI solicitada (limpa)
        $uri = $this->getCurrentUri();

        // VARIÁVEIS PARA ARMAZENAR A ROTA ENCONTRADA E OS ARGUMENTOS
        $matchedRoute = null;
        $args = [];

        // 2. Tenta encontrar a rota (Lógica de roteamento dinâmico permanece a mesma)
        if (array_key_exists($uri, $this->routes)) {
            // A) Rota exata encontrada (ex: /produto/cadastro)
            $matchedRoute = $this->routes[$uri];
        } else {
            // B) Tenta encontrar a rota com parâmetros (ex: /produto/listar/5)
            $uriParts = explode('/', $uri);

            if (count($uriParts) >= 2) {
                // Tenta primeiro a rota base (ex: 'produto')
                $baseUri = $uriParts[0];
                if (array_key_exists($baseUri, $this->routes)) {
                    $matchedRoute = $this->routes[$baseUri];
                    $args[] = $uriParts[1] ?? null;
                }

                // Tenta a rota com método (ex: 'produto/listar')
                if (count($uriParts) >= 3) {
                    $methodUri = $uriParts[0] . '/' . $uriParts[1];
                    if (array_key_exists($methodUri, $this->routes)) {
                        $matchedRoute = $this->routes[$methodUri];
                        $args[] = $uriParts[2] ?? null;
                    }
                }
            }
        }

        // Verifica se alguma rota foi encontrada
        if ($matchedRoute) {

            // 3. Cria o Controller usando Factory
            $controllerName = $matchedRoute['controller'];
            $methodName = $matchedRoute['method'];

            // Verifica se a classe existe
            if (class_exists($controllerName)) {

                // --- INJEÇÃO DE DEPENDÊNCIA (DI) USANDO FACTORIES ---
                $controller = null;

                // Tenta usar uma Factory específica (Padrão: ControllerName + 'Factory')
                $factoryName = $controllerName . 'Factory';

                if (class_exists($factoryName) && method_exists($factoryName, 'create')) {
                    // Usa a Factory estática para criar o Controller com dependências
                    $controller = $factoryName::create(); 
                } 
                
                // Se a Factory não for encontrada ou para Controllers sem DI (como AuthController)
                if ($controller === null) {
                    try {
                        // Tenta instanciar diretamente (se não tiver argumentos no construtor)
                        $controller = new $controllerName();
                    } catch (\ArgumentCountError $e) {
                        // Captura o erro se a Factory não foi criada para um Controller que exige DI
                        http_response_code(500);
                        die("Erro de Configuração: O Controller '{$controllerName}' requer dependências, mas sua Factory ('{$factoryName}') não foi definida ou encontrada.");
                    }
                }
                // --- FIM DA INJEÇÃO DE DEPENDÊNCIA ---

                if ($controller && method_exists($controller, $methodName)) {

                    // Executa o método do Controller, passando os argumentos
                    call_user_func_array([$controller, $methodName], $args);
                    return;
                }
            } else {
                error_log("Controller não encontrado: " . $controllerName);
            }
        }

        // 4. Se a rota não foi encontrada, exibe 404
        http_response_code(404);
        echo "<h1>404 - Página Não Encontrada</h1>";
    }
    /**
     * Extrai e limpa a URI da requisição.
     * @return string A URI limpa.
     */
    // Router.php - Dentro de protected function getCurrentUri(): string {

    protected function getCurrentUri(): string
    {
        // 1. Obtém a URI completa da requisição
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        // 2. Determina o caminho base do diretório (Ex: /sugarbeat_admin/)
        $basePath = dirname($_SERVER['SCRIPT_NAME']);

        // ... (restante da sua lógica de basePath) ...
        if ($basePath !== '\\' && $basePath !== '/') {
            $basePath = rtrim($basePath, '/') . '/';
        } else {
            $basePath = '/';
        }

        // 3. Remove o basePath da URI completa
        $uri = str_replace($basePath, '', $uri);

        // 4. Limpa barras iniciais e finais
        $uri = trim($uri, '/');



        return $uri;
    }
}
