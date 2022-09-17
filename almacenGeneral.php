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
    
    //echo "Informacion".file_get_contents('php://input');

   $methodApi = $_SERVER['REQUEST_METHOD'];

   switch($methodApi){
       // metodo post 
       case 'POST':
        $_POST = json_decode(file_get_contents('php://input'),true);
        //echo "guardar informacion data: =>".json_encode($_POST);
        // $sql = 'INSERT INTO almacenes (nombre_almacen,tipo,status,fecha_create,user_create) VALUES ("'.$_POST['nombre_almacen'].'","'.$_POST['tipo'].'","'.$_POST['status'].'","'.$_POST['fecha_create'].'",1)';
        // $result = mysqli_query($con,$sql);
        //     if($result)
        //         echo 'informacion registrada exitosamente';
        //     else
        //         echo 'no se pudo registrar';
       break;
       // metodo get 
       case 'GET':
        // para obtener un registro especifico
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
                $response[$i]['descripcion'] = $row['descripcion'];
                $response[$i]['cantidad'] = 0;
                $i++;
            }
           echo  json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
        }else{
            if(isset($_GET['id'])){
                $sql = 'SELECT * FROM productos  where a.id="'.$_GET['id'].'"';
                $result = mysqli_query($con,$sql);
                $i=0;
                while($row = mysqli_fetch_assoc($result)){
                    $response['id'] = $row['id'];
                    $response['nombre'] = $row['nombre'];
                    $response['codigo'] = $row['codigo'];
                    $response['almacen'] = $row['almacen'];
                    $response['descripcion'] = $row['descripcion'];
                    $i++;
                }
                echo json_encode($response,JSON_PRETTY_PRINT);
             } else{
                 // es para obtener todos los registros 
                $sql = 'select *from productos';
                $result = mysqli_query($con,$sql);
                $i=0;
                while($row = mysqli_fetch_assoc($result)){
                    $response[$i]['id'] = $row['id'];
                    $response[$i]['nombre'] = $row['nombre'];
                    $response[$i]['codigo'] = $row['codigo'];
                    $response[$i]['almacen'] = $row['almacen'];
                    $response[$i]['descripcion'] = $row['descripcion'];
                    $i++;
                }
               echo  json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
             }
        }
       break;
   }
}else{
    echo "DB FOUND CONNECTED";
}
?>