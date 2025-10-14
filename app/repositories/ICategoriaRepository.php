<?php

interface ICategoriaRepository
{
    public function getById($id): ?Categoria;
    public function getAll(): array;
    public function save(Categoria $categoria): Categoria;
    public function update(Categoria $categoria): Categoria;
    public function delete($id): bool;
    public function getByNome($nome): ?Categoria;
}