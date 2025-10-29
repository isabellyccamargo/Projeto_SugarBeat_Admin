<?php

// Assumindo que você tem o ClienteService injetado
class ClienteController
{
    private $clienteService;

    public function __construct(ClienteService $clienteService)
    {
        $this->clienteService = $clienteService;
    }

    public function listar($id = null)
    {
        // Lógica para listar todos ou ver detalhes de um cliente
        try {
            if ($id) {
                $cliente = $this->clienteService->getCliente($id);
                // Renderizar view de detalhe
                View::renderWithLayout('cliente/DetalheClienteView', 'config/AppLayout', ['cliente' => $cliente]);
            } else {
                $clientes = $this->clienteService->listarTodosClientes();
                // Renderizar view de listagem
                View::renderWithLayout('cliente/ListagemClienteView', 'config/AppLayout', ['listaClientes' => $clientes]);
            }
        } catch (Exception $e) {
            // Tratamento de erro...
            error_log("Erro no ClienteController: " . $e->getMessage());
            http_response_code(500);
            // ...
        }
    }
    
}