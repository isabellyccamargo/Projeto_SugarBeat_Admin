<?php

require_once 'IUsuarioRepository.php';

class UsuarioRepository implements IUsuarioRepository
{
    private $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function getById($id): ?Usuario
    {
        $stmt = $this->db->prepare("SELECT * FROM usuario WHERE id_usuario = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        // Criação Direta (como no ClienteRepository)
        if (!$data) {
            return null;
        }

        return new Usuario(
            $data['id_usuario'],
            $data['nome'],
            $data['email'],
            $data['senha'],
            $data['administrador']
        );
    }

    public function getByEmail($email): ?Usuario
    {
        $stmt = $this->db->prepare("SELECT id_usuario, nome, email, senha, administrador 
            FROM usuario 
            WHERE email = :email");
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$data) {
            return null;
        }

        $usuario = new Usuario(); // Instancia o objeto vazio

        // Atribui os valores um por um via Setter
        $usuario->setIdUsuario($data['id_usuario']);
        $usuario->setNome($data['nome']); // Garante que o nome é atribuído
        $usuario->setEmail($data['email']);
        $usuario->setSenha($data['senha']);
        $usuario->setAdministrador($data['administrador']);

        return $usuario;
    }

    public function getAll(?string $adminStatus = null): array
    {
        $sql = "SELECT * FROM usuario";
        $params = [];

        if (in_array($adminStatus, ['S', 'N'])) {
            $sql .= " WHERE administrador = :admin";
            $params[':admin'] = $adminStatus;
        }

        $sql .= " ORDER BY nome ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        $dataList = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $usuarios = [];

        foreach ($dataList as $data) {
            $usuarios[] = new Usuario(
                $data['id_usuario'],
                $data['nome'],
                $data['email'],
                $data['senha'],
                $data['administrador']
            );
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

    public function countAll(?string $adminFilter): int
    {
        $sql = "SELECT COUNT(*) FROM usuario";
        $params = [];

        // Converte o filtro string ('true'/'false') para um valor binário (1/0)
        $adminValue = null;
        if ($adminFilter !== null) {
            $adminValue = ($adminFilter === 'true' ? 1 : 0);
            $sql .= " WHERE is_admin = :is_admin";
            $params[':is_admin'] = $adminValue;
        }

        $stmt = $this->db->prepare($sql);

        // Faz o bind condicionalmente
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value, PDO::PARAM_INT);
        }

        $stmt->execute();
        return (int) $stmt->fetchColumn();
    }

     public function getPaginated(int $limit, int $offset, ?string $adminFilter): array
    {
        $sql = "SELECT id_usuario, nome, email, administrador FROM usuario";
        $params = [
            ':limit' => $limit, 
            ':offset' => $offset
        ];
        
        // 1. Aplica a cláusula WHERE condicionalmente
        $adminValue = null;
        if ($adminFilter !== null) {
            $adminValue = ($adminFilter === 'true' ? 1 : 0);
            $sql .= " WHERE administrador = :administrador";
            $params[':administrador'] = $adminValue;
        }

        // 2. Adiciona ordenação e limites
        $sql .= " ORDER BY id_usuario ASC LIMIT :limit OFFSET :offset";

        $stmt = $this->db->prepare($sql);
        
        // 3. Faz o bind dos valores
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        
        if ($adminFilter !== null) {
             // O is_admin é um TINYINT ou BOOLEAN, então usamos PARAM_INT
            $stmt->bindValue(':administrador', $adminValue, PDO::PARAM_INT); 
        }

        $stmt->execute();

        $usuariosData = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $usuarios = [];

        // 4. Mapeamento para Objetos (Você precisará garantir que seu modelo 'Usuario' tenha um construtor compatível)
        foreach ($usuariosData as $data) {
            // Supondo que o construtor de Usuario aceita esses campos
            $usuarios[] = new Usuario(
                $data['id_usuario'],
                $data['nome'],
                $data['email'],
                // etc. (adicione todos os campos necessários do seu modelo Usuario)
                (bool) $data['administrador'] // Conversão de 1/0 para booleano
            );
        }

        return $usuarios;
    }
    
}
