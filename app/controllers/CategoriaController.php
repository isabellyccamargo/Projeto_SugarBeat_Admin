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

                View::renderWithLayout('categoria/DetalheCategoriaView', 'config/AppLayout', ['categoria' => $categoria]);
            } catch (Exception $e) {
                http_response_code(404);
                $_SESSION['alert_message'] = ['type' => 'error', 'title' => 'Erro!', 'text' => $e->getMessage()];
                header("Location: /sugarbeat_admin/categoria");
                exit();
            }
        } else {

            try {
                $categorias = $this->categoriaService->listarTodasCategorias();
                View::renderWithLayout('categoria/ListagemCategoriaView', 'config/AppLayout', ['listaCategorias' => $categorias]);
            } catch (Exception $e) {
                $_SESSION['alert_message'] = ['type' => 'error', 'title' => 'Erro!', 'text' => 'Erro ao listar categorias: ' . $e->getMessage()];
                View::renderWithLayout('categoria/ListagemCategoriaView', 'config/AppLayout', ['listaCategorias' => []]);
            }
        }
    }


    public function cadastro()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->salvar();
        } else {

            
            View::renderWithLayout('categoria/CadastroCategoriaView', 'config/AppLayout');
        }
    }
    
    private function salvar()
    {
        try {
            $nome = $_POST['nome_categoria'] ?? '';
            $categoria = new Categoria(null, $nome);
            
            $novaCategoria = $this->categoriaService->criarNovaCategoria($categoria);

            $_SESSION['alert_message'] = [
                'type' => 'success',
                'title' => 'Sucesso!',
                'text' => "Categoria '{$novaCategoria->getNomeCategoria()}' cadastrada com sucesso."
            ];

            header("Location: /sugarbeat_admin/categoria"); 
            exit();
        } catch (Exception $e) {
            $_SESSION['alert_message'] = [
                'type' => 'error',
                'title' => 'Erro!',
                'text' => 'Erro ao cadastrar categoria: ' . $e->getMessage()
            ];

            header("Location: /sugarbeat_admin/categoria/cadastro");
            exit();
        }
    }
    

    public function editar($id)
    {
        try {
            $categoriaAtual = $this->categoriaService->getCategoria($id);

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $this->atualizar($id, $categoriaAtual);
            } else {

                View::renderWithLayout('categoria/EdicaoCategoriaView', 'config/AppLayout', ['categoria' => $categoriaAtual]);
            }
        } catch (Exception $e) {
            http_response_code(404);
            $_SESSION['alert_message'] = ['type' => 'error', 'title' => 'Erro!', 'text' => $e->getMessage()];
            header("Location: /sugarbeat_admin/categoria");
            exit();
        }
    }

    private function atualizar($id, Categoria $categoriaAtual)
    {
        try {
            $nome = $_POST['nome_categoria'] ?? $categoriaAtual->getNomeCategoria();
            

            $categoria = new Categoria($id, $nome);
            
            $this->categoriaService->atualizarCategoria($categoria);

            $_SESSION['alert_message'] = [
                'type' => 'success',
                'title' => 'Sucesso!',
                'text' => "Categoria '{$nome}' atualizada com sucesso."
            ];
        } catch (Exception $e) {
            $_SESSION['alert_message'] = [
                'type' => 'error',
                'title' => 'Erro!',
                'text' => 'Erro ao atualizar categoria: ' . $e->getMessage()
            ];
        } finally {
            header("Location: /sugarbeat_admin/categoria/editar/" . $id);
            exit();
        }
    }
    

    public function deletar($id)
    {
        try {
            $categoria = $this->categoriaService->getCategoria($id);
            $nome = $categoria->getNomeCategoria();
            
            $this->categoriaService->deletarCategoria($id);
            
            $_SESSION['alert_message'] = [
                'type' => 'success',
                'title' => 'Sucesso!',
                'text' => "Categoria '{$nome}' excluída com sucesso."
            ];
        } catch (Exception $e) {
            $_SESSION['alert_message'] = [
                'type' => 'error',
                'title' => 'Erro!',
                'text' => 'Erro ao deletar categoria: ' . $e->getMessage()
            ];
        } finally {
            header("Location: /sugarbeat_admin/categoria");
            exit();
        }
    }
}