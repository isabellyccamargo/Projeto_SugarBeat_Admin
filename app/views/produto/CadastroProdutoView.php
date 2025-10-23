

<head>
    <!--     <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SugarBeat Admin - Login</title> -->
    <link rel="stylesheet" href="/sugarbeat_admin/assets/css/cadastroProduto.css">

    <!--   <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Ancizar+Serif:ital,wght@0,300..900;1,300..900&family=Bitter:ital,wght@0,100..900;1,100..900&family=Caudex:ital,wght@0,400;0,700;1,400;1,700&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Marcellus&family=Merriweather:ital,opsz,wght@0,18..144,300..900;1,18..144,300..900&family=Noto+Serif:ital,wght@0,100..900;1,100..900&family=Padauk:wght@400;700&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">  
-->
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
                            <input type="text" id="id" name="id" readonly>
                        </div>

                        <div class="campo-grupo campo-nome esquerda">
                            <label for="nome">Nome</label>
                            <input type="text" id="nome" name="nome" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="campo-grupo esquerda">
                            <label for="estoque">Estoque</label>
                            <input type="number" id="estoque" name="estoque" min="0" required>
                        </div>

                        <div class="campo-grupo esquerda">
                            <label for="ativo">Ativo</label>
                            <select id="ativo" name="ativo " class="campo-select input-display " required>
                                <option value="" disabled selected>Selecione</option>
                                <option value="1">Sim</option>
                                <option value="0">Não</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="campo-grupo esquerda">
                            <label for="categoria">Categoria</label>
                            <select id="categoria" name="categoria" class="campo-select input-display" required>
                                <option value="" disabled selected>Selecione</option>
                                <?php
                                // Garante que a variável exista antes de iterar
                                if (isset($listaCategorias) && is_array($listaCategorias)):
                                    foreach ($listaCategorias as $categoria):
                                        // O value do option deve ser o ID da categoria, não o nome
                                        // Assumindo que sua Categoria Model tem getIdCategoria() e getNome()
                                        $selected = '';

                                        // Lógica para manter a categoria selecionada em caso de erro
                                        if (isset($produto_com_erro) && $produto_com_erro->getIdCategoria() == $categoria->getIdCategoria()) {
                                            $selected = 'selected';
                                        }
                                ?>
                                        <option value="<?= $categoria->getIdCategoria() ?>" <?= $selected ?>>
                                            <?= $categoria->getNomeCategoria() ?>
                                        </option>
                                <?php
                                    endforeach;
                                endif;
                                ?>
                            </select>
                        </div>

                        <div class="campo-grupo esquerda">
                            <label for="preco">Preço</label>
                            <input type="text" id="preco" name="preco" class="campo-input input-display" placeholder="0.00" required>
                        </div>
                    </div>

                </div>

                <div class="campo-grupo campo-imagem">
                    <label>Imagem do Produto</label>
                    <div class="imagem-box">
                        <img src="caminho/para/sua/imagem_do_brigadeiro.png" alt="Imagem do Produto" class="imagem-preview"required>
                        <div class="icones-imagem">
                            <button type="button" class="icone-btn" title="Substituir Imagem">
                                &#x1F4C4; </button>
                            <button type="button" class="icone-btn lixeira" title="Remover Imagem">
                                &#x1F5D1;
                            </button>
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