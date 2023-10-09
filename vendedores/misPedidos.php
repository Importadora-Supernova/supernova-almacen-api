<?php
// conexion con base de datos 
include '../conexion/conn.php';
//incluir middleware
include '../middleware/validarToken.php';

date_default_timezone_set('America/Mexico_City');
$fecha = date('Y-m-d H:i:s');

// declarar array para respuestas 
$response = array();

// insertamos cabeceras para permisos 
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept,Authorization, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");
header("Content-Type: JSON");
header('Content-Type: application/json;charset=utf-8'); 



// validamos si hay conexion 
if($con){
        $methodApi = $_SERVER['REQUEST_METHOD'];

        if($methodApi == 'POST'){
            $_POST = json_decode(file_get_contents('php://input'),true);

            $fecha_procesado   = $_POST['fecha'].'%';
            $vendedor = $_POST['vendedor'];
            $sql = 'SELECT *FROM `view_pedidos_vendedor` WHERE vendedora=? AND fecha_procesado LIKE ?';
            $stmt = $con->prepare($sql);
            $stmt->bind_param('ss',$vendedor,$fecha_procesado);
            $stmt->execute();
            $result   = $stmt->get_result();
            $response = $result->fetch_all(MYSQLI_ASSOC);
            echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
        }

        if($methodApi == 'PUT'){
            $_PUT = json_decode(file_get_contents('php://input'),true);

            $vendedora = $_PUT['vendedora'];
            $id = $_GET['id'];
            $marca = '1';

            $sql = 'UPDATE folios SET marcado=?,vendedora=? WHERE id=?';
            $stmt = $con->prepare($sql);
            $stmt->bind_param('ssi',$marca,$vendedora,$id);
            $result = $stmt->execute();
            if($result || $result == 1){
                header("HTTP/1.1 200 OK");
                $response['mensaje'] = 'Cliente contactado';
                echo  json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
            }else{
                header("HTTP/1.1 400");
                $response['mensaje'] = 'Ocurrio un error,intente nuevamente';
                echo  json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
            }
        }
}else{
    echo "DB FOUND CONNECTED";
}