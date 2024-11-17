<?php

require_once './apps/models/pedidos.api.model.php';
require_once './apps/views/json.view.php';

class pedidosController {

    private $model;
    private $apiview;
    private $data;

    function __construct() {  

        $this->model = new PedidosModel();
        $this->apiview = new JSONview();

        // lee el body del request
        $this->data = file_get_contents("php://input");
    }

    private function getData() {
        return json_decode($this->data);
    }


    public function getAllpedidos(){

        $parametros = [];

        if (isset($_GET['sort'])) {
            $parametros['sort'] = $_GET['sort'];   
            if (isset($_GET['order'])){
                $parametros['order'] = $_GET['order'];   
            }     
            
            if ($this->validarParametrosOrdenamiento($parametros)) {
                $resultado = $this->model->order($parametros['sort'], $parametros['order']);
                $this->apiview->response($resultado, 200);
            } else {
                $this->apiview->response("Debe proporcionar un criterio de orden válido", 404);
            }
        }
        else{
            $pedidos = $this->model->getAll();
            return $this->apiview->response($pedidos, 200);
        }
    }


    public function getPedidos($params = null){
        $id_pedido = $params[':ID'];

        $pedidos = $this->model->get($id_pedido);
        if($pedidos){
            $this->apiview->response($pedidos,200);
        }
        else{
            $this->apiview->response("no existe el id que esta buscando ",400);
        }
    }

    function deletePedidos($params = null){
        $id_pedido = $params[':ID'];

        $pedidos = $this->model->get($id_pedido);
        
        if($pedidos){
            $this->model->delete($id_pedido);
            $this->apiview->response("se elimino con exito el id: $id_pedido",200);
        }
        else{
            $this->apiview->response("no existe el id que desea eliminar $id_pedido",400);
        }
    }

    function updatePedidos($params = null){
        $id_pedido = $params[':ID'];
        $body = $this->getData(); 

        $pedidos = $this->model->get($id_pedido);

        if($pedidos){
           $fecha = $body->fecha;
           $estado = $body->estado;
           $total = $body->total;

            if (empty ($fecha) || empty ($estado) ||  empty ($total)){
                $this->apiview->response('ingrese de nuevo sus datos',400);
                return;
            }

            $this->model->update($fecha , $estado , $total ,$id_pedido);
            $this->apiview->response("se actualizo",200);
        }
        else{
            $this->apiview->response("no existe en la db el id que quiere actualizar",404); 
        }
    }

    function insertarPedidos($paramns = null ){

        $body = $this->getData(); 
        $fecha = $body->fecha;
        $estado = $body->estado;
        $total = $body->total;

        if (empty ($fecha) || empty ($estado) || empty ($total) ){
            $this->apiview->response('ingrese de nuevo sus datos',400);
            return;
        }
        $id = $this->model->insertarPedido($fecha, $estado, $total);

        if ($id){
            $this->apiview->response('La tarea fue insertada con el id='.$id, 201);
        }
        else{
            $this->apiview->response('La tarea no fue insertada con el id='.$id, 400);
        }
    }

        
    function validarParametrosOrdenamiento($parametros){

        $camposPermitidos = ['id_pedido', 'fecha', 'estado', 'total'];
        $ordenesPermitidas = ['asc', 'desc'];
    
       
        if (!isset($parametros['sort']) || !in_array($parametros['sort'], $camposPermitidos)) {
            return false;
        }
    
        
        if (!isset($parametros['order']) || !in_array($parametros['order'], $ordenesPermitidas)) {
            return false;
        }
    
        
        return true;
    }


}
