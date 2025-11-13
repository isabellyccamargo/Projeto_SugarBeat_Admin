    <?php

    ob_start();

    $dadosHistorico = $produto_historico ?? [];

    $listaHistorico = $dadosHistorico["listaProdutoHistorico"] ?? [];
    $paginaAtual = $dadosHistorico["pagina_atual"] ?? 1;
    $totalPaginas = $dadosHistorico["total_paginas"] ?? 1;

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
        <link rel="stylesheet" href="/sugarbeat_admin/assets/css/listagemProduto.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    </head>

    <div class="tela-container">

        <div class="cadastro-header">
            <h2>Dados do Produto</h2>
        </div>

        <div class="tabs-container">
            <div class="tab-menu">
                <button class="tab-button active" data-tab="tab-cadastro">Cadastro</button>
                <?php if ($is_editing): ?>
                    <button class="tab-button" data-tab="tab-auditoria">Auditoria</button>
                <?php endif; ?>
            </div>

            <div id="tab-cadastro" class="tab-content active window-container container-produto">

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

                                <div class="campo-grupo esquerda" style="position: relative; width: 150px;">
                                    <label for="preco">Preço</label>
                                    <span class="prefixo">R$</span>
                                    <input type="text" id="preco" name="preco" class="campo-input input-display"
                                        placeholder="0,00" required
                                        value="<?= $is_editing ? htmlspecialchars(number_format($produto?->getPreco(), 2, ',', '.')) : '' ?>">
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

            <?php if ($is_editing): ?>
                <div id="tab-auditoria" class="tab-content window-container">
                    <div class="produtos__header">
                        <h2>Histórico de Alterações</h2>
                    </div>

                    <table class="produtos__tabela">
                        <thead>
                            <tr>
                                <th style="width:18%;">Data/Hora</th>
                                <th style="width:18%;">Operação</th>
                                <th style="width:28%;">Campo Alterado</th>
                                <th style="width:18%;">Valor Antigo</th>
                                <th style="width:18%;">Novo Valor</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php if (!empty($listaHistorico)) : ?>
                                <?php foreach ($listaHistorico as $item): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($item->getData()) ?></td>
                                        <td><?= htmlspecialchars($item->getOperacao()) ?></td>
                                        <td><?= htmlspecialchars($item->getCampo()) ?></td>
                                        <td><?= htmlspecialchars($item->getValorAntigo()) ?></td>
                                        <td><?= htmlspecialchars($item->getValorAtual()) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" style="text-align: center; padding: 20px; color: var(--cor-primaria); background-color: var(--cor-terceira);">
                                        Nenhuma alteração registrada.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>

                        <tfoot>
                            <?php
                            $paginaAtual = $dadosHistorico["pagina_atual"] ?? 1;
                            $totalPaginas = $dadosHistorico["total_paginas"] ?? 1;
                            $idProduto = $produto?->getIdProduto();
                            ?>
                            <tr>
                                <td colspan="5">
                                    <div class="produtos__paginacao">
                                        <span class="paginacao__info">
                                            (Página <?= $paginaAtual ?>/<?= $totalPaginas ?>)
                                        </span>

                                        <div class="paginacao__botoes">
                                            <a href="?id=<?= $idProduto ?>&tab=tab-auditoria&page=<?= max(1, $paginaAtual - 1) ?>"
                                                <?= $paginaAtual <= 1 ? 'disabled style="pointer-events: none; opacity: 0.7;"' : '' ?>>Ant</a>

                                            <span class="active-page-number"><?= $paginaAtual ?></span>

                                            <a href="?id=<?= $idProduto ?>&tab=tab-auditoria&page=<?= min($totalPaginas, $paginaAtual + 1) ?>"
                                                <?= $paginaAtual >= $totalPaginas ? 'disabled style="pointer-events: none; opacity: 0.7;"' : '' ?>>Seg</a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            <?php endif; ?>

        </div>
    </div>

    <script>
        // Se o produto não estiver em edição (cadastrando um novo), 
        // a aba de auditoria não será exibida.

        const tabButtons = document.querySelectorAll('.tab-button');
        const tabContents = document.querySelectorAll('.tab-content');

        tabButtons.forEach(button => {
            button.addEventListener('click', () => {
                const targetId = button.dataset.tab;

                // 1. Desativa todas as abas e conteúdos
                tabButtons.forEach(btn => btn.classList.remove('active'));
                tabContents.forEach(content => content.classList.remove('active'));

                // 2. Ativa o botão clicado
                button.classList.add('active');

                // 3. Ativa o conteúdo correspondente
                const targetContent = document.getElementById(targetId);
                if (targetContent) {
                    targetContent.classList.add('active');
                }
            });
        });
    </script>

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

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>

    <script>
        $(document).ready(function() {
            // Máscara para formato de moeda
            $('#preco').mask('000.000.000,00', {
                reverse: true
            });

            // Ao enviar o formulário, converte para formato numérico (ex: 1.230,50 -> 1230.50)
            $('form').on('submit', function() {
                let val = $('#preco').val();
                val = val.replace(/\./g, '').replace(',', '.'); // remove pontos e troca vírgula por ponto
                $('#preco').val(val);
            });
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const params = new URLSearchParams(window.location.search);
            const tabParam = params.get("tab");

            if (tabParam) {
                const activeButton = document.querySelector(`[data-tab="${tabParam}"]`);
                const activeContent = document.getElementById(tabParam);

                if (activeButton && activeContent) {
                    document.querySelectorAll('.tab-button').forEach(btn => btn.classList.remove('active'));
                    document.querySelectorAll('.tab-content').forEach(tab => tab.classList.remove('active'));

                    activeButton.classList.add('active');
                    activeContent.classList.add('active');
                }
            }
        });
    </script>