<?php
$listaUsuarios = $listaUsuarios ?? [];
$mensagem_erro = $mensagem_erro ?? null;

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>

    <link rel="stylesheet" href="/sugarbeat_admin/assets/css/listagemUsuario.css">
</head>

<div class="usuarios__container">
    <div class="usuarios__header">
        <h2>Usuários Registrados</h2>
        <div class="usuarios__acoes">
            <a href="/sugarbeat_admin/usuario/cadastro" class="btn__acao btn__adicionar">Adicionar novo +</a>
            <button class="btn__acao btn__filtrar" id="btn-filtrar">Filtrar <span>&#9660;</span></button>
            <div id="dropdown-filtro" class="dropdown-filtro" style="display: none;">
                <div class="categoria" data-filtro="todos">Todos</div>
                <div class="categoria" data-filtro="S">Administrador</div>
                <div class="categoria" data-filtro="N">Não Administrador</div>
            </div>

        </div>
    </div>

    <table class="usuarios__tabela">
        <thead>
            <tr>
                <th>Id</th>
                <th>Nome</th>
                <th>Email</th>
                <th>Senha</th>
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
                        <td></td>
                        <td>
                            <?php
                            $isAdmin = $user->getAdministrador() === 'S';
                            $texto = $isAdmin ? 'Sim' : 'Não';
                            $classe = $isAdmin ? 'sim' : 'nao';
                            ?>
                            <span class="admin-badge <?= $classe ?>">
                                <?= $texto ?>
                            </span>
                        </td>
                        <td class="usuarios__acoes-col">
                            <a href="/sugarbeat_admin/usuario/editar/<?= $user->getIdUsuario() ?>" title="Editar" class="editar">
                                <i class="fa-solid fa-pen"></i>
                            </a>

                            <a href="/sugarbeat_admin/usuario/deletar/<?= $user->getIdUsuario() ?>" title="Excluir" class="deletar">
                                <i class="fa-solid fa-trash"></i>
                            </a>
                        </td>

                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>

    </table>
</div>

<script>
    const btnFiltrar = document.getElementById('btn-filtrar');
    const dropFiltro = document.getElementById('dropdown-filtro');

    // 1. Lógica para Abrir/Fechar o Dropdown
    btnFiltrar.addEventListener('click', (e) => {
        e.stopPropagation(); // Evita que o clique feche imediatamente
        dropFiltro.style.display = (dropFiltro.style.display === 'none' || dropFiltro.style.display === '') ? 'block' : 'none';
    });

    // Fechar ao clicar fora
    document.addEventListener('click', (e) => {
        if (!btnFiltrar.contains(e.target) && !dropFiltro.contains(e.target)) {
            dropFiltro.style.display = 'none';
        }
    });

    // 2. Lógica para Filtrar por Status de Administrador
    document.querySelectorAll('#dropdown-filtro .categoria').forEach(item => {
        item.addEventListener('click', () => {
            const filtroValor = item.getAttribute('data-filtro');
            let url = '/sugarbeat_admin/usuario';

            if (filtroValor === 'todos') {
                // Redireciona para a URL base (sem filtro)
                window.location.href = url;
            } else {
                // Adiciona o parâmetro 'admin' na URL (ex: /usuario?admin=S)
                window.location.href = `${url}?admin=${filtroValor}`;
            }
        });
    });
</script>