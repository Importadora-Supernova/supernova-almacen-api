<?php
// conexion con base de datos 
include '../conexion/conn.php';


date_default_timezone_set('America/Mexico_City');
$fecha = date('Y-m-d H:i:s');

// declarar array para respuestas 
$response = array();
// insertamos cabeceras para permisos 
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");
header("Content-Type: JSON");
header('Content-Type: application/json;charset=utf-8'); 



// validamos si hay conexion 
if($con){
    $methodApi = $_SERVER['REQUEST_METHOD'];


    if($methodApi == 'GET'){
        $_POST = json_decode(file_get_contents('php://input'),true);
        
    }
}else{
    echo "DB FOUND CONNECTED";
}