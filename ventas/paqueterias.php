<?php
// conexion con base de datos 
include '../conexion/conn.php';
include '../middleware/midleware.php';
date_default_timezone_set('America/Mexico_City');
//incluir middleware


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
    if($validate === 'validado'){
        $methodApi = $_SERVER['REQUEST_METHOD'];

        if($methodApi == 'GET'){
             // metodo get 
             // para obtener un registro especifico
            $sql = 'SELECT  DISTINCT(paqueteria),envio FROM folios';
            $result = mysqli_query($con,$sql);
            $i=0;
            while($row = mysqli_fetch_assoc($result)){
                $response[$i]['paqueteria'] =  $row['paqueteria'];
                $i++;
            }
            echo json_encode($response,JSON_PRETTY_PRINT);
        }
    }else{
        header("HTTP/1.1 200");
        $response['status'] = 401;
        $response['mensaje'] = 'Token '.$validate;
        echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
    }
    //echo "Informacion".file_get_contents('php://input');

}else{
    echo "DB FOUND CONNECTED";
}
?>