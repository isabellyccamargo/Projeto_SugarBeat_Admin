<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SugarBeat - Painel de Gerenciamento</title>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="icon" type="image/png" href="../fotos/imgsite.jpg">
    <link href="https://fonts.googleapis.com/css2?family=Ancizar+Serif:ital,wght@0,300..900;1,300..900&family=Bitter:ital,wght@0,100..900;1,100..900&family=Caudex:ital,wght@0,400;0,700;1,400;1,700&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Marcellus&family=Merriweather:ital,opsz,wght@0,18..144,300..900;1,18..144,300..900&family=Noto+Serif:ital,wght@0,100..900;1,100..900&family=Padauk:wght@400;700&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <?php
    // Define qual é a URI atual (ex: "/sugarbeat_admin/dashboard")
    $current_uri = $_SERVER['REQUEST_URI'] ?? '';

    // Função auxiliar para verificar se a URI do link está na URI atual
    function is_active(string $link_uri, string $current_uri): string
    {
        // Verifica se a URL atual contém a URL do link (boa prática para sistemas com rotas)
        // Além disso, se a URI atual for exatamente o link, marque como ativo (melhor para o Dashboard)
        $is_active = (strpos($current_uri, $link_uri) !== false);

        // Se for o link do Dashboard e estiver na raiz, também considera ativo
        if ($link_uri === '/sugarbeat_admin/dashboard' && $current_uri === '/sugarbeat_admin/') {
            $is_active = true;
        }

        return $is_active ? 'menu__link--ativo' : '';
    }
    ?>

    <style>
        :root {
            --cor-primaria: #3b2500ff;
            /* marrom escuro */
            --cor-secundaria: #fff0d0ff;
            /* bege claro */
            --cor-terceira: rgb(253, 230, 182);
            /* tom intermediário */
            --TamanhoFont-Titulo: 60px;
        }

        /* ====== GERAL ====== */
        body {
            margin: 0;
            font-family: var(--fontfamily);
            background-color: var(--cor-secundaria);
        }

        .container {
            display: flex;
            min-height: 100vh;
        }

        /* ====== MENU LATERAL ====== */
        .sidebar {
            width: 250px;
            background-color: var(--cor-primaria);
            color: var(--cor-secundaria);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        /* ======= LOGO ======= */
        .logo {
            width: 50px;
            height: 50px;
            background-color: var(--cor-terceira);
            border-radius: 50%;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .logo__texto {
            color: var(--cor-secundaria);
            font-size: 1.5rem;
            font-weight: 700;
            font-family: var(--fontfamily);
            font-size: 20px;
        }

        .logo-container {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 10px 0;
            gap: 10px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        }

        .logo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .menu {
            display: flex;
            flex-direction: column;
            gap: 20px;
            margin-top: 20px;
            font-size: 20px;
            text-align: left;
            font-family: var(--fontfamily);
        }

        .menu a {
            color: var(--cor-terceira);
            text-decoration: none;
            padding: 12px 25px;
            display: block;
            font-weight: 500;
            transition: 0.2s;
        }

        .menu a:hover {
            color: rgba(255, 250, 237, 1);
        }

        .menu a.menu__link--ativo {
            background-color: rgb(248, 239, 218);
            color: var(--cor-primaria);
            font-weight: 700;
            padding-left: 30px;
        }

        .menu a.menu__link--ativo:hover {
            color: var(--cor-primaria);
            background-color: rgb(248, 239, 218);
            ;
        }

        .logout {
            display: flex;
            align-items: center;
            gap: 8px;
            color: var(--cor-terceira);
            text-decoration: none;
            padding: 15px 25px;
            border-top: 1px solid rgba(255, 255, 255, 0.2);
            font-weight: 500;
            font-size: 20px;
        }

        .logout:hover {
            color: rgba(255, 250, 237, 1);
        }

        /* ====== CONTEÚDO PRINCIPAL ====== */
        .main-content {
            flex-grow: 1;
            background-color: rgb(248, 239, 218);
            display: flex;
            flex-direction: column;
        }

        /* ====== TOPO ====== */
        .topbar {
            background-color: var(--cor-primaria);
            padding: 15px 25px;
            display: flex;
            justify-content: flex-end;
            align-items: center;
            height: 40px;

        }

        .topbar span {
            font-weight: bold;
            color: var(--cor-secundaria);
            font-size: 1.1rem;
        }

        /* ====== ÁREA DE CONTEÚDO DINÂMICO ====== */
        .content-area {
            flex-grow: 1;
            padding: 30px;
        }

        /* ====== RESPONSIVIDADE ====== */
        @media (max-width: 768px) {
            .container {
                flex-direction: column;
            }

            .sidebar {
                width: 100%;
                flex-direction: row;
                justify-content: space-around;
                padding: 10px 0;
            }

            .menu {
                flex-direction: row;
                gap: 20px;
                margin: 0;
            }

            .topbar {
                justify-content: center;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- ====== MENU LATERAL ====== -->
        <aside class="sidebar">

            <div>
                <div class="logo-container">
                    <div class="logo">
                        <img src="../fotos/imgsite.jpg" alt="Logo da Empresa">
                    </div>
                    <span class="logo__texto">SugarBeat Admin</span>
                </div>
                <nav class="menu">
                    <nav class="menu">
                        <a href="/sugarbeat_admin/dashboard" class="<?= is_active('/sugarbeat_admin/dashboard', $current_uri) ?>">Dashboard</a>
                        <a href="/sugarbeat_admin/produto" class="<?= is_active('/sugarbeat_admin/produto', $current_uri) ?>">Produtos</a>
                        <a href="/sugarbeat_admin/categoria" class="<?= is_active('/sugarbeat_admin/categoria', $current_uri) ?>">Categorias</a>
                        <a href="/sugarbeat_admin/usuario" class="<?= is_active('/sugarbeat_admin/usuario', $current_uri) ?>">Usuários</a>
                    </nav>
            </div>

            <a href="/sugarbeat_admin/logout" class="logout">
                ← Sair
            </a>
        </aside>

        <!-- ====== CONTEÚDO PRINCIPAL ====== -->
        <div class="main-content">
            <div class="topbar">
                <?php

                $nome = $_SESSION['user_nome'] ?? 'Visitante';
                echo "<span>Olá, $nome</span>";
                ?>
            </div>

            <div class="content-area">
                <?php
                if (isset($content)) {
                    echo $content;
                } else {
                    echo "<h2>Conteúdo não carregado</h2><p>Erro na renderização da View.</p>";
                }
                ?>
            </div>
        </div>
    </div>
</body>

</html>