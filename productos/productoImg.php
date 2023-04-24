<?php
// conexion con base de datos 
include '../conexion/conn.php';
//incluir middleware
include '../middleware/midleware.php';

// declarar array para respuestas 
$response = array();

// insertamos cabeceras para permisos 

header('Access-Control-Allow-Origin:*');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept,Authorization, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");
header("Content-Type: JSON");
header('Content-Type: application/json;charset=utf-8'); 



// validamos si hay conexion 
if($con){

    $methodApi = $_SERVER['REQUEST_METHOD'];
    if($validate === 'validado'){
        if($methodApi == 'GET'){
            // es para obtener todos los registros 
            $sql = 'SELECT a FROM img WHERE id_producto = "'.$_GET['id'].'" limit 1';
            $result = mysqli_query($con,$sql);
            while($row = mysqli_fetch_assoc($result)){
                $response['img'] = $row['a'];
            }
            header("HTTP/1.1 200");
            echo  json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT); 
        }
    }else{
        header("HTTP/1.1 200");
        $response['status'] = 401;
        $response['mensaje'] = 'Token '.$validate;
        echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
    }
  
}else{
    echo "DB FOUND CONNECTED";
}
?>