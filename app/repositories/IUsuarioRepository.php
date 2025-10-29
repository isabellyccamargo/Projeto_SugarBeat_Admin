<?php

interface IUsuarioRepository
{
    public function getById($id): ?Usuario;
    public function getAll(): array;
    public function save(Usuario $usuario): Usuario;
    public function update(Usuario $usuario): Usuario;
    public function getByEmail($email): ?Usuario;
}