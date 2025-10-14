<?php

class ClienteService
{
    private $clienteRepository;

    public function __construct(IClienteRepository $clienteRepository)
    {
        $this->clienteRepository = $clienteRepository;
    }

    public function listarTodosClientes(): array
    {
        return $this->clienteRepository->getAll();
    }
    
    public function getCliente($id): Cliente
    {
        $cliente = $this->clienteRepository->getById($id);

        if (!$cliente) {
            throw new Exception("Cliente com ID $id não encontrado.");
        }
        return $cliente;
    }

    public function getClienteByEmail(string $email): ?Cliente
    {
        return $this->clienteRepository->getClienteByEmail($email);
    }

    
    private function validarDadosComuns(Cliente $cliente, bool $isNew = false)
    {
        if (empty($cliente->getNome())) {
            throw new Exception("O nome do cliente é obrigatório.");
        }
        
        $cpfLimpo = $this->removerMascaraCpf($cliente->getCpf());
        if (strlen($cpfLimpo) !== 11 || !is_numeric($cpfLimpo)) {
            throw new Exception("CPF inválido ou incompleto.");
        }
        $cliente->setCpf($cpfLimpo);
        
        if (!filter_var($cliente->getEmail(), FILTER_VALIDATE_EMAIL)) {
            throw new Exception("E-mail inválido.");
        }

        // Verifica unicidade do E-mail/CPF (apenas se for novo ou se o email/cpf tiver mudado)
        $clienteExistenteEmail = $this->clienteRepository->getClienteByEmail($cliente->getEmail());
        if ($clienteExistenteEmail && ($isNew || $clienteExistenteEmail->getIdCliente() !== $cliente->getIdCliente())) {
            throw new Exception("E-mail já cadastrado.");
        }
        
        // Validação de endereço
        if (empty($cliente->getCidade()) || empty($cliente->getRua())) {
            throw new Exception("Cidade e Rua são obrigatórios.");
        }
    }

    private function removerMascaraCpf(string $texto): string
    {
        return str_replace(['.', '-'], '', $texto);
    }
}