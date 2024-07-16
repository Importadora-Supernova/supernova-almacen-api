<?php
// conexion con base de datos 
include 'conexion/conn.php';
include 'headers.php';
//import middleware

// declarar array para respuestas 
$response = array();


date_default_timezone_set('America/Mexico_City');
$fecha = date('Y-m-d H:i:s');

// insertamos cabeceras para permisos 

// validamos si hay conexion 
if($con){
    //if($token_access['token']){
        $methodApi = $_SERVER['REQUEST_METHOD'];
        if($methodApi == 'GET'){
            $sql = 'SELECT  codigo FROM productos GROUP BY codigo ORDER BY id';
            $stmt = $con->prepare($sql);
            $stmt->execute();
            $result = $stmt->get_result();
            $response = $result->fetch_all(MYSQLI_ASSOC);
            echo  json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT); 
        }

        

}else{
    echo "DB FOUND CONNECTED";
}