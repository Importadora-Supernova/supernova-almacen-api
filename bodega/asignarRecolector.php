<?php
// conexion con base de datos 
include '../conexion/conn.php';
date_default_timezone_set('America/Mexico_City');
//incluir middleware


// declarar array para respuestas 
$response = array();

// insertamos cabeceras para permisos 

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type,Accept, Authorization, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");
header("Content-Type: JSON");
header('Content-Type: application/json;charset=utf-8'); 

$fecha = date('Y-m-d');

$fecha_delete = date('Y-m-d H:i:s');

// validamos si hay conexion 
if($con){
        $methodApi = $_SERVER['REQUEST_METHOD'];


        if($methodApi == 'POST'){
            $_POST = json_decode(file_get_contents('php://input'),true);
            $recolector = $_POST['recolector'];
            $orden      = $_POST['orden'];

            $sql = 'UPDATE folios SET id_operador=? WHERE orden=?';

            $stmt = $con->prepare($sql);
            $stmt->bind_param('ss',$recolector,$orden);
            $result = $stmt->execute();

            if($result == 1){
                header("HTTP/1.1 200");
                $response['status'] = 200;
                $response['mensaje'] = 'Se asigno el recolector a la orden';
                echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT); 
            }else{
                $con->rollback();
                header("HTTP/1.1 400");
                $response['status'] = 400;
                $response['mensaje'] = 'Ocurrio un error al asignar recolector';
                echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT); 
            }
                
        }

    //echo "Informacion".file_get_contents('php://input');

}else{
    echo "DB FOUND CONNECTED";
}
?>