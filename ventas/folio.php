<?php
// conexion con base de datos 
include '../conexion/conn.php';
date_default_timezone_set('America/Mexico_City');
//incluir middleware
include '../middleware/midleware.php';


// declarar array para respuestas 
$response = array();

// insertamos cabeceras para permisos 

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept,Authorization, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");
header("Content-Type: JSON");
header('Content-Type: application/json;charset=utf-8'); 

$fecha = date('Y-m-d');

// validamos si hay conexion 
if($con){
    if($validate === 'validado'){
        $methodApi = $_SERVER['REQUEST_METHOD'];

        switch($methodApi){
            // metodo post 
            case 'POST':
             // post
            $_POST = json_decode(file_get_contents('php://input'),true);

            
            break;
            // metodo get 
            case 'GET':
             // para obtener un registro especifico
                $sql = 'SELECT  *FROM folios WHERE orden="'.$_GET['orden'].'"';
                $result = mysqli_query($con,$sql);
                while($row = mysqli_fetch_assoc($result)){      
                    $response['id_usuario'] =  $row['id_usuario'];
                    $response['nombres'] =  $row['nombres'];
                    $response['orden'] =  $row['orden'];
                    $response['paqueteria'] =  $row['paqueteria'];
                    $response['total'] =  $row['total'];
                    $response['fecha_pedido'] =  $row['fecha'];
                    $response['fecha_procesado'] =  $row['fecha_procesado'];
                    $response['fecha_almacen'] =  $row['fecha_almacen'];
                    $response['fecha_salida'] =  $row['fecha_salida'];
                    $response['estatus'] =  $row['estatus'];       
                }
                header("HTTP/1.1 200 OK");
                echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
            break;
        }
    }else{
        header("HTTP/1.1 201");
        $response['status'] = 401;
        $response['mensaje'] = 'Token '.$validate;
        echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
    }
    //echo "Informacion".file_get_contents('php://input');

}else{
    echo "DB FOUND CONNECTED";
}
?>