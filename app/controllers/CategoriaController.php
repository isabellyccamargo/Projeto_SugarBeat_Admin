<?php

class CategoriaController
{
    private $categoriaService;

    public function __construct(CategoriaService $categoriaService)
    {
        $this->categoriaService = $categoriaService;
    }

    public function listar($id = null)
    {
        if ($id) {
            try {
                $categoria = $this->categoriaService->getCategoria($id);
                // Renderizar detalhe/edição (se este listar for a rota de detalhe)
                View::renderWithLayout('categoria/DetalheCategoriaView', 'config/AppLayout', ['categoria' => $categoria]);
                return;
            } catch (Exception $e) {
                http_response_code(404);
                $_SESSION['alert_message'] = ['type' => 'error', 'title' => 'Erro!', 'text' => $e->getMessage()];
                header("Location: /sugarbeat_admin/categoria");
                exit();
            }
        }

        // Lógica de listagem e paginação (usada quando NÃO há ID)
        $itensPorPagina = 8;
        $paginaAtual = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT) ?: 1;

        try {
            $dadosPaginacao = $this->categoriaService->getCategoriasPaginadas(
                $paginaAtual,
                $itensPorPagina
            );

            $data = [
                'listaCategorias' => $dadosPaginacao['categorias'],
                'pagina_atual' => $dadosPaginacao['pagina_atual'],
                'total_paginas' => $dadosPaginacao['total_paginas'],
                'itens_por_pagina' => $itensPorPagina,
                'total_categorias' => $dadosPaginacao['total_categorias'],
            ];

            View::renderWithLayout('categoria/ListagemCategoriaView', 'config/AppLayout', $data);
        } catch (Exception $e) {
            $_SESSION['alert_message'] = ['type' => 'error', 'title' => 'Erro!', 'text' => 'Erro ao listar categorias: ' . $e->getMessage()];

            View::renderWithLayout('categoria/ListagemCategoriaView', 'config/AppLayout', ['listaCategorias' => []]);
        }
    }


    public function cadastro()
    {
        $categoriaId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        $categoriaExistente = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Se for POST, tenta salvar (o salvar decidirá se é INSERT ou UPDATE)
            $this->salvar();
            // É importante dar exit() no POST
            exit();
        }

        // Se for GET: Checa se é uma EDIÇÃO
        if ($categoriaId) {
            try {
                // Tenta buscar a categoria existente para preencher o formulário
                $categoriaExistente = $this->categoriaService->getCategoria($categoriaId);
            } catch (Exception $e) {
                // Erro ao buscar (Categoria não encontrada)
                $_SESSION['alert_message'] = [
                    'type' => 'error',
                    'title' => 'Erro!',
                    'text' => 'Categoria não encontrada: ' . $e->getMessage()
                ];
                header("Location: /sugarbeat_admin/categoria");
                exit();
            }
        }

        // Renderiza a View de cadastro/edição
        $data = $categoriaExistente ? ['categoria_existente' => $categoriaExistente] : [];
        View::renderWithLayout('categoria/CadastroCategoriaView', 'config/AppLayout', $data);
    }

    private function salvar()
    {
        // 1. Tenta pegar o ID, se existir (para UPDATE)
        $categoriaId = !empty($_POST['id']) ? (int)$_POST['id'] : null;
        $locationError = $categoriaId ? "/sugarbeat_admin/categoria/cadastro?id=" . $categoriaId : "/sugarbeat_admin/categoria/cadastro";

        try {
            $nome = trim($_POST['nome_categoria'] ?? '');

            if (empty($nome)) {
                throw new Exception("O nome da categoria é obrigatório.");
            }

            $categoria = new Categoria($categoriaId, $nome);
            $mensagemSucesso = "Categoria '{$nome}' cadastrada com sucesso.";

            // 2. Decide se é CREATE ou UPDATE
            if ($categoriaId) {
                $this->categoriaService->atualizarCategoria($categoria);
                $mensagemSucesso = "Categoria '{$nome}' atualizada com sucesso.";
            } else {
                $this->categoriaService->criarNovaCategoria($categoria);
            }

            $_SESSION['alert_message'] = [
                'type' => 'success',
                'title' => 'Sucesso!',
                'text' => $mensagemSucesso
            ];

            header("Location: /sugarbeat_admin/categoria");
            exit();
        } catch (Exception $e) {
            $_SESSION['alert_message'] = [
                'type' => 'error',
                'title' => 'Erro!',
                'text' => 'Erro ao salvar categoria: ' . $e->getMessage()
            ];


            header("Location: " . $locationError);
            exit();
        }
    }

    public function excluir($id)
    {
        $id = filter_var($id, FILTER_VALIDATE_INT);

        if (!$id) {
            http_response_code(400);
            $_SESSION['alert_message'] = ['type' => 'error', 'title' => 'Erro!', 'text' => 'ID da categoria inválido ou não fornecido.'];
            header("Location: /sugarbeat_admin/categoria");
            exit();
        }

        try {
            $this->categoriaService->excluirCategoria($id);

            $_SESSION['alert_message'] = [
                'type' => 'success',
                'title' => 'Sucesso!',
                'text' => "Categoria excluída com sucesso."
            ];

            header("Location: /sugarbeat_admin/categoria");
            exit();
        } catch (Exception $e) {
            http_response_code(400);
        
        $errorMessage = 'Erro interno ao tentar a exclusão. Tente novamente ou contate o suporte.';
        
        if (str_contains($e->getMessage(), '1451 Cannot delete or update a parent row') || $e->getCode() == '23000') {
            
            $errorMessage = "⚠️ Esta categoria **não pode ser excluída**! Ela possui produtos vinculados. Por favor, remova ou mova todos os produtos desta categoria e tente novamente.";
        }
        
        $_SESSION['alert_message'] = [
            'type' => 'error', 
            'title' => 'Atenção: Violação de Regra!', 
            'text' => $errorMessage
        ];
        
        header("Location: /sugarbeat_admin/categoria");
        exit();
    
        }
    }
}
