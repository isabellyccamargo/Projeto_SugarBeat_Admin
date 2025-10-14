<?php

session_start();

// Define a constante da raiz para facilitar a inclusão de arquivos
define('ROOT_PATH', __DIR__);

// Inclui o núcleo do roteador
require_once ROOT_PATH . '/app/config/Router.php';
require_once ROOT_PATH . '/app/config/View.php'; // Helper para carregar views

// Inclui os controllers necessários
require_once ROOT_PATH . '/app/controller/AuthController.php';
require_once ROOT_PATH . '/app/controller/ProdutoController.php';
require_once ROOT_PATH . '/app/controller/CategoriaController.php';
require_once ROOT_PATH . '/app/controller/UsuarioController.php';

// --- Lógica de Autenticação Inicial ---

// Pega a URL solicitada (o Router::route() cuidará da limpeza, mas precisamos do caminho base)
$requestUrl = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
// Remove o prefixo do projeto ('/sugarbeat') para obter a rota limpa
$routePath = str_replace('/sugarbeat_admin', '', $requestUrl);

// Se a rota for a raiz ('/') ou estiver vazia, verifica o login e redireciona
if ($routePath === '/' || $routePath === '') {
    // Simula a verificação de login
    if (isset($_SESSION['user_logged']) && $_SESSION['user_logged'] === true) {
        // Logado: Redireciona para o Dashboard
        header('Location: /sugarbeat_admin/dashboard');
        exit;
    } else {
        // Não Logado: Redireciona para o Login
        header('Location: /sugarbeat_admin/login');
        exit;
    }
}

// --- Definição das Rotas (URLs Amigáveis) ---

$router = new Router();

// ROTA: /login (NÃO PRECISA DE AUTENTICAÇÃO)
$router->add('login', 'AuthController', 'login');

// ROTA: /logout
// Protegida para garantir que apenas usuários logados possam sair.
$router->add('logout', 'AuthController', 'logout', ['middleware' => 'AuthMiddleware']);

// ROTA: /dashboard
// Esta rota exige autenticação (middleware) - verifica se o usuário está logado antes de executar o controller
$router->add('dashboard', 'AuthController', 'dashboard', ['middleware' => 'AuthMiddleware']);

// ROTA: /produto (Listagem)
// Amigável: /produto -> app/views/produtos/ListagemProduto.php. Protegida por middleware.
$router->add('produto', 'ProdutoController', 'listar', ['middleware' => 'AuthMiddleware']);

// ROTA: /produto/cadastro (Cadastro)
// Amigável: /produto/cadastro -> app/views/produtos/CadastroProduto.php. Protegida por middleware.
$router->add('produto/cadastro', 'ProdutoController', 'cadastro', ['middleware' => 'AuthMiddleware']);

// --- Adicione mais rotas aqui, seguindo o padrão ---

// ROTA: /categorias (Listagem). Protegida por middleware.
$router->add('categoria', 'CategoriaController', 'listar', ['middleware' => 'AuthMiddleware']);

// ROTA: /categoria/cadastro (Cadastro)
// Amigável: /categoria/cadastro -> app/views/categoria/CadastroCategoria.php. Protegida por middleware.
$router->add('categoria/cadastro', 'CategoriaController', 'cadastro', ['middleware' => 'AuthMiddleware']);

// ROTA: /usuarios (Listagem). Protegida por middleware.
$router->add('usuario', 'UsuarioController', 'listar', ['middleware' => 'AuthMiddleware']);

// ROTA: /usuario/cadastro (Cadastro)
// Amigável: /usuario/cadastro -> app/views/usuario/CadastroUsuario.php. Protegida por middleware.
$router->add('usuario/cadastro', 'UsuarioController', 'cadastro', ['middleware' => 'AuthMiddleware']);


// --- Executa a Rota ---

$router->dispatch();

?>