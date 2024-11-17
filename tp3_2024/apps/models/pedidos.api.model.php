<?php

require_once './apps/models/model.php';

class PedidosModel extends Model{


    function getAll(){
        $query = $this->db->prepare ('SELECT * FROM pedidos');
        $query->execute();
        $pedidos = $query->fetchAll(PDO:: FETCH_OBJ);
        return $pedidos;
    }

    function get($id_pedido){
        $query= $this->db->prepare('SELECT * FROM pedidos WHERE id_pedido = ?');
        $query->execute([$id_pedido]);
        $tipo_pedido = $query->fetchAll(PDO:: FETCH_OBJ);
        return $tipo_pedido;
    }

    function delete($id_pedido){
        $query = $this->db->prepare('DELETE FROM pedidos WHERE id_pedido = ?');
        return $query->execute([$id_pedido]);
    }

    function update($fecha , $estado , $total , $id_pedido){
        $query = $this->db->prepare('UPDATE pedidos SET fecha = ? , estado = ? , total = ? WHERE id_pedido = ?');
        $query->execute([$fecha ,$estado , $total , $id_pedido]);
    }

    function insertarPedido($fecha, $estado, $total){
        $query = $this->db->prepare('INSERT INTO pedidos (fecha, estado, total) VALUES (?, ?, ?)');
        $query->execute([$fecha, $estado, $total]);
        return $this->db->lastInsertId();
    }

    function order($sort = null, $order = null){
        $query = $this->db->prepare("SELECT * FROM pedidos ORDER BY $sort $order");
        $query->execute();
        $pedidos = $query->fetchAll(PDO::FETCH_OBJ);
        return $pedidos;
    }
}