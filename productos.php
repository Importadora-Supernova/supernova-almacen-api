<?php
// conexion con base de datos 
include 'conexion/conn.php';
//incluir middleware
include 'middleware/midleware.php';

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

   if($validate == 'validado'){
    
    $methodApi = $_SERVER['REQUEST_METHOD'];
   
    if($methodApi == 'GET'){
        if(isset($_GET['codigo'])){
             // es para obtener todos los registros  por codigo
             $sql = 'SELECT *FROM productos where codigo="'.$_GET['codigo'].'"';
             $result = mysqli_query($con,$sql);
             $i=0;
             while($row = mysqli_fetch_assoc($result)){
                 $response[$i]['id'] = $row['id'];
                 $response[$i]['nombre'] = $row['nombre'];
                 $response[$i]['codigo'] = $row['codigo'];
                 $response[$i]['almacen'] = $row['almacen'];
                 $response[$i]['cantidad'] = null;
                 $i++;
             }
            echo  json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
        }else{
            if(isset($_GET['id'])){
                $sql = 'SELECT *FROM productos  where id="'.$_GET['id'].'"';
                $result = mysqli_query($con,$sql);
                $i=0;
                while($row = mysqli_fetch_assoc($result)){
                    $response['id'] = $row['id'];
                    $response['nombre'] = $row['nombre'];
                    $response['codigo'] = $row['codigo'];
                    $response['almacen'] = $row['almacen'];
                    $i++;
                }
                echo json_encode($response,JSON_PRETTY_PRINT);
             } else{
                if(isset($_GET['total'])){
                     // es para obtener todos los registros 
                     $sql = 'SELECT id,codigo,nombre,almacen FROM productos';
                     $result = mysqli_query($con,$sql);
                     $i=0;
                     while($row = mysqli_fetch_assoc($result)){
                         $response[$i]['id'] = $row['id'];
                         $response[$i]['nombre'] = $row['nombre'];
                         $response[$i]['codigo'] = $row['codigo'];
                         $response[$i]['almacen'] = $row['almacen'];
                         $response[$i]['fullname'] = $row['codigo']." ".$row['nombre'];
                         $i++;
                     }
                     echo  json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT); 
                }else{
                    // es para obtener todos los registros 
                    $sql = 'SELECT id,codigo,nombre,almacen FROM productos GROUP BY codigo';
                    $result = mysqli_query($con,$sql);
                    $i=0;
                    while($row = mysqli_fetch_assoc($result)){
                        $response[$i]['id'] = $row['id'];
                        $response[$i]['nombre'] = $row['nombre'];
                        $response[$i]['codigo'] = $row['codigo'];
                        $response[$i]['almacen'] = $row['almacen'];
                        $response[$i]['fullname'] = $row['codigo']." ".$row['nombre'];
                        $i++;
                    }
                    echo  json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT); 
                }
                
             }
        }
    }
   }else{
        header("HTTP/1.1 401");
        $response['mensaje'] = 'Tu TOKEN '.$validate.' ';
        echo  json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
   } 
  
}else{
    echo "DB FOUND CONNECTED";
}
?>