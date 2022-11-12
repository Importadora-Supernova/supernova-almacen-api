<?php
// conexion con base de datos 
include '../conexion/conn.php';
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

        switch($methodApi){
            // metodo post 
            case 'POST':
               $_POST = json_decode(file_get_contents('php://input'),true);
             // post
             $sqlUpdatePermisos = 'UPDATE permisos_user SET almacen_general='.$_POST['almacen_general'].',productos_almacenes='.$_POST['productos_almacenes'].', almacenes='.$_POST['almacenes'].',traslados='.$_POST['traslados'].', almacen_garantia='.$_POST['almacen_garantia'].' WHERE id_user='.$_POST['id_user'].'';
             $result = mysqli_query($con,$sqlUpdatePermisos);
             if($result){
               header("HTTP/1.1 200 OK");
               $response['status'] = 200;
               $response['mensaje'] = 'Registro actualizado correctamente';
               echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
             }else{
               header("HTTP/1.1 400");
               $response['status'] = 400;
               $response['mensaje'] = 'No se pudo actualizar el registro';
               echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
             }
            break;
            // metodo get 
            case 'GET':
             // para obtener un registro especifico
             if(isset($_GET['id'])){
                 $sql = 'SELECT  *FROM permisos_user WHERE id_user='.$_GET['id'].'';
                 $result = mysqli_query($con,$sql);
                 $i=0;
                 while($row = mysqli_fetch_assoc($result)){


                     if( $row['almacen_general'] == "1"){
                        $response['almacen_general'] = true;    
                     }else{
                        $response['almacen_general'] = false;
                     }

                     if( $row['productos_almacenes'] == "1"){
                        $response['productos_almacenes'] = true;
                     }else{
                        $response['productos_almacenes'] = false;
                     }

                     if( $row['almacenes'] == "1"){
                        $response['almacenes'] = true;
                     }else{
                        $response['almacenes'] = false;
                     }

                     if( $row['traslados'] == "1"){
                        $response['traslados'] = true;
                     }else{
                        $response['traslados'] = false;
                     }

                     if( $row['almacen_garantia'] == "1"){
                        $response['almacen_garantia'] = true;
                     }else{
                        $response['almacen_garantia'] = false;
                     }
                     

                     $i++;
                 }
                 echo json_encode($response,JSON_PRETTY_PRINT);
              } else{
                  // es para obtener todos los registros 
               
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
    //echo "Informacion".file_get_contents('php://input');

}else{
    echo "DB FOUND CONNECTED";
}
?>