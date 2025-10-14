<?php
// Certifique-se de que todas as dependências estão incluídas no index.php
// require_once '../../models/ItemPedido.php'; // Removido, confiando no autoloader ou index.php

class PedidoController
{
    private $pedidoService;

    public function __construct(PedidoService $pedidoService)
    {
        $this->pedidoService = $pedidoService;
    }

    /**
     * Rota: /pedido (Listagem de Pedidos) ou /pedido/detalhe/{id} (Visualização)
     */
    public function listar($id = null)
    {
        if ($id) {
            // Se um ID foi fornecido, mostra os detalhes do pedido
            try {
                $dadosPedido = $this->pedidoService->getPedidoComItens($id);
                
                // Renderiza a view de detalhe
                View::renderWithLayout('pedido/DetalhePedidoView', 'config/AppLayout', $dadosPedido);
                
            } catch (Exception $e) {
                // Pedido não encontrado ou erro
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
            // Se nenhum ID, mostra a lista de todos os pedidos
            try {
                $pedidos = $this->pedidoService->listarPedidos();
                
                // Renderiza a listagem de pedidos
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

    /**
     * Rota: /pedido/novo (Simula a criação, mas normalmente é via API/Cliente)
     * Mantida a lógica original para ser chamada internamente ou via API
     */
    public function post()
    {
        // Esta lógica deve ser usada internamente pelo sistema de e-commerce (check-out)
        // e não por uma rota GET/POST de formulário admin. 
        // Vamos encapsulá-la para manter o código limpo, mas manter a funcionalidade.
        
        // Simulação de como receberia os dados (assumindo que o POST está preenchido)
        // NOTA: Em um ambiente MVC real, esta função post() provavelmente seria chamada
        // por um webhook de pagamento ou um endpoint de API, e não diretamente pela UI do Admin.
        
        $pedidoData = filter_input(INPUT_POST, 'pedido', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY) ?? [];
        $itensData = filter_input(INPUT_POST, 'itens', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY) ?? [];

        $id_cliente = $pedidoData['id_cliente'] ?? null;
        $preference_id = $pedidoData['preference_id'] ?? null;
        $descricao_pedido = $pedidoData['descricao_pedido'] ?? null;

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
            $preference_id,
            $descricao_pedido 
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