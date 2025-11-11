<?php
$listaUsuarios = $listaUsuarios ?? [];
$mensagem_erro = $mensagem_erro ?? null;

$pagina_atual = $pagina_atual ?? 1;
$total_paginas = $total_paginas ?? 1;
$adminFilter = $adminFilter ?? null;

$filtroUrl = $adminFilter !== null ? '&admin=' . htmlspecialchars($adminFilter) : '';

$filtro_ativo_display = null;

if ($adminFilter !== null) {
    $valor_filtro = '';
    if (strtoupper($adminFilter) === 'S') {
        $valor_filtro = 'Administrador (Sim)';
    } elseif (strtoupper($adminFilter) === 'N') {
        $valor_filtro = 'Não Administrador (Não)';
    }

    if (!empty($valor_filtro)) {
        $filtro_ativo_display = [
            'tipo' => 'Status Admin',
            'valor' => $valor_filtro,
            'url_remover' => '/sugarbeat_admin/usuario?page=1'
        ];
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="/sugarbeat_admin/assets/css/listagemUsuario.css">
</head>

<div class="usuarios__container">
    <div class="usuarios__header">
        <h2>Usuários Registrados</h2>
        <div class="usuarios__acoes">
            <a href="/sugarbeat_admin/usuario/cadastro" class="btn__acao btn__adicionar">Adicionar novo +</a>
            <?php
            $btn_filtro_text = 'Filtrar';
            if ($adminFilter === 'true') $btn_filtro_text = 'Admin';
            if ($adminFilter === 'false') $btn_filtro_text = 'Não Admin';
            ?>
            <button class="btn__acao btn__filtrar <?= $adminFilter !== null ? 'ativo' : '' ?>" id="btn-filtrar">
                <?= $btn_filtro_text ?> <span>&#9660;</span>
            </button>
            <div id="dropdown-filtro" class="dropdown-filtro" style="display: none;">
                <div class="categoria" data-filtro="todos">Todos</div>
                <div class="categoria" data-filtro="S">Administrador</div>
                <div class="categoria" data-filtro="N">Não Administrador</div>
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

    <table class="usuarios__tabela">
        <thead>
            <tr>
                <th>Id</th>
                <th class="Nome">Nome</th>
                <th class="email">Email</th>
                <th>Administrador</th>
                <th class="usuarios__acoes-col" style="text-align:center;">Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($listaUsuarios) || !is_array($listaUsuarios)): ?> <tr>
                    <td colspan="7" style="text-align: center; padding: 20px; color: var(--cor-primaria); background-color: var(--cor-terceira);">Nenhum usuário encontrado.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($listaUsuarios as $user): ?> <tr>
                        <td>
                            <div class="id__circle">
                                <?= htmlspecialchars($user->getIdUsuario()) ?></div>
                        </td>
                        <td>
                            <div class="usuario__info-completa">
                                <span><?= htmlspecialchars($user->getNome()) ?></span>
                            </div>
                        </td>
                        <td><?= htmlspecialchars($user->getEmail()) ?></td>
                        <td>
                            <?php

                            $adminValue = $user->getAdministrador();

                            $isAdmin = strtoupper($adminValue) === 'S';
                            $texto = $isAdmin ? 'Sim' : 'Não';
                            $classe = $isAdmin ? 'sim' : 'nao';
                            ?>
                            <span class="admin-badge <?= $classe ?>">
                                <?= $texto ?>
                            </span>
                        </td>
                        <td class="usuarios__acoes-col">
                            <?php
                            $query_data = http_build_query([
                                'id' => $user->getIdUsuario(),
                                'nome' => $user->getNome(),
                                'email' => $user->getEmail(),
                                'administrador' => $user->getAdministrador()
                            ]);
                            ?>
                            <a href="/sugarbeat_admin/usuario/cadastro?<?= $query_data ?>" title="Editar" class="editar">
                                <i class="fa-solid fa-pen"></i>
                            </a>
                        </td>

                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>

        <?php if (!empty($listaUsuarios)): ?>
            <tfoot>
                <tr>
                    <td colspan="5">
                        <div class="produtos__paginacao">
                            <span class="paginacao__info">
                                (Página <?= $pagina_atual ?>/<?= $total_paginas ?>)
                            </span>

                            <div class="paginacao__botoes">
                                <?php $prevPage = max(1, $pagina_atual - 1) ; ?>
                                <a href="/sugarbeat_admin/usuario?page=<?= $prevPage ?><?= $filtroUrl ?>"
                                    <?= $pagina_atual <= 1 ? 'disabled style="pointer-events: none; opacity: 0.7;"' : '' ?>>Ant</a>

                                <span class="active-page-number">
                                    <?= $pagina_atual ?>
                                </span>

                                <?php $nextPage = min($total_paginas, $pagina_atual + 1); ?>
                                <a href="/sugarbeat_admin/usuario?page=<?= $nextPage ?><?= $filtroUrl ?>"
                                    <?= $pagina_atual >= $total_paginas ? 'disabled style="pointer-events: none; opacity: 0.7;"' : '' ?>>Seg</a>
                            </div>
                        </div>
                    </td>
                </tr>
            </tfoot>
        <?php endif; ?>
    </table>


    <script>
        const btnFiltrar = document.getElementById('btn-filtrar');
        const dropFiltro = document.getElementById('dropdown-filtro');

        btnFiltrar.addEventListener('click', (e) => {
            e.stopPropagation();
            dropFiltro.style.display = (dropFiltro.style.display === 'none' || dropFiltro.style.display === '') ? 'block' : 'none';
        });

        document.addEventListener('click', (e) => {
            if (!btnFiltrar.contains(e.target) && !dropFiltro.contains(e.target)) {
                dropFiltro.style.display = 'none';
            }
        });

        document.querySelectorAll('#dropdown-filtro .categoria').forEach(item => {
            item.addEventListener('click', () => {
                const filtroValor = item.getAttribute('data-filtro');
                let url = '/sugarbeat_admin/usuario';

                let query = '?page=1';

                if (filtroValor === 'todos') {
                    window.location.href = url + query;
                } else {
                    window.location.href = `${url}${query}&admin=${filtroValor}`;
                }
            });
        });
    </script>