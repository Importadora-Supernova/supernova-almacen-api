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
            $id = $_GET['id'];
            $response = $products->getSearchedWords($con,$id);
            echo  json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT); 
        }
        
}else{
    echo "DB FOUND CONNECTED";
}