<?php

require_once 'IProdutoRepository.php';

class ProdutoRepository implements IProdutoRepository
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getById($id)
    {
        $stmt = $this->db->prepare("SELECT pro.id_produto, pro.nome, pro.ativo, pro.preco, pro.imagem, cat.nome_categoria " .
            "  FROM produto pro " .
            " INNER JOIN categoria cat on cat.id_categoria = pro.id_categoria WHERE pro.id_produto = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $produtoData = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($produtoData) {
            return new Produto(
                $produtoData['id_produto'],
                $produtoData['nome'],
                $produtoData['preco'],
                $produtoData['imagem'],
                $produtoData['nome_categoria'],
                $produtoData['ativo']
            );
        }
    }


    public function getAll()
    {
        $stmt = $this->db->query("SELECT pro.id_produto, pro.nome, pro.ativo, pro.preco, pro.imagem, pro.estoque, cat.nome_categoria " .
            "  FROM produto pro " .
            " INNER JOIN categoria cat on cat.id_categoria = pro.id_categoria;");
        $produtosData = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $produtos = [];

        foreach ($produtosData as $data) {
            $produtos[] = new Produto(
                $data['id_produto'],
                $data['nome'],
                $data['preco'],
                $data['estoque'],
                $data['imagem'],
                $data['nome_categoria'],
                $data['ativo']
            );
        }
        return $produtos;
    }

    public function save($produto)
    {
        $stmt = $this->db->prepare("INSERT INTO produto (nome, preco, imagem, id_categoria, estoque, ativo) VALUES (:nome, :preco, :imagem, :id_categoria, :estoque, :ativo)");

        $stmt->bindValue(':nome', $produto->getNome());
        $stmt->bindValue(':preco', $produto->getPreco());
        $stmt->bindValue(':imagem', $produto->getImagem());
        $stmt->bindValue(':id_categoria', $produto->getIdCategoria(), PDO::PARAM_INT);
        $stmt->bindValue(':estoque', $produto->getEstoque(), PDO::PARAM_INT);
        $stmt->bindValue(':ativo', $produto->getAtivo());

        $stmt->execute();

        $produto->setIdProduto($this->db->lastInsertId());
        return $produto;
    }

    public function update($produto)
    {
        $stmt = $this->db->prepare("UPDATE produto SET nome = :nome, preco = :preco, imagem = :imagem, id_categoria = :id_categoria, estoque = :estoque, ativo = :ativo WHERE id_produto = :id");

        $stmt->bindValue(':id', $produto->getIdProduto(), PDO::PARAM_INT);
        $stmt->bindValue(':nome', $produto->getNome());
        $stmt->bindValue(':imagem', $produto->getImagem());
        $stmt->bindValue(':id_categoria', $produto->getIdCategoria(), PDO::PARAM_INT);
        $stmt->bindValue(':estoque', $produto->getEstoque(), PDO::PARAM_INT);
        $stmt->bindValue(':ativo', $produto->getAtivo());
        
        $stmt->execute();
        return $produto;
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM produto WHERE id_produto = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function countAll(): int
    {
        $stmt = $this->db->query("SELECT COUNT(*) FROM produto;");
        return (int) $stmt->fetchColumn();
    }

    // NOVO MÉTODO 2: Busca produtos com Paginação
    public function getPaginated(int $limit, int $offset): array
    {
        $sql = "SELECT pro.id_produto, pro.nome, pro.ativo, pro.preco, pro.imagem,
                   pro.estoque, cat.nome_categoria
            FROM produto pro
            INNER JOIN categoria cat ON cat.id_categoria = pro.id_categoria
            ORDER BY pro.id_produto ASC
            LIMIT :limit OFFSET :offset";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        $produtosData = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $produtos = [];

        foreach ($produtosData as $data) {
            $produtos[] = new Produto(
                $data['id_produto'],
                $data['nome'],
                $data['preco'],
                $data['imagem'],
                $data['nome_categoria'],
                $data['estoque'],
                $data['ativo']
            );
        }

        return $produtos;
    }
}
