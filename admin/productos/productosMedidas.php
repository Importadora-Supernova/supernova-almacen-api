<?php
// conexion con base de datos 
include '../../conexion/conn.php';
require_once '../../class/products.class.php';
include '../../headers.php';
//import middleware

// declarar array para respuestas 
$response = array();

$producto = new Producto();

date_default_timezone_set('America/Mexico_City');
$fecha = date('Y-m-d H:i:s');

// insertamos cabeceras para permisos 

// validamos si hay conexion 
if($con){
    //if($token_access['token']){
        $methodApi = $_SERVER['REQUEST_METHOD'];

        if($methodApi == 'POST'){
            $_POST = json_decode(file_get_contents('php://input'),true);
            
            $jsonCodigos = $_POST['codigos'];
            // Crear la lista de códigos para la consulta dinámica
            $codigos_lista = "'" . implode("','", $jsonCodigos) . "'";
            $response = $producto->getMedidasProducts($con,$codigos_lista);
            echo  json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT); 
        }

}else{
    echo "DB FOUND CONNECTED";
}