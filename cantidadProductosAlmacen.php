<?php
// conexion con base de datos 
include 'conexion/conn.php';

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
        if(isset($_GET['id'])){
            $sql = 'SELECT a.id_almacen,a.nombre_almacen,r.cantidad,p.nombre,p.codigo FROM almacenes a INNER JOIN almacen_producto r ON a.id_almacen = r.id_almacen INNER JOIN productos p ON p.id = r.id_producto WHERE p.id='.$_GET['id'].'';
             $result = mysqli_query($con,$sql);
             $i=0;
             while($row = mysqli_fetch_assoc($result)){
                 $response[$i]['id_almacen'] = $row['id_almacen'];
                 $response[$i]['nombre_almacen'] = $row['nombre_almacen'];
                 $response[$i]['cantidad'] = $row['cantidad'];
                 $response[$i]['nombre'] = $row['nombre'];
                 $response[$i]['codigo'] = $row['codigo'];
                 $i++;
             }
             echo  json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
             
        }else{

            // es para obtener todos los registros 
            $sql = 'SELECT *from cantidades_productos_almacen';
            $result = mysqli_query($con,$sql);
            $i=0;
            while($row = mysqli_fetch_assoc($result)){
                $response[$i]['cantidades'] = $row['cantidades'];
                $response[$i]['nombre_almacen'] = $row['nombre_almacen'];
                $i++;
            }
            echo  json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
            }
    }
    
}else{
    echo "DB FOUND CONNECTED";
}
?>