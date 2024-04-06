<?php
// conexion con base de datos 
include '../conexion/conn.php';
//incluir middleware
//include '../middleware/validarToken.php';


// declarar array para respuestas 
$response = array();

// insertamos cabeceras para permisos 

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type,Authorization, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");
header("Content-Type: JSON");
header('Content-Type: application/json;charset=utf-8'); 



// validamos si hay conexion 
if($con){
    $methodApi = $_SERVER['REQUEST_METHOD'];

    if($methodApi == 'POST'){
        $_POST = json_decode(file_get_contents('php://input'),true);
        
        $sql = 'CALL NotificationsAlmacen()';

        if($stmt = $con->prepare($sql)){
            $stmt->execute();
            $result = $stmt->get_result(); 
            $response = $result->fetch_assoc(); 
            echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
        }
    }
}else{
    echo "DB FOUND CONNECTED";
}