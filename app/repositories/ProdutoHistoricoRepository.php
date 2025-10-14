<?php

require_once 'IProdutoHistoricoRepository.php';

class ProdutoHistoricoRepository implements IProdutoHistoricoRepository
{
    private $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    private function criarObjetoHistorico(array $data): ProdutoHistorico
    {
        return new ProdutoHistorico(
            $data['id_historico'],
            $data['data'],
            $data['id_usuario'],
            $data['operacao'],
            $data['valor_antigo'],
            $data['valor_atual'],
            $data['id_produto']
        );
    }

    public function getById($id): ?ProdutoHistorico
    {
        $stmt = $this->db->prepare("SELECT * FROM produto_historico WHERE id_historico = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        return $data ? $this->criarObjetoHistorico($data) : null;
    }
    
    public function getByProdutoId($id_produto): array
    {
        $stmt = $this->db->prepare("SELECT * FROM produto_historico WHERE id_produto = :id_produto ORDER BY data DESC");
        $stmt->bindValue(':id_produto', $id_produto, PDO::PARAM_INT);
        $stmt->execute();
        $dataList = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $historicos = [];

        foreach ($dataList as $data) {
            $historicos[] = $this->criarObjetoHistorico($data);
        }

        return $historicos;
    }

    public function save(ProdutoHistorico $historico): ProdutoHistorico
    {
        $sql = "INSERT INTO produto_historico (data, id_usuario, operacao, valor_antigo, valor_atual, id_produto) 
                VALUES (:data, :id_usuario, :operacao, :valor_antigo, :valor_atual, :id_produto)";
        $stmt = $this->db->prepare($sql);
        
        $stmt->bindValue(':data', $historico->getData());
        $stmt->bindValue(':id_usuario', $historico->getIdUsuario(), PDO::PARAM_INT);
        $stmt->bindValue(':operacao', $historico->getOperacao());
        $stmt->bindValue(':valor_antigo', $historico->getValorAntigo());
        $stmt->bindValue(':valor_atual', $historico->getValorAtual());
        $stmt->bindValue(':id_produto', $historico->getIdProduto(), PDO::PARAM_INT);
        $stmt->execute();

        $historico->setIdHistorico($this->db->lastInsertId());
        return $historico;
    }
}