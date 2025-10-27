<?php

ob_start();

session_set_cookie_params([
    'lifetime' => 0,          // 0 = Expira ao fechar o navegador
    'path' => '/',            // Válido em todo o site
    'domain' => '',           // Deixa o domínio padrão
    'secure' => false,        // Use 'true' se estiver em HTTPS
    'httponly' => true,       // Protege contra ataques XSS
    'samesite' => 'Lax'       // Boa prática de segurança
]);

// 2. Opcional: Define o tempo máximo de inatividade no SERVIDOR (em segundos)
//    Exemplo: 1440 segundos = 24 minutos.
ini_set('session.gc_maxlifetime', 1440);

session_start();

// Define a constante da raiz para facilitar a inclusão de arquivos
define('ROOT_PATH', __DIR__);

// =========================================================================
// 1. AUTOLOADER DE CLASSES (DEVE SER DEFINIDO ANTES DE USAR QUALQUER CLASSE)
// =========================================================================
spl_autoload_register(function ($class) {
    // Lista de diretórios que contêm as classes (ajuste conforme sua estrutura)
    $directories = [
        'controllers', // Para AuthController, ProdutoController, etc.
        'models',
        'repositories',
        'services',
        'config',
        'factories'
    ];

    $filename = $class . '.php';

    foreach ($directories as $dir) {
        // Tenta carregar a classe a partir de ROOT_PATH/app/{diretorio}/{Classe}.php
       $path = ROOT_PATH . "/app/{$dir}/{$filename}";

        if (file_exists($path)) {
            require_once $path;
            return;
        }
    }
});


// index.php (Seção 2. Lógica de Autenticação e Redirecionamento Inicial)

// Pega a URL solicitada (inclui o /sugarbeat_admin e a rota)
$requestUrl = $_SERVER['REQUEST_URI'];
// Usamos str_contains (PHP 8+) ou strpos (PHP 7+) para checar as rotas públicas

$isLoggedIn = isset($_SESSION['user_logged']) && $_SESSION['user_logged'] === true;

// 1. REGRA PRINCIPAL: Se o usuário NÃO está logado.
if (!$isLoggedIn) {
    // Se a URL não contém '/login' e não contém '/logout'
    if (!str_contains($requestUrl, '/login') && !str_contains($requestUrl, '/logout')) {
        // Redireciona para o login e impede qualquer acesso a rotas protegidas
        header('Location: /sugarbeat_admin/login');
        exit;
    }
} 
// 2. REGRA DE CONFORTO: Se o usuário ESTÁ logado.
else {
    // Se estiver logado E tentar acessar LOGIN ou a RAIZ
    if (str_contains($requestUrl, '/login') || $requestUrl === '/sugarbeat_admin/' || $requestUrl === '/sugarbeat_admin') {
        // Impede que um usuário logado fique na tela de login
        header('Location: /sugarbeat_admin/dashboard');
        exit;
    }
}


$router = new Router(); 

// 🚨 CORREÇÃO CRÍTICA 🚨
// ROTA RAIZ (Vazia): Mapeia a URL base ('') para o LOGIN. 
// O Usuário logado será desviado para o dashboard pelo Bloco 2.
$router->add('', 'AuthController', 'login'); 

// ROTA: /login
$router->add('login', 'AuthController', 'login');

// ROTA: /logout
$router->add('logout', 'AuthController', 'logout');

// ROTA: /dashboard e as demais (PROTEGIDAS - SEM MIDDLEWARE)
$router->add('dashboard', 'AuthController', 'dashboard');
$router->add('produto', 'ProdutoController', 'listar');
$router->add('produto/cadastro', 'ProdutoController', 'cadastro');
$router->add('categoria', 'CategoriaController', 'listar');
$router->add('categoria/cadastro', 'CategoriaController', 'cadastro');
$router->add('usuario', 'UsuarioController', 'listar');
$router->add('usuario/cadastro', 'UsuarioController', 'cadastro');


// --- Executa a Rota ---

$router->dispatch();


