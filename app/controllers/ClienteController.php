<?php

class PedidoController
{
    private $pedidoService;

    public function __construct(PedidoService $pedidoService)
    {
        $this->pedidoService = $pedidoService;
    }

    
    public function listar($id = null)
    {
        if ($id) {
            try {
                $dadosPedido = $this->pedidoService->getPedidoComItens($id);
                
                View::renderWithLayout('pedido/DetalhePedidoView', 'config/AppLayout', $dadosPedido);
                
            } catch (Exception $e) {
                http_response_code(404);
                $_SESSION['alert_message'] = [
                    'type' => 'error',
                    'title' => 'Erro!',
                    'text' => 'Pedido não encontrado: ' . $e->getMessage()
                ];
                header("Location: /sugarbeat_admin/pedido");
                exit();
            }
        } else {
            try {
                $pedidos = $this->pedidoService->listarPedidos();
                
                View::renderWithLayout('pedido/ListagemPedidoView', 'config/AppLayout', ['listaPedidos' => $pedidos]);
            } catch (Exception $e) {
                 $_SESSION['alert_message'] = [
                    'type' => 'error',
                    'title' => 'Erro!',
                    'text' => 'Erro ao listar pedidos: ' . $e->getMessage()
                ];
                View::renderWithLayout('pedido/ListagemPedidoView', 'config/AppLayout', ['listaPedidos' => []]);
            }
        }
    }


    public function post()
    {
        $pedidoData = filter_input(INPUT_POST, 'pedido', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY) ?? [];
        $itensData = filter_input(INPUT_POST, 'itens', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY) ?? [];

        $id_cliente = $pedidoData['id_cliente'] ?? null;
        $preference_id = $pedidoData['preference_id'] ?? null;

        if (empty($id_cliente) || empty($preference_id) || empty($itensData)) {
            http_response_code(400);
            echo json_encode(['error' => 'Dados de pedido incompletos.']);
            return;
        }

        $pedido = new Pedido(
            null,
            $id_cliente,
            date('Y-m-d H:i:s'),
            0,
            $preference_id
        );

        $itens = [];
        foreach ($itensData as $itemData) {
            $itens[] = new ItemPedido(
                null,
                null,
                $itemData['id_produto'] ?? null,
                $itemData['quantidade'] ?? null,
                $itemData['preco_unitario'] ?? null,
                $itemData['sub_total'] ?? null
            );
        }

        try {
            $novoPedido = $this->pedidoService->criarNovoPedido($pedido, $itens);
            http_response_code(201);
            header('Content-Type: application/json');
            echo json_encode($novoPedido);
        } catch (Exception $e) {
            http_response_code(400);
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Erro ao criar pedido: ' . $e->getMessage()]);
        }
    }

    public function getPedidosPorCliente($clienteId) {
        header('Content-Type: application/json');
        try {
            $pedidos = $this->pedidoService->getPedidosPorCliente($clienteId);
            echo json_encode($pedidos);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
}