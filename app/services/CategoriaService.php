<?php

class CategoriaService
{
    private $categoriaRepository;

    public function __construct(ICategoriaRepository $categoriaRepository)
    {
        $this->categoriaRepository = $categoriaRepository;
    }

    
    public function listarTodasCategorias(): array
    {
        return $this->categoriaRepository->getAll();
    }

    public function getCategoria($id): Categoria
    {
        $categoria = $this->categoriaRepository->getById($id);

        if (!$categoria) {
            throw new Exception("Categoria com ID $id não encontrada.");
        }
        return $categoria;
    }
    
    public function criarNovaCategoria(Categoria $categoria): Categoria
    {
        $this->validarNomeCategoria($categoria->getNomeCategoria(), null);

        return $this->categoriaRepository->save($categoria);
    }
    
    public function atualizarCategoria(Categoria $categoria): Categoria
    {
        if (empty($categoria->getIdCategoria())) {
             throw new Exception("ID da categoria é obrigatório para atualização.");
        }
        
        $this->validarNomeCategoria($categoria->getNomeCategoria(), $categoria->getIdCategoria());
        
        return $this->categoriaRepository->update($categoria);
    }

    private function validarNomeCategoria(string $nome, $currentId)
    {
        $nome = trim($nome);
        if (empty($nome)) {
            throw new Exception("O nome da categoria é obrigatório.");
        }
        
        // Verifica unicidade
        $categoriaExistente = $this->categoriaRepository->getByNome($nome);
        
        if ($categoriaExistente && $categoriaExistente->getIdCategoria() != $currentId) {
            throw new Exception("Já existe uma categoria com o nome '{$nome}'.");
        }

    }

    public function getCategoriasPaginadas(int $paginaAtual, int $itensPorPagina): array
    {
        $totalCategorias = $this->categoriaRepository->countAll();
        
        $totalPaginas = $itensPorPagina > 0 ? ceil($totalCategorias / $itensPorPagina) : 1;
        
        $paginaAtual = max(1, min((int)$paginaAtual, $totalPaginas));
        
        $offset = ($paginaAtual - 1) * $itensPorPagina;

        $categorias = $this->categoriaRepository->getPaginated($itensPorPagina, $offset);

        return [
            'categorias' => $categorias,
            'pagina_atual' => $paginaAtual,
            'total_paginas' => (int) $totalPaginas,
            'total_categorias' => $totalCategorias
        ];
    }

    public function excluirCategoria(int $id): void
    {
        $categoria = $this->categoriaRepository->getById($id);
        
        if (!$categoria) {
            throw new Exception("Categoria com ID $id não encontrada para exclusão.");
        }
        $sucesso = $this->categoriaRepository->delete($id);

        if (!$sucesso) {
            throw new Exception("Falha ao excluir a categoria.");
        }
    }

    
}