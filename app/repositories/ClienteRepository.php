<?php

require_once 'IClienteRepository.php';

class ClienteRepository implements IClienteRepository
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getById($id): ?Cliente
    {
        $stmt = $this->db->prepare("SELECT * FROM cliente WHERE id_cliente = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $clienteData = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$clienteData) {
            return null; 
        }

        return new Cliente(
            $clienteData['id_cliente'],
            $clienteData['nome'],
            $clienteData['cpf'],
            $clienteData['email'],
            $clienteData['senha'],
            $clienteData['cidade'],
            $clienteData['bairro'],
            $clienteData['rua'],
            $clienteData['numero_da_casa'],
            $clienteData['data_cadastro'] ?? null 
        );
    }

    public function getClienteByEmail($email): ?Cliente
    {
        $stmt = $this->db->prepare("SELECT * FROM cliente WHERE email = :email");
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        $clienteData = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$clienteData) {
            return null; 
        }

        return new Cliente(
            $clienteData['id_cliente'],
            $clienteData['nome'],
            $clienteData['cpf'],
            $clienteData['email'],
            $clienteData['senha'],
            $clienteData['cidade'],
            $clienteData['bairro'],
            $clienteData['rua'],
            $clienteData['numero_da_casa'],
            $clienteData['data_cadastro'] ?? null 
        );
    }
    
    public function getAll(): array
    {
        $stmt = $this->db->query("SELECT * FROM cliente ORDER BY nome ASC");
        $clientesData = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $clientes = [];

        foreach ($clientesData as $clienteData) {
            $clientes[] = new Cliente(
                $clienteData['id_cliente'],
                $clienteData['nome'],
                $clienteData['cpf'],
                $clienteData['email'],
                $clienteData['senha'],
                $clienteData['cidade'],
                $clienteData['bairro'],
                $clienteData['rua'],
                $clienteData['numero_da_casa'],
                $clienteData['data_cadastro'] ?? null
            );
        }

        return $clientes;
    }


}