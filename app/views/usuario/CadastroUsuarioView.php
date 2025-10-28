<head>
    <link rel="stylesheet" href="/sugarbeat_admin/assets/css/cadastroUsuario.css">
</head>

<div class="tela-container">

    <div class="cadastro-header">
        <h2>Dados do Usuário</h2>
    </div>

    <div class=" window-container container-produto">

        <form action="/sugarbeat_admin/produto/cadastro" method="POST" enctype="multipart/form-data" class="formulario">

            <div class="campos-container">
                <div class="campos-esquerda-container">

                    <div class="form-row campos_acima">
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
                        <div class="campo-grupo  esquerda">
                            <label for="email">Email</label>
                            <input type="text" id="email" name="email" required>
                        </div>

                        <div class="campo-grupo esquerda">
                            <label for="senha">Senha</label>
                            <input type="password" id="senha" name="senha" required>
                        </div>

                        <div class="campo-grupo esquerda campo-toggle">
                            <!-- O rótulo para o nome do campo -->
                            <label for="administrador-toggle">Administrador</label>

                            <!-- O contêiner do switch em si -->
                            <label class="switch">
                                <!-- O checkbox real (escondido) -->
                                <input type="checkbox" id="administrador-toggle" name="administrador" value="1">
                                <!-- O elemento visual que desliza -->
                                <span class="slider round"></span>
                            </label>
                        </div>

                    </div>

                    <div class="form-row">

                    </div>

                    <div class="botoes-acao">
                        <a href="/sugarbeat_admin/usuario/" class="botao botao-cancelar">Cancelar</a>
                        <button type="submit" class="botao botao-salvar">Salvar</button>
                    </div>
                </div>

        </form>
    </div>

</div>