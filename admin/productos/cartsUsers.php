<?php
// conexion con base de datos 
include '../../conexion/conn.php';
include '../../headers.php';
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

        //Peticion GET
        if($methodApi == 'GET'){
            $sql  = 'SELECT u.id, u.nombre, u.apellido, cp.fecha_register, SUM(cantidad) AS total_productos FROM app_cart_products cp JOIN usuario u ON cp.user_id = u.id GROUP BY u.id, u.nombre, u.apellido ORDER BY cp.fecha_register';
            $stmt = $con->prepare($sql);
            $stmt->execute();
            $result = $stmt->get_result();
            $data = $result->fetch_all(MYSQLI_ASSOC);
            echo json_encode($data,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
           
        }

        //PETICION POST
        if($methodApi == 'POST'){
            
            $_POST = json_decode(file_get_contents('php://input'),true);

        }

        //PETICION PUT
        if($methodApi == 'PUT'){

            $_PUT = json_decode(file_get_contents('php://input'),true);


        }
}else{
    echo "DB FOUND CONNECTED";
}