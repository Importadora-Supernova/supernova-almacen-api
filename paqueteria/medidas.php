<?php
// conexion con base de datos 
include '../conexion/conn.php';
require_once('../class/guias.class.php');
date_default_timezone_set('America/Mexico_City');
// declarar array para respuestas 
$response = array();
$guia = new Guias();
// insertamos cabeceras para permisos 

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");
header("Content-Type: JSON");
header('Content-Type: application/json;charset=utf-8');

if($con){
    
    $methodApi = $_SERVER['REQUEST_METHOD'];

    if($methodApi == 'POST'){
        $_POST = json_decode(file_get_contents('php://input'),true);
        $id  = $_POST['id'];
        $num = $_POST['num_rastreo'];
        $sql = 'UPDATE medidas_almacen SET num_rastreo=? WHERE id=?';
        $resultado = $guia->updateNumeroRastreo($con,$sql,$num,$id);

        if($resultado == 1){
            header("HTTP/1.1 200 OK");
            $response['status'] = 200;
            $response['mensaje'] = "Se ha guardado el número de rastreo correctamente";
            echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
            $con->close();
        }else{
            header("HTTP/1.1 400 OK");
            $response['status'] = 400;
            $response['mensaje'] = 'Error ejecutando peticion';
            echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
        }
    }
}else{
    echo "DB FOUND CONNECTED";
}


?>