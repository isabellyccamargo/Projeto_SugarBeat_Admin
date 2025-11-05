<?php
$produtos = $produtos ?? [];
$mensagem_erro = $mensagem_erro ?? null;

$produtos = $produtos ?? [];
$mensagem_erro = $mensagem_erro ?? null;
$pagina_atual = $pagina_atual ?? 1;
$total_paginas = $total_paginas ?? 1;
$total_produtos = $total_produtos ?? 0;
$produtos_por_pagina = $produtos_por_pagina ?? 8;
$listaCategorias = $listaCategorias ?? [];

function formatarPreco($preco)
{
    return 'R$ ' . number_format((float)$preco, 2, ',', '.');
}

$filtro_ativo_display = null;
// ✅ VÁRIAVEL DO FILTRO: Captura o ID da categoria que está ativa (ou null se não houver)
$categoria_id_selecionada = filter_input(INPUT_GET, 'categoria', FILTER_VALIDATE_INT) ?: null;

// ✅ VÁRIAVEL DE BASE: String que conterá o parâmetro do filtro para ser adicionado aos links de paginação.
$base_url_params = '';

if (!empty($categoria_id_selecionada) && $categoria_id_selecionada !== 0) {
    
    // Constrói a string de filtro para a paginação
    $base_url_params = '&categoria=' . $categoria_id_selecionada;

    $nome_categoria_selecionada = null;
    $id_selecionado_str = (string)$categoria_id_selecionada;

    foreach ($listaCategorias as $c) {
        if ((string)$c->getIdCategoria() === $id_selecionado_str) {
            $nome_categoria_selecionada = $c->getNomeCategoria();
            break;
        }
    }

    if ($nome_categoria_selecionada) {
        // O link para remover o filtro deve apenas remover o parâmetro 'categoria', mantendo a página atual
        $filtro_ativo_display = [
            'tipo' => 'Categoria',
            'valor' => htmlspecialchars($nome_categoria_selecionada),
            'url_remover' => '/sugarbeat_admin/produto?page=' . $pagina_atual
        ];
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SugarBeat Admin - Produto</title>
    <link rel="stylesheet" href="/sugarbeat_admin/assets/css/listagemProduto.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Ancizar+Serif:ital,wght@0,300..900;1,300..900&family=Bitter:ital,wght@0,100..900;1,100..900&family=Caudex:ital,wght@0,400;0,700;1,400;1,700&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Marcellus&family=Merriweather:ital,opsz,wght@0,18..144,300..900;1,18..144,300..900&family=Noto+Serif:ital,wght@0,100..900;1,100..900&family=Padauk:wght@400;700&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<div class="produtos__container">
    <div class="produtos__header">
        <h2>Produtos Registrados</h2>
        <div class="produtos__acoes">
            <a href="/sugarbeat_admin/produto/cadastro" class="btn__acao btn__adicionar">Adicionar novo +</a>
            <button class="btn__acao btn__filtrar" id="btn-filtrar">Filtrar <span>&#9660;</span></button>

            <div class="dropdown-filtro" id="dropdown-filtro" style="display:none;">
                <div class="categoria" data-id="0">Todas</div>
                <?php foreach ($listaCategorias as $c): ?>
                    <div class="categoria" data-id="<?= htmlspecialchars($c->getIdCategoria()) ?>">
                        <?= htmlspecialchars($c->getNomeCategoria()) ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <?php if ($filtro_ativo_display): ?>
        <div class="filtro-ativo-container">
            <span class="filtro-chip">
                Filtrado por: <?= $filtro_ativo_display['valor'] ?>
                <a href="<?= $filtro_ativo_display['url_remover'] ?>" class="remover-filtro" title="Remover Filtro">
                    <i class="fa-solid fa-xmark"></i>
                </a>
            </span>
        </div>
    <?php endif; ?>

    <?php if ($mensagem_erro): ?>
        <div style="color: red; padding: 15px; background: #ffe0e0; border: 1px solid #ffb3b3; margin-bottom: 20px;">
            <?= $mensagem_erro ?>
        </div>
    <?php endif; ?>

    <table class="produtos__tabela">
        <thead>
            <tr>
                <th>Id</th>
                <th>Produto</th>
                <th>Estoque</th>
                <th>Status</th>
                <th>Categoria</th>
                <th>Preço</th>
                <th class="produtos__acoes-col" style="text-align:center;">Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($produtos)): ?>
                <tr>
                    <td colspan="7" style="text-align: center; padding: 20px; color: var(--cor-primaria); background-color: var(--cor-terceira);">Nenhum produto encontrado.</td>
                </tr>
            <?php else: ?>
                <?php
                foreach ($produtos as $produto):
                    $is_ativo = $produto->getAtivo() === '1';
                    $ativo_texto = $is_ativo ? 'Ativo' : 'Inativo';
                    $preco_formatado = formatarPreco($produto->getPreco());
                ?>
                    <tr>
                        <td>
                            <div class="id__circle">
                                <?= htmlspecialchars($produto->getIdProduto()) ?>
                            </div>
                        </td>
                        <td>
                            <div class="produto__info-completa">

                                <?php
                                // CORRIGIDO: O caminho da imagem deve usar o caminho completo (URL)
                                // Removendo a lógica desnecessária de manipulação de string para usar o caminho do objeto
                                $caminho_web = $produto->getImagem() ?? '/sugarbeat_admin/assets/img/placeholder.png'; // Usando placeholder se nulo
                                ?>
                                <img src="<?= htmlspecialchars($caminho_web) ?>" width="60" class="produto__img">

                                <span><?= htmlspecialchars($produto->getNome()) ?></span>
                            </div>
                        </td>
                        <td><?= htmlspecialchars($produto->getEstoque()) ?></td>
                        <td class="<?= !$is_ativo ? 'status-nao' : 'status-sim' ?>">
                            <?= $ativo_texto ?>
                        </td>
                        <td><?= htmlspecialchars($produto->getNomeCategoria()) ?></td>
                        <td><?= $preco_formatado ?></td>
                        <td class="produtos__acoes-col">
                            <?php
                            // PREPARAÇÃO DOS DADOS PARA PASSAR VIA GET
                            $query_data = http_build_query([
                                'id' => $produto->getIdProduto(),
                                'nome' => $produto->getNome(),
                                'estoque' => $produto->getEstoque(),
                                'ativo' => $produto->getAtivo(),
                                'categoria' => $produto->getIdCategoria(),
                                'preco' => $produto->getPreco(),
                                'imagem_path' => $produto->getImagem() // O caminho relativo da imagem
                            ]);
                            ?>
                            <a href="/sugarbeat_admin/produto/cadastro?<?= $query_data ?>"
                                title="Editar" class="editar">
                                <i class="fa-solid fa-pen"></i>
                            </a>

                        </td>

                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>

        <tfoot>
            <tr>
                <td colspan="7">
                    <div class="produtos__paginacao">
                        <span class="paginacao__info">
                            (Página <?= $pagina_atual ?>/<?= $total_paginas ?>)
                        </span>

                        <div class="paginacao__botoes">

                            <a href="?page=<?= max(1, $pagina_atual - 1) . $base_url_params ?>"
                                <?= $pagina_atual <= 1 ? 'disabled style="pointer-events: none; opacity: 0.7;"' : '' ?>>Ant</a>

                            <span class="active-page-number">
                                <?= $pagina_atual ?>
                            </span>


                            <a href="?page=<?= min($total_paginas, $pagina_atual + 1) . $base_url_params ?>"
                                <?= $pagina_atual >= $total_paginas ? 'disabled style="pointer-events: none; opacity: 0.7;"' : '' ?>>Seg</a>
                        </div>
                    </div>
                </td>
            </tr>
        </tfoot>
    </table>
</div>

<script>
    const btn = document.getElementById('btn-filtrar');
    const drop = document.getElementById('dropdown-filtro');

    // Alterna exibição do dropdown ao clicar no botão
    btn.addEventListener('click', () => {
        drop.style.display = (drop.style.display === 'none' || drop.style.display === '') ? 'block' : 'none';
    });

    // Fechar ao clicar fora
    document.addEventListener('click', (e) => {
        if (!btn.contains(e.target) && !drop.contains(e.target)) {
            drop.style.display = 'none';
        }
    });

    // Clique nas categorias
    document.querySelectorAll('.dropdown-filtro .categoria').forEach(item => {
        item.addEventListener('click', () => {
            const categoriaId = item.getAttribute('data-id');
            // Ao clicar, sempre volta para a página 1 da nova categoria selecionada
            window.location.href = `?page=1&categoria=${categoriaId}`;
        });
    });
</script>