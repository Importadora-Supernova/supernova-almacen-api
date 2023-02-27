<?php
date_default_timezone_set('America/Mexico_City');
// conexion con base de datos 
include '../conexion/conn.php';

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
    
    //echo "Informacion".file_get_contents('php://input');

   $methodApi = $_SERVER['REQUEST_METHOD'];

   switch($methodApi){
       // metodo post 
       case 'POST':
                $_POST = json_decode(file_get_contents('php://input'),true);
        break;
        case 'GET':
            $sql = 'SELECT *FROM qr WHERE listo=""';
            $result = mysqli_query($con,$sql);
        $i=0;
        while($row = mysqli_fetch_assoc($result)){
            $response[$i]['id'] =  $row['id'];
            $response[$i]['qr'] =  $row['qr'];
            $response[$i]['folio'] =  $row['folio'];
            $response[$i]['bodega'] =  $row['bodega'];
            $response[$i]['id_bodega'] =  $row['id_bodega'];
            $response[$i]['listo'] =  $row['listo'];
            $i++;
        }
        echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
        break;
        case 'UPDATE':

        break;
        default:
        break;
    }
}else{
    echo 'DB desconectada';
}