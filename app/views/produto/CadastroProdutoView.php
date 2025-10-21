<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SugarBeat Admin - Login</title>
    <link rel="stylesheet" href="/sugarbeat_admin/assets/css/cadastroProduto.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Ancizar+Serif:ital,wght@0,300..900;1,300..900&family=Bitter:ital,wght@0,100..900;1,100..900&family=Caudex:ital,wght@0,400;0,700;1,400;1,700&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Marcellus&family=Merriweather:ital,opsz,wght@0,18..144,300..900;1,18..144,300..900&family=Noto+Serif:ital,wght@0,100..900;1,100..900&family=Padauk:wght@400;700&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<div class="tela-container">

    <h1 class="titulo">Dados do Produto</h1>
    <div class="container-produto">



        <form action="processa_produto.php" method="POST" enctype="multipart/form-data" class="formulario">

            <div class="campos-container">

                <div class="campo-grupo">
                    <label for="id">ID</label>
                    <input type="text" id="id" name="id" class="campo-input input-display" readonly>
                </div>

                <div class="campo-grupo campo-nome">
                    <label for="nome">Nome</label>
                    <input type="text" id="nome" name="nome" class="campo-input input-ativo" required>
                </div>

                <div class="campo-grupo campo-imagem">
                    <label>Imagem do Produto</label>
                    <div class="imagem-box">
                        <img src="caminho/para/sua/imagem_do_brigadeiro.png" alt="Imagem do Produto" class="imagem-preview">
                        <div class="icones-imagem">
                            <button type="button" class="icone-btn" title="Substituir Imagem">
                                &#x1F4C4; </button>
                            <button type="button" class="icone-btn lixeira" title="Remover Imagem">
                                &#x1F5D1;
                            </button>
                        </div>
                    </div>
                </div>


                <div class="campo-grupo">
                    <label for="estoque">Estoque</label>
                    <input type="number" id="estoque" name="estoque" class="campo-input input-display" min="0">
                </div>

                <div class="campo-grupo">
                    <label for="ativo">Ativo</label>
                    <select id="ativo" name="ativo" class="campo-select input-display">
                        <option value="" disabled selected>Selecione</option>
                        <option value="1">Sim</option>
                        <option value="0">Não</option>
                    </select>
                </div>

                <div class="espacador-imagem"></div>


                <div class="campo-grupo">
                    <label for="categoria">Categoria</label>
                    <select id="categoria" name="categoria" class="campo-select input-display">
                        <option value="" disabled selected>Selecione</option>
                    </select>
                </div>

                <div class="campo-grupo">
                    <label for="preco">Preço</label>
                    <input type="text" id="preco" name="preco" class="campo-input input-display" placeholder="0.00">
                </div>

                <div class="espacador-imagem"></div>

            </div>

            <div class="botoes-acao">
                <button type="button" class="botao botao-cancelar">Cancelar</button>
                <button type="submit" class="botao botao-salvar">Salvar</button>
            </div>
        </form>
    </div>

</div>