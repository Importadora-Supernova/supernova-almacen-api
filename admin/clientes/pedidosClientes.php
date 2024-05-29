<?php
// conexion con base de datos 
include '../../conexion/conn.php';
require_once '../../class/pedidosClientes.class.php';
include '../../headers.php';
//import middleware

// declarar array para respuestas 
$response = array();

$cliente = new PedidosClientes();

date_default_timezone_set('America/Mexico_City');
$fecha = date('Y-m-d H:i:s');

// insertamos cabeceras para permisos 

// validamos si hay conexion 
if($con){
    //if($token_access['token']){
        $methodApi = $_SERVER['REQUEST_METHOD'];

        if($methodApi == 'GET'){

            if(isset($_GET['id'])){
                $id = $_GET['id'];
                $response = $cliente->getAllPedidosCliente($con,$id);

                echo  json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);   
            }else{
                $favorite = $_GET['favorites'];
                $response = $cliente->getAllProductsFavorites($con,$favorite);
                echo  json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);   
            }
            
        }
        
        if($methodApi == 'POST'){
            $_POST = json_decode(file_get_contents('php://input'),true);

            $id_user     = $_POST['id_user'];
            $id_producto = $_POST['id_producto'];

            if($id_user != null && $id_producto != null){
                $res = $cliente->createProductFavorite($con,$_POST,$fecha);
                if($res == 1){
                    header("HTTP/1.1 200 OK");
                    $response['status'] = 200;
                    $response['mensaje'] = 'Producto se agrego a favoritos';
                    echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                }else{
                    header("HTTP/1.1 400");
                    $response['status'] = 400;
                    $response['mensaje'] = 'No se pudo Guardar el registro';
                    echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                }
            }else{
                header("HTTP/1.1 400");
                $response['status'] = 400;
                $response['mensaje'] = 'Ocurrio un error,faltan datos';
                echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
            }
        }
}else{
    echo "DB FOUND CONNECTED";
}