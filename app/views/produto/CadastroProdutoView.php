<?php
// ...
// Lógica de pré-preenchimento
ob_start();
$produto = $produto_existente ?? null; // Pode vir do GET (edição) ou ser null (cadastro)

// Definimos uma variável para facilitar a verificação se estamos editando
$is_editing = $produto && $produto->getIdProduto();

$caminhoImagemAtual = 'caminho/para/sua/imagem_do_brigadeiro.png';

if ($produto && $produto->getImagem()) {
    // Aplica o basename para garantir que só pegamos o nome do arquivo
    $nome_do_arquivo = basename($produto->getImagem());

    // Monta o caminho web correto
    $caminhoImagemAtual = '../../fotos/' . htmlspecialchars($nome_do_arquivo);
}
ob_end_flush();
?>

<head>
    <link rel="stylesheet" href="/sugarbeat_admin/assets/css/cadastroProduto.css">
</head>

<div class="tela-container">

    <div class="cadastro-header">
        <h2>Dados do Produto</h2>
    </div>

    <div class=" window-container container-produto">

        <form action="/sugarbeat_admin/produto/cadastro" method="POST" enctype="multipart/form-data" class="formulario">

            <div class="campos-container">
                <div class="campos-esquerda-container">

                    <div class="form-row ">
                        <div class="campo-grupo id-campo esquerda">
                            <label for="id">ID</label>
                            <input type="text" id="id" name="id" readonly
                                value="<?= htmlspecialchars($produto?->getIdProduto() ?? '') ?>">

                            <input type="hidden" name="imagem_antiga" value="<?= $produto ? htmlspecialchars($produto->getImagem()) : '' ?>">
                        </div>

                        <div class="campo-grupo campo-nome esquerda">
                            <label for="nome">Nome</label>
                            <input type="text" id="nome" name="nome" required
                                value="<?= $is_editing ? htmlspecialchars($produto?->getNome()) : '' ?>">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="campo-grupo esquerda">
                            <label for="estoque">Estoque</label>
                            <input type="number" id="estoque" name="estoque" min="0" required
                                value="<?= $is_editing ? htmlspecialchars($produto?->getEstoque()) : '' ?>">
                        </div>

                        <div class="campo-grupo esquerda">
                            <label for="ativo">Ativo</label>
                            <select id="ativo" name="ativo" class="campo-select input-display" required>
                                <option value="" disabled <?= !$is_editing ? 'selected' : '' ?>>Selecione</option>

                                <?php
                                // Pega o valor atual de 'ativo' (que pode ser '1' ou '0')
                                // Prioriza o produto com erro se houver, senão usa o produto existente
                                $ativo_value = (isset($produto_com_erro) ? $produto_com_erro->getAtivo() : null)
                                    ?? ($is_editing ? $produto?->getAtivo() : null);
                                ?>

                                <option value="1" <?= $ativo_value == '1' ? 'selected' : '' ?>>Sim</option>
                                <option value="0" <?= $ativo_value == '0' ? 'selected' : '' ?>>Não</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="campo-grupo esquerda">
                            <label for="categoria">Categoria</label>
                            <select id="categoria" name="categoria" class="campo-select input-display" required>
                                <option value="" disabled <?= !$is_editing ? 'selected' : '' ?>>Selecione</option>
                                <?php
                                $categoria_selecionada_id = (isset($produto_com_erro) ? $produto_com_erro->getIdCategoria() : null)
                                    ?? ($is_editing ? $produto?->getIdCategoria() : null);
                                if (isset($listaCategorias) && is_array($listaCategorias)):
                                    foreach ($listaCategorias as $categoria):
                                        $selected = '';

                                        if ($categoria_selecionada_id == $categoria->getIdCategoria()) {
                                            $selected = 'selected';
                                        }
                                ?>
                                        <option value="<?= htmlspecialchars($categoria->getIdCategoria()) ?>" <?= $selected ?>>
                                            <?= htmlspecialchars($categoria->getNomeCategoria()) ?>
                                        </option>
                                <?php
                                    endforeach;
                                endif;
                                ?>
                            </select>

                        </div>

                        <div class="campo-grupo esquerda">
                            <label for="preco">Preço</label>
                            <input type="text" id="preco" name="preco" class="campo-input input-display" placeholder="0.00" required
                                value="<?= $is_editing ? htmlspecialchars($produto?->getPreco()) : '' ?>">
                        </div>
                    </div>

                </div>

                <div class="campo-grupo campo-imagem">
                    <label>Imagem do Produto</label>
                    <div class="imagem-box">
                        <img id="imagem-preview" src="<?= $caminhoImagemAtual ?>" alt="Imagem do Produto" class="imagem-preview" required>
                        <input type="file" id="input-imagem" name="imagem" accept="image/jpeg" style="display: none;">

                        <div class="icones-imagem">
                            <button type="button" class="icone-btn" title="Substituir Imagem" id="btn-substituir">&#x1F4C4;</button>
                            <button type="button" class="icone-btn lixeira" title="Remover Imagem" id="btn-remover">&#x1F5D1;</button>
                        </div>
                    </div>
                    <div class="botoes-acao">
                        <a href="/sugarbeat_admin/produto/" class="botao botao-cancelar">Cancelar</a>
                        <button type="submit" class="botao botao-salvar">Salvar</button>
                    </div>
                </div>

            </div>

        </form>
    </div>

</div>

<script>
    const inputImagem = document.getElementById('input-imagem');
    const btnSubstituir = document.getElementById('btn-substituir');
    const imagemPreview = document.getElementById('imagem-preview');
    const btnRemover = document.getElementById('btn-remover');

    // 1. Lógica para acionar o input file ao clicar em "Substituir"
    btnSubstituir.addEventListener('click', () => {
        inputImagem.click();
    });

    // 2. Lógica para pré-visualizar a imagem selecionada
    inputImagem.addEventListener('change', function(event) {
        const [file] = event.target.files;
        if (file) {
            // Cria uma URL temporária para o arquivo e atualiza o SRC da imagem
            imagemPreview.src = URL.createObjectURL(file);
        }
    });

    // 3. Lógica para "Remover" (reseta o input e a imagem)
    btnRemover.addEventListener('click', () => {
        inputImagem.value = ''; // Limpa o arquivo selecionado
        // Volta para a imagem padrão ou um placeholder. 
        // Você deve definir o caminho da imagem padrão (placeholder) aqui.
        imagemPreview.src = "caminho/para/sua/imagem_do_brigadeiro.png";
    });
</script>