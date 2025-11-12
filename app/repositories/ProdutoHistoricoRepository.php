<?php

require_once 'IProdutoHistoricoRepository.php';

class ProdutoHistoricoRepository implements IProdutoHistoricoRepository
{
    private $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function getHistoricoByProdutoId($id_produto, int $limit, int $offset): array
    {
        $stmt = $this->db->prepare("SELECT * FROM produto_historico WHERE id_produto = :id_produto ORDER BY id_historico DESC LIMIT :limit OFFSET :offset");
        $stmt->bindValue(':id_produto', $id_produto, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $dataList = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $historicos = [];

        foreach ($dataList as $data) {
            $historicos[] = $this->criarObjetoHistorico($data);
        }

        return $historicos;
    }

    public function countHistoricoByProdutoId(int $id_produto): int
    {
        $stmt = $this->db->prepare("SELECT count(1) FROM produto_historico WHERE id_produto = :id_produto");
        $stmt->bindValue(':id_produto', $id_produto, PDO::PARAM_INT);
        $stmt->execute();
        return (int) $stmt->fetchColumn();
    }

    private function criarObjetoHistorico(array $data): ProdutoHistorico
    {
        return new ProdutoHistorico(
            $data['id_historico'],
            $data['data'],
            $data['operacao'],
            $data['valor_antigo'],
            $data['valor_atual'],
            $data['id_produto'],
            $data['campo']
        );
    }
}
