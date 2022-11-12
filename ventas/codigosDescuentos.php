<?php
// conexion con base de datos 
include '../conexion/conn.php';
date_default_timezone_set('America/Mexico_City');
//incluir middleware


// declarar array para respuestas 
$response = array();

// insertamos cabeceras para permisos 

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");
header("Content-Type: JSON");
header('Content-Type: application/json;charset=utf-8'); 


// validamos si hay conexion 
if($con){
    
        $methodApi = $_SERVER['REQUEST_METHOD'];

        if($methodApi == 'GET'){
             // metodo get 
             // para obtener un registro especifico
            $sql = 'SELECT  *FROM codigos_productos_descuentos';
            $result = mysqli_query($con,$sql);
            $i=0;
            while($row = mysqli_fetch_assoc($result)){
                $response[$i]['id_producto'] =  $row['id'];
                $response[$i]['nombre'] =  $row['nombre'];
                $response[$i]['codigo'] =  $row['codigo'];
                $response[$i]['preciou'] =  $row['preciou'];
                $response[$i]['preciom'] =  $row['preciom'];
                $response[$i]['precioc'] =  $row['precioc'];
                $response[$i]['preciov'] =  $row['preciov'];
                $response[$i]['topem'] =  $row['topem'];
                $response[$i]['topec'] =  $row['topec'];
                $response[$i]['topev'] =  $row['topev'];
                $response[$i]['descuento'] =  $row['descuento'];
                $response[$i]['descuento_precio_docena'] =  $row['descuento_precio_docena'];
                $i++;
            }
            header("HTTP/1.1 200 OK");
            echo json_encode($response,JSON_PRETTY_PRINT);
        }
    //echo "Informacion".file_get_contents('php://input');

}else{
    echo "DB FOUND CONNECTED";
}
?>