<?php
ob_start();
$categoria = $categoria_existente ?? null;
$is_editing = $categoria && $categoria->getIdCategoria();
ob_end_flush();
?>

<head>
    <link rel="stylesheet" href="/sugarbeat_admin/assets/css/cadastroCategoria.css">
</head>

<div class="tela-container">

    <div class="cadastro-header">
        <h2>Dados da Categoria</h2>
    </div>

    <div class="window-container container-categoria">

        <form action="/sugarbeat_admin/categoria/cadastro" method="POST" class="formulario">
            
            <?php if ($is_editing): ?>
                <input type="hidden" name="id" 
                       value="<?= htmlspecialchars($categoria?->getIdCategoria()) ?>">
            <?php endif; ?>

            <div class="campos-container">
                <div class="campos-esquerda-container">

                    <div class="form-row campos_acima">
                        <div class="campo-grupo id-campo esquerda">
                            <label for="id">ID</label>
                            <input type="text" id="id_visual" readonly
                                value="<?= $is_editing ? htmlspecialchars($categoria?->getIdCategoria()) : '' ?>">
                        </div>

                        <div class="campo-grupo campo-nome esquerda">
                            <label for="nome">Nome</label>
                            <input type="text" id="nome_categoria" name="nome_categoria" required
                                value="<?= $is_editing ? htmlspecialchars($categoria?->getNomeCategoria()) : '' ?>">
                        </div>
                    </div>

                    <div class="botoes-acao">
                        <a href="/sugarbeat_admin/categoria/" class="botao botao-cancelar">Cancelar</a>
                        <button type="submit" class="botao botao-salvar">Salvar</button>
                    </div>

                </div>
            </div>

        </form>
    </div>

</div>