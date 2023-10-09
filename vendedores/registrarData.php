<?php
// conexion con base de datos 
include '../conexion/conn.php';
require('../class/ventasClientes.class.php');
//incluir middleware

date_default_timezone_set('America/Mexico_City');
$fecha = date('Y-m-d H:i:s');

// declarar array para respuestas 
$response = array();
$cliente = new pedidoCliente();

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


    if($methodApi == 'POST'){
        $_POST = json_decode(file_get_contents('php://input'),true);

        try{
            $ip = $_POST['ip'];
            $country   = $_POST['country_name'];
            $latitude  = $_POST['latitude'];
            $city      = $_POST['city'];
            $longitude  = $_POST['longitude'];
            $code       = $_POST['calling_code'];

            $sql = 'INSERT INTO usuarios_ofertas (ip,fecha,country_name,latitude,city,longitude,calling_code) VALUES (?,?,?,?,?,?,?)';
            $stmt = $con->prepare($sql);
            $stmt->bind_param("sssssss",$ip,$fecha,$country,$latitude,$city,$longitude,$code);
            $result = $stmt->execute();
            if($result){
                header("HTTP/1.1 200 OK");
                $response['status'] = 200;
                $response['mensaje'] = 'Registros insertados correctamente';
                echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
            }else{
                header("HTTP/1.1 400");
                $response['status'] = 400;
                $response['mensaje'] = 'No se pudo guardar los registros';
                echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
            }
        }catch(Exception $e){
            header("HTTP/1.1 400");
            $response['status'] = 400;
            $response['mensaje'] = $e->getMessage();
            echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
        }
    }
}else{
    echo "DB FOUND CONNECTED";
}