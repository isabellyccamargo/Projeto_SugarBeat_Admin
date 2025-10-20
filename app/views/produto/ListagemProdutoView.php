<?php
// Variáveis padrão fornecidas pelo Controller (ajuste conforme a sua implementação)
$produtos = $produtos ?? [];
$mensagem_erro = $mensagem_erro ?? null;

$produtos = $produtos ?? [];
$mensagem_erro = $mensagem_erro ?? null;
$pagina_atual = $pagina_atual ?? 1; // Dados de Paginação (que vieram do Controller)
$total_paginas = $total_paginas ?? 1;
$total_produtos = $total_produtos ?? 0;
$produtos_por_pagina = $produtos_por_pagina ?? 10;

// Funções Auxiliares para formatação
function formatarPreco($preco)
{
    return 'R$ ' . number_format((float)$preco, 2, ',', '.');
}

// Seu CSS específico
?>
<style>
    /* Suas cores definidas, repetidas aqui para referência */
    :root {
        --cor-primaria: #3b2500ff;
        /* Marrom Escuro */
        --cor-secundaria: rgb(248, 239, 218);
        /* Bege Claro (Quase Branco) */
        --cor-terceira: rgb(253, 230, 182);
        /* Bege Médio */
    }

    /* =======================================================
     * CSS GERAL (Mantido)
     * ======================================================= */
    .produtos__container {
        padding: 20px;
    }

    .produtos__header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .produtos__header h2 {
        color: var(--cor-primaria);
        font-size: 1.8rem;
        font-weight: 600;
        margin: 0;
    }

    .produtos__acoes {
        display: flex;
        gap: 15px;
    }

    .btn__acao {
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        font-size: 1rem;
        cursor: pointer;
        font-weight: 600;
        transition: background-color 0.2s;
        text-decoration: none;
        display: inline-block;
        text-align: center;
    }

    .btn__adicionar {
        background-color: var(--cor-primaria);
        color: var(--cor-secundaria);
    }

    .btn__adicionar:hover {
        background-color: #553500;
    }

    .btn__filtrar {
        background-color: var(--cor-terceira);
        color: var(--cor-primaria);
        border: 1px solid var(--cor-primaria);
    }

    .btn__filtrar:hover {
        background-color: #f7e0bc;
    }

    /* ====== TABELA ESTILOS ====== */
    .produtos__tabela {
        width: 100%;
        border-collapse: collapse;
        background-color: var(--cor-secundaria);
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        margin-bottom: 0;
    }

    .produtos__tabela thead th {
        background-color: var(--cor-primaria);
        color: var(--cor-terceira);
        padding: 12px 15px;
        text-align: left;
        font-weight: 500;
        text-transform: none;
        font-size: 0.95rem;
    }

    .produtos__tabela tbody tr {
        border-bottom: 1px solid var(--cor-terceira);
        color: var(--cor-primaria);
        background-color: var(--cor-secundaria);
    }

    .produtos__tabela tbody tr:nth-child(odd) {
        background-color: var(--cor-terceira);
    }

    .produtos__tabela td {
        padding: 12px 15px;
        font-size: 0.95rem;
    }

    /* Estilo do RODAPÉ da Tabela (PAGINAÇÃO) */
    .produtos__tabela tfoot td {
        background-color: var(--cor-terceira);
        padding: 15px;
        border-top: none;
    }

    /* Redefinindo o container da Paginação */
    .produtos__paginacao {
        display: flex;
        justify-content: space-between;
        align-items: center;
        color: var(--cor-primaria);
        width: 100%;
    }

    .paginacao__info {
        font-weight: 600;
        margin-left: 10px;
    }

    /* =======================================================
     * CSS CORRIGIDO PARA O VISUAL DE APENAS 3 ELEMENTOS
     * (Ant | Número Ativo | Seg)
     * ======================================================= */
    .paginacao__botoes {
        display: flex;
        /* Usa a cor da borda clara do seu design */
        border: 1px solid rgba(59, 37, 0, 0.3);
        border-radius: 5px;
        overflow: hidden;
    }

    .paginacao__botoes a,
    .paginacao__botoes .active-page-number {
        /* Inclui a nova classe para o número */
        padding: 5px 10px;
        margin: 0;
        border: none;
        text-decoration: none;
        color: var(--cor-primaria);
        transition: background-color 0.2s;

        background-color: var(--cor-secundaria);
        font-weight: 600;
        display: flex;
        align-items: center;
        justify-content: center;
        min-width: 30px;
    }

    /* Borda vertical entre os itens (Ant e Número Ativo) */
    .paginacao__botoes a:first-child {
        border-right: 1px solid rgba(59, 37, 0, 0.3);
    }

    /* Borda vertical entre o Número Ativo e Seg */
    .paginacao__botoes .active-page-number {
        border-right: 1px solid rgba(59, 37, 0, 0.3);
    }

    /* Elemento Central Ativo (Cor Marrom Escura) */
    .paginacao__botoes .active-page-number {
        background-color: var(--cor-primaria);
        color: var(--cor-secundaria);
    }

    /* Efeito Hover em Ant/Seg */
    .paginacao__botoes a:hover {
        background-color: var(--cor-terceira);
    }

    /* Estilos de Status e Ícones (Mantidos) */
    .status-nao {
        color: #B00020;
        font-weight: 600;
    }

    .id__circle {
        display: flex;
        align-items: center;
        gap: 5px;
        font-weight: 700;
        justify-content: center;
        /* Centraliza o ID */
    }

    .circle {
        width: 15px;
        height: 15px;
        border-radius: 50%;
        background-color: var(--cor-primaria);
        /* Cor da bolinha do ID */
        display: inline-block;
    }

    .produto__info-completa {
        display: flex;
        align-items: center;
        gap: 10px;
        /* Espaço entre a imagem e o nome */
        font-weight: 500;
    }

    .produto__img {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        /* Transforma em círculo */
        object-fit: cover;
        /* Garante que a imagem preencha o círculo */
        border: 1px solid rgba(59, 37, 0, 0.5);
        /* Borda sutil */
    }

    .produtos__acoes-col {
        text-align: right;
        width: 100px;
    }

    .produtos__acoes-col a {
        color: var(--cor-primaria);
        margin-left: 10px;
        font-size: 1.1rem;
    }

    .produtos__acoes-col a:hover {
        color: #8b6b3e;
    }
