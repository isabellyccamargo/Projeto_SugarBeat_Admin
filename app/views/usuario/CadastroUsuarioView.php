<?php
ob_start();
$usuario = $usuario_existente ?? null; // Pode vir do GET (edição) ou ser null (cadastro)
$is_editing = $usuario && $usuario->getIdUsuario();
ob_end_flush();
?>

<head>
    <link rel="stylesheet" href="/sugarbeat_admin/assets/css/cadastroUsuario.css">
</head>

<div class="tela-container">

    <div class="cadastro-header">
        <h2>Dados do Usuário</h2>
    </div>

    <div class="window-container container-usuario">

        <form action="/sugarbeat_admin/usuario/cadastro" method="POST" class="formulario">

            <div class="campos-container">
                <div class="campos-esquerda-container">

                    <div class="form-row campos_acima">
                        <div class="campo-grupo id-campo esquerda">
                            <label for="id">ID</label>
                            <input type="text" id="id" name="id" readonly
                                value="<?= $is_editing ? htmlspecialchars($usuario?->getIdUsuario()) : '' ?>">
                        </div>

                        <div class="campo-grupo campo-nome esquerda">
                            <label for="nome">Nome</label>
                            <input type="text" id="nome" name="nome" required
                                value="<?= $is_editing ? htmlspecialchars($usuario?->getNome()) : '' ?>">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="campo-grupo esquerda">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" required
                                value="<?= $is_editing ? htmlspecialchars($usuario?->getEmail()) : '' ?>">
                        </div>

                        <div class="campo-grupo esquerda">
                            <label for="senha">Senha</label>
                            <input type="password" id="senha" name="senha"
                                <?= $is_editing ? '' : 'required' ?>>
                            <?php if ($is_editing): ?>
                                <small>Deixe em branco para manter a senha atual</small>
                            <?php endif; ?>
                        </div>

                        <div class="campo-grupo esquerda campo-toggle">
                            <label for="administrador-toggle">Administrador</label>
                            <label class="switch">
                                <input type="checkbox" id="administrador-toggle" name="administrador"
                                    value="S" <?= $is_editing && $usuario?->getAdministrador() === 'S' ? 'checked' : '' ?>>
                                <span class="slider round"></span>
                            </label>
                        </div>
                    </div>

                    <div class="botoes-acao">
                        <a href="/sugarbeat_admin/usuario/" class="botao botao-cancelar">Cancelar</a>
                        <button type="submit" class="botao botao-salvar">Salvar</button>
                    </div>

                </div>
            </div>

        </form>
    </div>

</div>







