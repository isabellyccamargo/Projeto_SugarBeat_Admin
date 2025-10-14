<?php

interface IProdutoHistoricoRepository
{
    public function getById($id): ?ProdutoHistorico;
    public function getByProdutoId($id_produto): array;
    public function save(ProdutoHistorico $historico): ProdutoHistorico;
}