<?php
// insertamos cabeceras para permisos 
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept,Authorization, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");
header('Content-Type: application/json;charset=utf-8'); 


if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(204); // Opcional: responde sin contenido
    exit;
}

// conexion con base de datos 
include '../conexion/conn.php';
require('../class/ventasClientes.class.php');
//incluir middleware
include '../middleware/validarToken.php';

date_default_timezone_set('America/Mexico_City');
$fecha = date('Y-m-d H:i:s');

// declarar array para respuestas 
$response = array();
$cliente = new pedidoCliente();




// validamos si hay conexion 
if($con){
    if($token_access['token']){
        $methodApi = $_SERVER['REQUEST_METHOD'];
    

        if($methodApi == 'POST'){

            $_POST = json_decode(file_get_contents('php://input'),true);
            $orden = $_POST['orden'];

            try{

                $procees = $cliente->updatePriceCost($con,$orden);

                if($procees){
                    header("HTTP/1.1 200");
                    $response['status'] = 200;
                    $response['mensaje'] = 'Se actualizaron los precios correctamente';
                    echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                }else{
                    header("HTTP/1.1 400");
                    $response['status'] = 400;
                    $response['mensaje'] = 'Ocurrio un error, intentalo nuevamente';
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
        header("HTTP/1.1 401");
        $response['status'] = 401;
        $response['mensaje'] = $token_access['validate'];
        echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
    }
}else{
    echo "DB FOUND CONNECTED";
}