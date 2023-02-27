<?php
// conexion con base de datos 
include '../conexion/conn.php';
date_default_timezone_set('America/Mexico_City');

// declarar array para respuestas 
$response = array();

// insertamos cabeceras para permisos 

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");
header("Content-Type: JSON");
header('Content-Type: application/json;charset=utf-8'); 

$fecha = date('Y-m-d H:i:s');

// validamos si hay conexion 
if($con){
    
        $methodApi = $_SERVER['REQUEST_METHOD'];

        switch($methodApi){
            // metodo post 
            case 'POST':
             $_POST = json_decode(file_get_contents('php://input'),true);
             $con->autocommit(false);
             // actualizamos orden a pausado
             $sqlUpdate = 'UPDATE folios SET estatus="Resuelto" WHERE orden="'.$_POST['orden'].'"';
             $result = mysqli_query($con,$sqlUpdate);

             $sqlUpdateNotificaciones = 'UPDATE notificaciones SET accion="'.$_POST['accion'].'",observacion="'.$_POST['observacion'].'",fecha_resuelto="'.$fecha.'" WHERE orden="'.$_POST['orden'].'"';
             $resultUpdate = mysqli_query($con,$sqlUpdateNotificaciones);

             if($result && $resultUpdate){
                $con->commit();
                header("HTTP/1.1 200");
                $response['mensaje'] = 'La orden fue resuelta correctamente';
                echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
             }else{
                $con->rollback();
                header("HTTP/1.1 400");
                $response['mensaje'] = 'Ocurrio un error,No se podo completar la accion';
                echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
             }
            break;
            // metodo get 
            case 'GET':
             // para obtener un registro especifico
            break;

            default:
            break;

        }
    //echo "Informacion".file_get_contents('php://input');

}else{
    echo "DB FOUND CONNECTED";
}
?>