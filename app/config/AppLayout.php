<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SugarBeat - Gerenciamento</title>
    <style>
        /* Estilos básicos para o layout */
        body { margin: 0; font-family: 'Inter', sans-serif; background-color: #f4f7f9; }
        .container { display: flex; min-height: 100vh; }
        .sidebar { 
            width: 250px; 
            background-color: #1a202c; /* Cinza escuro */
            color: #ffffff; 
            padding: 20px 0; 
            box-shadow: 2px 0 5px rgba(0,0,0,0.1); 
            display: flex; 
            flex-direction: column;
        }
        .logo { text-align: center; margin-bottom: 30px; font-size: 24px; font-weight: bold; color: #68d391; }
        .menu a { 
            display: block; 
            padding: 12px 20px; 
            text-decoration: none; 
            color: #a0aec0; 
            transition: background-color 0.2s, color 0.2s;
            border-left: 5px solid transparent;
        }
        .menu a:hover { 
            background-color: #2d3748; /* Cinza um pouco mais claro */
            color: #ffffff;
            border-left-color: #68d391; /* Verde de destaque */
        }
        .main-content { 
            flex-grow: 1; 
            padding: 30px; 
            background-color: #ffffff; 
            margin: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -2px rgba(0, 0, 0, 0.1);
        }
        .footer { margin-top: auto; padding: 20px; text-align: center; font-size: 0.8rem; color: #4a5568; border-top: 1px solid #e2e8f0; }
        /* Estilos responsivos */
        @media (max-width: 768px) {
            .sidebar { width: 100%; height: auto; padding-bottom: 0; }
            .container { flex-direction: column; }
            .main-content { margin: 10px; padding: 20px; }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Menu Lateral (Sidebar) -->
        <aside class="sidebar">
            <div class="logo">SugarBeat</div>
            <nav class="menu">
                <a href="/sugarbeat_admin/dashboard">Dashboard</a>
                <a href="/sugarbeat_admin/produto">Produtos</a>
                <a href="/sugarbeat_admin/categoria">Categorias</a>
                <a href="/sugarbeat_admin/usuario">Usuários</a>
                <a href="/sugarbeat_admin/logout" style="color: #fc8181;">Sair</a>
            </nav>
        </aside>

        <!-- Área de Conteúdo Principal -->
        <main class="main-content">
            <!-- O conteúdo da View específica será injetado aqui -->
            <?php 
            // Verifica se a variável $content foi definida (pelo renderWithLayout)
            if (isset($content)) {
                echo $content;
            } else {
                echo "<h2>Conteúdo não carregado</h2><p>Erro na renderização da View.</p>";
            }
            ?>
        </main>
    </div>
</body>
</html>
