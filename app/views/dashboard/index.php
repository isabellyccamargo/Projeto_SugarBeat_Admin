<?php
// Este é o arquivo principal da sua área administrativa.
// É assumido que o Front Controller (index.php da raiz) já fez a checagem de login.

// Define a base URL para uso nos links (ajuste 'PROJETO_SUGARBEAT_ADMIN' se necessário)
$BASE_URL = '/PROJETO_SUGARBEAT_ADMIN';

// Definição dos itens do menu lateral (Sidebar)
$menu_items = [
    'Dashboard' => $BASE_URL . '/dashboard',
    'Produtos' => $BASE_URL . '/produtos',
    'Categorias' => $BASE_URL . '/categorias',
    'Usuários' => $BASE_URL . '/usuarios',
];

// O nome da página atual para destacar o item ativo no menu (ex: 'Produtos')
// Aqui, você usaria sua lógica de roteamento para obter o item ativo.
// Para simplificar, vou assumir 'Dashboard' como ativo por padrão.
$current_page = 'Dashboard'; 

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Administrativo</title>
    
    <link rel="stylesheet" href="<?php echo $BASE_URL; ?>/app/views/dashboard/style.css">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

    <div class="dashboard-container">
        
        <aside class="sidebar">
            <div class="logo-area">
                <div class="logo-circle"></div> 
            </div>
            
            <nav class="sidebar-nav">
                <?php foreach ($menu_items as $label => $url): ?>
                    <a 
                        href="<?php echo htmlspecialchars($url); ?>" 
                        class="nav-item <?php echo ($label === $current_page) ? 'active' : ''; ?>"
                    >
                        <?php echo htmlspecialchars($label); ?>
                    </a>
                <?php endforeach; ?>
            </nav>

            <a href="<?php echo $BASE_URL; ?>/logout" class="sidebar-logout">
                <i class="fas fa-arrow-left"></i> Sair
            </a>
        </aside>

        <main class="main-content">
            <h1>Visão Geral do Painel</h1>
            <p>Este é o conteúdo principal. Aqui entra a lógica do Dashboard, Produtos, etc.</p>
        </main>
    </div>

</body>
</html>