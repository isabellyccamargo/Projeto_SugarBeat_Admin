<?php


class ProdutoHistoricoService
{
    private $historicoRepository;

    public function __construct(IProdutoHistoricoRepository $historicoRepository)
    {
        $this->historicoRepository = $historicoRepository;
    }

    public function registrarHistorico(
        string $operacao, 
        int $id_produto, 
        int $id_usuario, 
        string $valorAntigo, 
        string $valorAtual
    ): ProdutoHistorico {
        
        $historico = new ProdutoHistorico(
            null,
            date('Y-m-d H:i:s'), 
            $id_usuario,
            strtoupper($operacao),
            $valorAntigo,
            $valorAtual,
            $id_produto
        );
        
        return $this->historicoRepository->save($historico);
    }
    
  
    public function listarHistoricoPorProduto(int $id_produto): array
    {
        return $this->historicoRepository->getByProdutoId($id_produto);
    }
    
}