<?php
ob_start();
if (!isset($data)) {
    $data = [
        'listaCategorias' => [
            (object)['id_categoria' => 1, 'nome_categoria' => 'BRIGADEIRO'],
            (object)['id_categoria' => 2, 'nome_categoria' => 'Categoria Padrão'],
            (object)['id_categoria' => 3, 'nome_categoria' => 'Bolos'],
        ],
        'pagina_atual' => 1,
        'total_paginas' => 1,
        'itens_por_pagina' => 8,
        'total_categorias' => 3,
        'termo_busca' => '' 
    ];
}

$listaCategorias = $data['listaCategorias'];
$paginaAtual = $data['pagina_atual'];
$totalPaginas = $data['total_paginas'];
$termoBusca = $data['termo_busca'] ?? ''; 
$appBaseUrl = '/sugarbeat_admin/categoria'; 
ob_end_flush();
?>

<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="/sugarbeat_admin/assets/css/listagemCategoria.css">
</head>

<div class="listagem-container">
    
    <header class="produtos__header">
        <h2>Categorias Registradas</h2>
        
        <div class="produtos__acoes">
            <a href="<?php echo $appBaseUrl; ?>/cadastro" class="btn__acao btn__adicionar">
                Adicionar novo +
            </a>
        </div>
    </header>

    <?php if (!empty($listaCategorias)): ?>
        <div class="table-responsive">
            <table class="produtos__tabela">
                <thead>
                    <tr>
                        <th style="width: 5%;">Id</th>
                        <th class="nome-coluna-centralizada">Nome</th>
                        <th class="produtos__acoes-col" style="width: 10%;">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($listaCategorias as $categoria): 
                        $id = $categoria->id_categoria ?? (method_exists($categoria, 'getIdCategoria') ? $categoria->getIdCategoria() : null);
                        $nome = $categoria->nome_categoria ?? (method_exists($categoria, 'getNomeCategoria') ? $categoria->getNomeCategoria() : 'Nome Indefinido');
                    ?>
                        <tr>
                            <td><?php echo htmlspecialchars($id); ?></td>
                            <td class="nome-celula-centralizada"><?php echo htmlspecialchars($nome); ?></td>
                            <td class="produtos__acoes-col">
                                <a href="<?php echo $appBaseUrl; ?>/editar/<?php echo htmlspecialchars($id); ?>" class="editar" title="Editar">
                                    <i class="fas fa-pencil-alt"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                
                <tfoot>
                    <tr>
                        <td colspan="3">
                            <div class="produtos__paginacao">
                                <span class="paginacao__info">
                                    (Página <?php echo $paginaAtual; ?>/<?php echo $totalPaginas; ?>)
                                </span>
                                
                                <div class="paginacao__botoes">
                                    <a href="?page=<?php echo max(1, $paginaAtual - 1); ?><?php echo !empty($termoBusca) ? '&busca=' . urlencode($termoBusca) : ''; ?>" class="<?php echo $paginaAtual <= 1 ? 'disabled-link' : ''; ?>">Ant</a>
                                    
                                    <span class="active-page-number"><?php echo $paginaAtual; ?></span>
                                    
                                    <a href="?page=<?php echo min($totalPaginas, $paginaAtual + 1); ?><?php echo !empty($termoBusca) ? '&busca=' . urlencode($termoBusca) : ''; ?>" class="<?php echo $paginaAtual >= $totalPaginas ? 'disabled-link' : ''; ?>">Seg</a>
                                </div>
                            </div>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    <?php else: ?>
        <p class="alerta-vazio" style="padding: 30px; text-align: center; color: var(--cor-primaria); background-color: var(--cor-secundaria); border-radius: 5px;">
            Nenhuma categoria encontrada. 
        </p>
    <?php endif; ?>
</div>