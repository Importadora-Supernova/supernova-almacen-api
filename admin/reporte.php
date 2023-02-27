<?php
// conexion con base de datos 
include '../conexion/conn.php';

// declarar array para respuestas 
$response = array();

// insertamos cabeceras para permisos 

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type,Authorization, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");
header("Content-Type: JSON");
header('Content-Type: application/json;charset=utf-8'); 

date_default_timezone_set('America/Mexico_City');
$fecha = date('Y-m-d H:i:s');


// validamos si hay conexion 
if($con){
    
        $methodApi = $_SERVER['REQUEST_METHOD'];

        switch($methodApi){
            // metodo get 
            case 'GET':
                
            break;
            case 'POST':
                $_POST = json_decode(file_get_contents('php://input'),true);
                $sqlConsulta = 'SELECT SUM(cantidad) as cantidad_pedida FROM `registro_usuario` WHERE (fecha BETWEEN "'.$_POST['fecha_inicio'].'" AND "'.$_POST['fecha_fin'].'") AND codigo="'.$_POST['codigo'].'"';
                $resultConsulta = mysqli_query($con,$sqlConsulta);
                while($row = mysqli_fetch_assoc($resultConsulta)){
                    $response['cantidad_pedida']  = $row['cantidad_pedida'];
                }
                header("HTTP/1.1 200 OK");
                echo  json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT); 
            break;
            default:
            break;
    }
    //echo "Informacion".file_get_contents('php://input');
}else{
    echo "DB FOUND CONNECTED";
}
?>  