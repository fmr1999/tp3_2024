<?php

    require_once 'config.php';
    require_once './apps/libs/router.php';
    require_once './apps/controllers/pedidos.api.controller.php';

    $router = new Router();

    #                 endpoint          verbo             controller             método
    $router->addRoute('pedidos',       'GET',    'pedidosController',    'getAllPedidos' );
    $router->addRoute('pedidos/:ID',   'GET',    'pedidosController',     'getPedidos');
    $router->addRoute('pedidos/:ID',   'DELETE', 'pedidosController',     'deletePedidos');
    $router->addRoute('pedidos/:ID',   'PUT',    'pedidosController',    'updatePedidos');
    $router->addRoute('pedidos',       'POST',   'pedidosController',    'insertarPedidos');
    $router->route($_GET['resource'], $_SERVER['REQUEST_METHOD']);