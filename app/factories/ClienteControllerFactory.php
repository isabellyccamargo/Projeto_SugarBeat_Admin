<?php

class ClienteControllerFactory
{
    public static function create(): ClienteController
    {
        $pdo = Connection::connect(); 
        $clienteRepository = new ClienteRepository($pdo); 
        $clienteService = new ClienteService($clienteRepository); 
        return new ClienteController($clienteService);
    }
}