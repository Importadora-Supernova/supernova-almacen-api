<?php
// conexion con base de datos 
include '../conexion/conn.php';
//incluir middleware

// declarar array para respuestas 
$response = array();

// insertamos cabeceras para permisos 

header('Access-Control-Allow-Origin:*');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");
header("Content-Type: JSON");
header('Content-Type: application/json;charset=utf-8'); 



// validamos si hay conexion 
if($con){

    $methodApi = $_SERVER['REQUEST_METHOD'];
        if($methodApi == 'GET'){

            // es para obtener todos los registros 
            $sql = 'SELECT * FROM `productos` WHERE `codigo` LIKE "%se%"';
            $result = mysqli_query($con,$sql);  
            $i=0;
            while($row = mysqli_fetch_assoc($result)){
                $response[$i]['id'] = $row['id'];
                $response[$i]['nombre'] = $row['nombre'];
                $response[$i]['codigo'] = $row['codigo'];
                $response[$i]['preciou'] = $row['preciou'];
                $response[$i]['precioc'] = $row['precioc'];
                $response[$i]['preciom'] = $row['preciom'];
                $response[$i]['preciov'] = $row['preciov'];
                $response[$i]['topev'] = $row['topev'];
                $response[$i]['topem'] = $row['topem'];
                $response[$i]['topec'] = $row['topec'];
                $response[$i]['almacen'] = $row['almacen'];
                $i++;
            }
            echo  json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT); 
        }
                    
}else{
    echo "DB FOUND CONNECTED";
}
?>