<?php

// conexion con base de datos 
include '../conexion/conn.php';
require_once('../class/pedidosTienda.class.php');

// declarar array para respuestas 
$response = array();

$pedido = new pedidosTienda();

date_default_timezone_set('America/Mexico_City');
$fecha = date('Y-m-d H:i:s');

// insertamos cabeceras para permisos 

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");
header("Content-Type: JSON");
header('Content-Type: application/json;charset=utf-8'); 

if($con){
    
    $methodApi = $_SERVER['REQUEST_METHOD'];

    
    if($methodApi == 'PUT'){
        try{
            $_PUT = json_decode(file_get_contents('php://input'),true);
            $datos = $_PUT;
            $id    = $_PUT['id_caja_envio'];

            $sql = 'UPDATE  tienda_cajas_envio SET peso=?,alto=?,ancho=?,largo=? WHERE id_caja_envio=?';
            $result = $pedido->updateMedidaCaja($con,$sql,$datos,$id);

            if($result == 1){
                header("HTTP/1.1 200 OK");
                $response['status'] = 200;
                $response['mensaje'] = 'Se actualizo la medida correctamente';
                echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
            }else{
                header("HTTP/1.1 400");
                $response['status'] = 400;
                $response['mensaje'] = 'No se pudo actualizar';
                echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
            }
        }catch(Exception $e){
            header("HTTP/1.1 400");
            $response['status'] = 400;
            $response['mensaje'] = 'No se pudo actualizar  '.$e;
            echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
        }
    }
}else{
echo "DB FOUND CONNECTED";
}

?>