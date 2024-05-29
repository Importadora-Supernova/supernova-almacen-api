<?php
// conexion con base de datos 
include '../../conexion/conn.php';
require_once '../../class/pedidosClientes.class.php';
include '../../headers.php';
//import middleware

// declarar array para respuestas 
$response = array();
$products = new PedidosClientes();

date_default_timezone_set('America/Mexico_City');
$fecha = date('Y-m-d H:i:s');

// insertamos cabeceras para permisos 

// validamos si hay conexion 
if($con){
    //if($token_access['token']){
        $methodApi = $_SERVER['REQUEST_METHOD'];

        if($methodApi == 'GET'){
            $palabra = $_GET['nombre'];
            $user_id = $_GET['user_id'];

            $exist = $products->buscarBusquedaPalabra($con,$_GET);

            if(!$exist){
                $products->createSearchWord($con,$_GET,$fecha);
            }

            $busqueda = "%".$palabra."%";
            $response = $products->buscarProductWord($con,$busqueda);
            echo  json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT); 
        }
        
        if($methodApi == 'POST'){
            $_POST = json_decode(file_get_contents('php://input'),true);


            $userId = $_POST['user_id'];
            $codigo = $_POST['codigo'];
            $nombre = $_POST['nombre'];
            $exist = $products->buscarBusquedaPalabra($con,$_POST);

            if(!$exist){
                $products->createSearchProduct($con,$_POST,$fecha);
            }

            $response = $products->getProductsCodigo($con,$codigo);
            echo  json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT); 
        }
}else{
    echo "DB FOUND CONNECTED";
}