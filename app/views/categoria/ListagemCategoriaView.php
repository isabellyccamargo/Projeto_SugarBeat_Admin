<?php
$listaCategorias = $listaCategorias ?? [];
$pagina_atual = $pagina_atual ?? 1;
$total_paginas = $total_paginas ?? 1;
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="/sugarbeat_admin/assets/css/listagemCategoria.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<div class="categorias__container">
    <div class="categorias__header">
        <h2>Categorias Registrados</h2>
        <div class="categorias__acoes">
            <a href="/sugarbeat_admin/categoria/cadastro" class="btn__acao btn__adicionar">Adicionar novo +</a>
        </div>
    </div>

    <table class="categoria__tabela">
        <thead>
            <tr>
                <th>Id</th>
                <th class="Nome">Nome</th>
                <th class="categorias__acoes-col" style="text-align:center;">Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($listaCategorias) || !is_array($listaCategorias)): ?> <tr>
                    <td colspan="7" style="text-align: center; padding: 20px; color: var(--cor-primaria); background-color: var(--cor-terceira);">Nenhum usuário encontrado.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($listaCategorias as $categoria): ?> <tr>
                        <td>
                            <div class="id__circle">
                                <?= htmlspecialchars($categoria->getIdCategoria()) ?></div>
                        </td>
                        <td>
                            <div class="categoria__info-completa">
                                <span><?= htmlspecialchars($categoria->getNomeCategoria()) ?></span>
                            </div>
                        </td>

                        <td class="categorias__acoes-col">
                            <?php
                            $query_data = http_build_query([
                                'id' => $categoria->getIdCategoria(),
                                'nome' => $categoria->getNomeCategoria(),
                            ]);
                            ?>
                            <a href="/sugarbeat_admin/categoria/cadastro?<?= $query_data ?>" title="Editar" class="editar">
                                <i class="fa-solid fa-pen"></i>
                            </a>

                            <a href="/sugarbeat_admin/categoria/excluir/<?= $categoria->getIdCategoria() ?>"
                                title="Excluir"
                                class="excluir"
                                id="link-excluir-<?= $categoria->getIdCategoria() ?>" data-nome-categoria="<?= htmlspecialchars($categoria->getNomeCategoria()) ?>">
                                <i class="fa-solid fa-trash-can"></i>
                            </a>
                        </td>

                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>

        <?php if (!empty($listaCategorias)): ?>
            <tfoot>
                <tr>
                    <td colspan="5">
                        <div class="produtos__paginacao">
                            <span class="paginacao__info">
                                (Página <?= $pagina_atual ?>/<?= $total_paginas ?>)
                            </span>

                            <div class="paginacao__botoes">
                                <?php $prevPage = max(1, $pagina_atual - 1); ?>
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const linksExcluir = document.querySelectorAll('a.excluir');

            linksExcluir.forEach(link => {
                link.addEventListener('click', function(event) {
                    event.preventDefault();

                    const urlExclusao = this.getAttribute('href');
                    const nomeCategoria = this.getAttribute('data-nome-categoria');

                    Swal.fire({
                        title: 'Tem certeza?',
                        html: `Você realmente deseja excluir a categoria **${nomeCategoria}**?`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#b34242',
                        cancelButtonColor: '#3b2500',
                        confirmButtonText: 'Sim, excluir!',
                        cancelButtonText: 'Cancelar',
                        background: 'rgb(248, 239, 218)',
                        color: '#3b2500',
                        heightAuto: false
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = urlExclusao;
                        }
                    });
                });
            });
        });
    </script>