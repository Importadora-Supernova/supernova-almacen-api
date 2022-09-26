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

        switch($methodApi){
            // metodo post 
            case 'POST':
             $_POST = json_decode(file_get_contents('php://input'),true);
             //echo "guardar informacion data: =>".json_encode($_POST);
             $sql = 'INSERT INTO almacenes (nombre_almacen,tipo,status,fecha_create,user_create) VALUES ("'.$_POST['nombre_almacen'].'","'.$_POST['tipo'].'","'.$_POST['status'].'","'.$_POST['fecha_create'].'",1)';
             $result = mysqli_query($con,$sql);
                 if($result)
                     echo 'informacion registrada exitosamente';
                 else
                     echo 'no se pudo registrar';
            break;
            // metodo get 
            case 'GET':
             // para obtener un registro especifico
             if(isset($_GET['id'])){
                 $sql = 'SELECT a.id_almacen,a.nombre_almacen,a.tipo,a.fecha_create,s.nombre_status FROM almacenes a INNER JOIN estados s ON a.status = s.id_status  where a.id_almacen="'.$_GET['id'].'"';
                 $result = mysqli_query($con,$sql);
                 $i=0;
                 while($row = mysqli_fetch_assoc($result)){
                     $response['id_almacen'] = $row['id_almacen'];
                     $response['nombre_almacen'] = $row['nombre_almacen'];
                     $response['tipo'] = $row['tipo'];
                     $response['fecha_create'] = $row['fecha_create'];
                     $response['status'] = $row['nombre_status'];
                     $i++;
                 }
                 echo json_encode($response,JSON_PRETTY_PRINT);
              } else{
                  // es para obtener todos los registros 
                 $sql = 'select *from vista_almacenes';
                 $result = mysqli_query($con,$sql);
                 $i=0;
                 while($row = mysqli_fetch_assoc($result)){
                     $response[$i]['id'] = $row['id_almacen'];
                     $response[$i]['nombre_almacen'] = $row['nombre_almacen'];
                     $response[$i]['tipo'] = $row['tipo'];
                     $response[$i]['fecha_create'] = $row['fecha_create'];
                     $response[$i]['status'] = $row['nombre_status'];
                     $i++;
                 }
                echo  json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
              }
            break;
            case 'PUT':
             $_PUT = json_decode(file_get_contents('php://input'),true);
             $sql = 'UPDATE almacenes SET nombre_almacen="'.$_PUT['nombre_almacen'].'", tipo="'.$PUT['tipo'].'", status="'.$PUT['status'].'"  WHERE id='.$_GET['id'].'';
             $result = mysqli_query($con,$sql);
             if($result)
                     echo 'registro actualizado correctamente';
                 else
                     echo 'no se pudo actualizar';
            break;
            case 'DELETE':
                 $sql = 'DELETE  from almacenes where id='.$_GET['id'].'';
                 $result = mysqli_query($con,$sql);
                 if($result)
                     echo "registro eliminado satisfactoriamente";
                 else
                     echo "no se pudo eliminar el registro";
            break;
        }
    }else{
        header("HTTP/1.1 401");
        $response['mensaje'] = 'Tu TOKEN '.$validate.' ';
        echo  json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
    }
    //echo "Informacion".file_get_contents('php://input');

}else{
    echo "DB FOUND CONNECTED";
}
?>