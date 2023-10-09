<?php
// conexion con base de datos 
include '../conexion/conn.php';
include '../class/querys.php';
//import middleware
include '../middleware/validarToken.php';

date_default_timezone_set('America/Mexico_City');
// declarar array para respuestas 
$response = array();
$fecha = date('Y-m-d H:i:s');

// insertamos cabeceras para permisos 

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type,Authorization, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");
header("Content-Type: JSON");
header('Content-Type: application/json;charset=utf-8'); 


$query = new Querys();

// validamos si hay conexion 
if($con){
    if($token_access['token']){
        $methodApi = $_SERVER['REQUEST_METHOD'];

        switch($methodApi){
            // metodo post 
            case 'POST':
                $_POST = json_decode(file_get_contents('php://input'),true);

                $tasa = $_POST['tasa'];

                if($tasa == '' || $tasa == null || $tasa == 0){
                    header("HTTP/1.1 400");
                    $response['status'] = 400;
                    $response['mensaje'] = 'Debes agregar todos los campos, existen campos vacios';
                    echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT); 
                }else{
                    //preparamos sentencia
                    $sql = "INSERT INTO admin_tasa_cambio (tasa_cambio,fecha_actualizacion) VALUES (?,?)";
                    $result = $query->insertarTasaCambio($con,$sql,$tasa,$fecha);
                    if($result){
                        header("HTTP/1.1 200");
                        $response['status'] = 200;
                        $response['mensaje'] = 'La tasa de cambio fue actualizada correctamente';
                        echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT); 
                    }
                }

            break;
            // metodo get 
            case 'GET':
             // para obtener un registro especifico
                $sql = 'SELECT *FROM admin_tasa_cambio ORDER BY id_tasa_cambio DESC LIMIT 1';
                $result = $query->getTasaCambio($con,$sql);
                echo json_encode($result,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                $con->close(); 
                    
            break;

        }
    //echo "Informacion".file_get_contents('php://input');
    }else{
        echo $token_access['validate'];
    }
}else{
    echo "DB FOUND CONNECTED";
}
?>