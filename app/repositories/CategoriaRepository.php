<?php

require_once 'ICategoriaRepository.php';


class CategoriaRepository implements ICategoriaRepository
{
    private $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function getById($id): ?Categoria
    {
        $stmt = $this->db->prepare("SELECT id_categoria, nome_categoria FROM categoria WHERE id_categoria = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$data) {
            return null;
        }

        return new Categoria(
            $data['id_categoria'],
            $data['nome_categoria']
        );
    }
    
    public function getByNome($nome): ?Categoria
    {
        $stmt = $this->db->prepare("SELECT id_categoria, nome_categoria FROM categoria WHERE nome_categoria = :nome");
        $stmt->bindValue(':nome', $nome, PDO::PARAM_STR);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$data) {
            return null;
        }

        return new Categoria(
            $data['id_categoria'],
            $data['nome_categoria']
        );
    }

    public function getAll(): array
    {
        $stmt = $this->db->query("SELECT id_categoria, nome_categoria FROM categoria ORDER BY nome_categoria ASC");
        $dataList = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $categorias = [];

        foreach ($dataList as $data) {
            $categorias[] = new Categoria(
                $data['id_categoria'],
                $data['nome_categoria']
            );
        }

        return $categorias;
    }

    public function save(Categoria $categoria): Categoria
    {
        $stmt = $this->db->prepare("INSERT INTO categoria (nome_categoria) VALUES (:nome)");
        $stmt->bindValue(':nome', $categoria->getNomeCategoria());
        $stmt->execute();

        $categoria->setIdCategoria($this->db->lastInsertId());
        return $categoria;
    }

    public function update(Categoria $categoria): Categoria
    {
        $stmt = $this->db->prepare("UPDATE categoria SET nome_categoria = :nome WHERE id_categoria = :id");
        $stmt->bindValue(':nome', $categoria->getNomeCategoria());
        $stmt->bindValue(':id', $categoria->getIdCategoria(), PDO::PARAM_INT);
        $stmt->execute();

        return $categoria;
    }

    public function delete($id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM categoria WHERE id_categoria = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        
        return $stmt->execute();
    }

    public function countAll(): int
    {
        $stmt = $this->db->query("SELECT COUNT(*) FROM categoria;");
        return (int) $stmt->fetchColumn();
    }

    public function getPaginated(int $limit, int $offset): array
    {
        $sql = "SELECT id_categoria, nome_categoria 
                FROM categoria 
                ORDER BY nome_categoria ASC
                LIMIT :limit OFFSET :offset";

        $stmt = $this->db->prepare($sql);
        
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        
        $stmt->execute();

        $categoriasData = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $categorias = [];

        foreach ($categoriasData as $data) {
            $categorias[] = new Categoria(
                $data['id_categoria'],
                $data['nome_categoria']
            );
        }

        return $categorias;
    }
}