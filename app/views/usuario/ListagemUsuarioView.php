<?php
$listaUsuarios = $listaUsuarios ?? [];
$mensagem_erro = $mensagem_erro ?? null;
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>

    <link rel="stylesheet" href="/sugarbeat_admin/assets/css/listagemProduto.css">
</head>

<div class="produtos__container">
    <div class="produtos__header">
        <h2>Usuários Registrados</h2>
        <div class="produtos__acoes">
            <a href="/sugarbeat_admin/produto/cadastro" class="btn__acao btn__adicionar">Adicionar novo +</a>
            <button class="btn__acao btn__filtrar" id="btn-filtrar">Filtrar <span>&#9660;</span></button>

        </div>
    </div>

    <table class="produtos__tabela">
        <thead>
            <tr>
                <th>Id</th>
                <th>Nome</th>
                <th>Email</th>
                <th>Senha</th>
                <th>Administrador</th>
                <th class="produtos__acoes-col" style="text-align:center;">Ações</th>
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
                            <div class="produto__info-completa">
                                <span><?= htmlspecialchars($user->getNome()) ?></span>
                            </div>
                        </td>
                        <td><?= htmlspecialchars($user->getEmail()) ?></td>
                        <td></td>
                        <td><?= htmlspecialchars($user->getAdministrador()) ?></td>
                        <td class="produtos__acoes-col">
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

    const btn = document.getElementById('btn-filtrar');
    const drop = document.getElementById('dropdown-filtro');

    btn.addEventListener('click', () => {
        drop.style.display = (drop.style.display === 'none' || drop.style.display === '') ? 'block' : 'none';
    });

    document.addEventListener('click', (e) => {
        if (!btn.contains(e.target) && !drop.contains(e.target)) {
            drop.style.display = 'none';
        }
    });

    document.querySelectorAll('.dropdown-filtro .categoria').forEach(item => {
        item.addEventListener('click', () => {
            const categoriaId = item.getAttribute('data-id');
            window.location.href = `?categoria=${categoriaId}`;
        });
    });
    
</script>