</style>

<div class="produtos__container">
    <div class="produtos__header">
        <h2>Produtos Registrados</h2>
        <div class="produtos__acoes">
            <a href="/sugarbeat_admin/produto/CadastroProdutoView" class="btn__acao btn__adicionar">Adicionar novo +</a>
            <button class="btn__acao btn__filtrar">Filtrar <span>&#9660;</span></button>
        </div>
    </div>

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
                <th>Ativo</th>
                <th>Categoria</th>
                <th>Preço</th>
                <th class="produtos__acoes-col">Editar Excluir</th>
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
                    $is_ativo = $produto->getEstoque() > 0;
                    $ativo_texto = $is_ativo ? 'Sim' : 'Não';
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
                                // 1. Pega o valor do BD (deve ser apenas o nome do arquivo, ex: "brigadeiro.jpg")
                                $img = $produto->getImagem();


                                $img = preg_replace('#^\.\./\.\./\.\./#', '', $img);
                                ?>


                                <img src="/sugarbeat_admin/<?= htmlspecialchars($img) ?>" width="60" class="produto__img">

                                <span><?= htmlspecialchars($produto->getNome()) ?></span>
                            </div>
                        </td>
                        <td><?= htmlspecialchars($produto->getEstoque()) ?></td>
                        <td class="<?= !$is_ativo ? 'status-nao' : '' ?>">
                            <?= $ativo_texto ?>
                        </td>
                        <td><?= htmlspecialchars($produto->getIdCategoria()) ?></td>
                        <td><?= $preco_formatado ?></td>
                        <td class="produtos__acoes-col">
                            <a href="/sugarbeat_admin/produto/editar/<?= $produto->getIdProduto() ?>" title="Editar">
                                &#9998;
                            </a>
                            <a href="/sugarbeat_admin/produto/deletar/<?= $produto->getIdProduto() ?>" title="Excluir">
                                &#128465;
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
                            Mostrando de <?= ($pagina_atual - 1) * $produtos_por_pagina + 1 ?>
                            a <?= min($pagina_atual * $produtos_por_pagina, $total_produtos) ?>
                            de <?= $total_produtos ?> produtos. (Pág. <?= $pagina_atual ?>/<?= $total_paginas ?>)
                        </span>

                        <div class="paginacao__botoes">

                            <a href="?page=<?= max(1, $pagina_atual - 1) ?>"
                                <?= $pagina_atual <= 1 ? 'disabled style="pointer-events: none; opacity: 0.7;"' : '' ?>>Ant</a>

                            <span class="active-page-number">
                                <?= $pagina_atual ?>
                            </span>

                            <a href="?page=<?= min($total_paginas, $pagina_atual + 1) ?>"
                                <?= $pagina_atual >= $total_paginas ? 'disabled style="pointer-events: none; opacity: 0.7;"' : '' ?>>Seg</a>
                        </div>
                    </div>
                </td>
            </tr>
        </tfoot>
    </table>
</div>