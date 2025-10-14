<?php

require_once 'IUsuarioRepository.php';

class UsuarioRepository implements IUsuarioRepository
{
    private $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    private function criarObjetoUsuario(array $data): Usuario
    {
        return new Usuario(
            $data['id_usuario'],
            $data['nome'],
            $data['email'],
            $data['senha'], 
            $data['administrador']
        );
    }

    public function getById($id): ?Usuario
    {
        $stmt = $this->db->prepare("SELECT * FROM usuario WHERE id_usuario = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        return $data ? $this->criarObjetoUsuario($data) : null;
    }
    
    public function getByEmail($email): ?Usuario
    {
        $stmt = $this->db->prepare("SELECT * FROM usuario WHERE email = :email");
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        return $data ? $this->criarObjetoUsuario($data) : null;
    }

    public function getAll(): array
    {
        $stmt = $this->db->query("SELECT * FROM usuario ORDER BY nome ASC");
        $dataList = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $usuarios = [];

        foreach ($dataList as $data) {
            $usuarios[] = $this->criarObjetoUsuario($data);
        }

        return $usuarios;
    }

    public function save(Usuario $usuario): Usuario
    {
        $sql = "INSERT INTO usuario (nome, email, senha, administrador) 
                VALUES (:nome, :email, :senha, :administrador)";
        $stmt = $this->db->prepare($sql);
        
        $stmt->bindValue(':nome', $usuario->getNome());
        $stmt->bindValue(':email', $usuario->getEmail());
        $stmt->bindValue(':senha', $usuario->getSenha()); 
        $stmt->bindValue(':administrador', $usuario->getAdministrador());
        $stmt->execute();

        $usuario->setIdUsuario($this->db->lastInsertId());
        return $usuario;
    }

    public function update(Usuario $usuario): Usuario
    {
        $set = "nome = :nome, email = :email, administrador = :administrador";
        if (!empty($usuario->getSenha())) {
            $set .= ", senha = :senha";
        }
        
        $sql = "UPDATE usuario SET {$set} WHERE id_usuario = :id";
        $stmt = $this->db->prepare($sql);
        
        $stmt->bindValue(':nome', $usuario->getNome());
        $stmt->bindValue(':email', $usuario->getEmail());
        $stmt->bindValue(':administrador', $usuario->getAdministrador());
        
        if (!empty($usuario->getSenha())) {
            $stmt->bindValue(':senha', $usuario->getSenha());
        }
        $stmt->bindValue(':id', $usuario->getIdUsuario(), PDO::PARAM_INT);
        $stmt->execute();

        return $usuario;
    }

    public function delete($id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM usuario WHERE id_usuario = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        
        return $stmt->execute();
    }
}