<?php

class Pedido implements JsonSerializable
{

    private $id_pedido;
    private $id_cliente;
    private $data;
    private $total;
    private $preference_id;

    public function __construct(
        $id_pedido,
        $id_cliente,
        $data,
        $total,
        $preference_id,
    ) {
        $this->id_pedido = $id_pedido;
        $this->id_cliente = $id_cliente;
        $this->data = $data;
        $this->total = $total;
        $this->preference_id = $preference_id;
    }

    public function getIdPedido()
    {
        return $this->id_pedido;
    }
    public function setIdPedido($id_pedido): void
    {
        $this->id_pedido = $id_pedido;
    }

    public function getIdCliente()
    {
        return $this->id_cliente;
    }
    public function setIdCliente($id_cliente): void
    {
        $this->id_cliente = $id_cliente;
    }

    public function getData()
    {
        return $this->data;
    }
    public function setData($data): void
    {
        $this->data = $data;
    }

    public function getTotal()
    {
        return $this->total;
    }
     public function setTotal($total): void
    {
        $this->total = $total;
    }

    public function getPreference_id()
    {
        return $this->preference_id;
    }
    public function setPreference_id($preference_id): void
    {
        $this->preference_id = $preference_id;
    }

    public function jsonSerialize(): mixed
    {

         return [
            'id_pedido' => $this->id_pedido,
            'id_cliente' => $this->id_cliente,
            'data_pedido' => $this->data,
            'total' => $this->total,
            'preference_id' => $this->preference_id
        ];

    }
}
