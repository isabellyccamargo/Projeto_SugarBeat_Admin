<?php

interface IPedidoRepository
{
    public function getById($id);
    public function getAll();
    public function save($pedido);
    public function getPedidosPorCliente($id_cliente);
}
