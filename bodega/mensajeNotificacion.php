<?php
// conexion con base de datos 
include '../conexion/conn.php';
//incluir middleware
date_default_timezone_set('America/Mexico_City');
$fecha = date('Y-m-d H:i:s');

// declarar array para respuestas 
$response = array();

// insertamos cabeceras para permisos 

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Authorization, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");
header("Content-Type: JSON");
header('Content-Type: application/json;charset=utf-8'); 


// validamos si hay conexion 
if($con){
    
        $methodApi = $_SERVER['REQUEST_METHOD'];

        switch($methodApi){
            // metodo post 
            case 'POST':
                $_POST = json_decode(file_get_contents('php://input'),true);

                $id_notificacion = $_POST['id_notificacion'];
                $mensaje = $_POST['mensaje'];
                $user    = $_POST['user_created'];

                $sql = 'INSERT INTO notificaciones_mensajes(id_notificacion,mensaje,user_created,fecha_created) VALUES(?,?,?,?)';
                $stmt = $con->prepare($sql);
                $stmt->bind_param('isis',$id_notificacion,$mensaje,$user,$fecha);
                $reta = $stmt->execute();
                if($reta == 1){
                    header("HTTP/1.1 200 OK");
                    $response['status'] = 200;
                    $response['mensaje'] = 'Se creo el mensaje exitosamente';
                    echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT); 
                }else{
                    header("HTTP/1.1 400");
                    $response['status'] = 400;
                    $response['mensaje'] = 'No se pudo completar el proceso';
                    echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                }

            break;
            // metodo get 
            case 'GET':
             // para obtener un registro especifico

            break;
            case 'PUT':
             $_PUT = json_decode(file_get_contents('php://input'),true);

            break;
            case 'DELETE':

            break;
        }
    //echo "Informacion".file_get_contents('php://input');

}else{
    echo "DB FOUND CONNECTED";
}
?>