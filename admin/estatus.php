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
             //echo "guardar informacion data: =>".json_encode($_POST);
             $sql = 'INSERT INTO admin_estatus (nombre_estatus,active,descripcion,color_estatus) VALUES ("'.$_POST['nombre_estatus'].'","'.$_POST['active'].'","'.$_POST['descripcion'].'","'.$_POST['color'].'")';
             $result = mysqli_query($con,$sql);
             if($result){
                header("HTTP/1.1 200 OK");
                $response['status'] = 200;
                $response['mensaje'] = 'Registro creado correctamente';
                echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
            }else{
                header("HTTP/1.1 400");
                $response['status'] = 400;
                $response['mensaje'] = 'No se pudo Guardar el registro';
                echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
            }
            break;
            // metodo get 
            case 'GET':
             // para obtener un registro especifico
             if(isset($_GET['id'])){
                 $sql = 'SELECT  *FROM admin_estatus WHERE id_estatus='.$_GET['id'].'';
                 $result = mysqli_query($con,$sql);
                 $i=0;
                 while($row = mysqli_fetch_assoc($result)){
                     $response['id_estatus'] = $row['id_estatus'];
                     $response['nombre_estatus'] = $row['nombre_estatus'];

                     if( $row['active'] == "1"){
                        $response['active'] = true;
                     }else{
                        $response['active'] = false;
                     }
                     
                     $response['descripcion'] = $row['descripcion'];
                     $response['color'] = $row['color_estatus'];
                     $i++;
                 }
                 echo json_encode($response,JSON_PRETTY_PRINT);
              } else{
                  // es para obtener todos los registros 
                 $sql = 'SELECT *FROM admin_estatus';
                 $result = mysqli_query($con,$sql);
                 $i=0;
                 while($row = mysqli_fetch_assoc($result)){
                    $response[$i]['id_estatus'] = $row['id_estatus'];
                    $response[$i]['nombre_estatus'] = $row['nombre_estatus'];
                    if( $row['active'] == "1"){
                        $response[$i]['active'] = true;
                     }else{
                        $response[$i]['active'] = false;
                     }
                    $response[$i]['descripcion'] = $row['descripcion'];
                    $response[$i]['color'] = $row['color_estatus'];
                    $i++;
                }
                // $variable = md5('    ');
                // echo $variable;
                echo  json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);  
              }
            break;
            case 'PUT':
             $_PUT = json_decode(file_get_contents('php://input'),true);
             $sql = 'UPDATE admin_estatus SET nombre_estatus="'.$_PUT['nombre_estatus'].'", active="'.$_PUT['active'].'", descripcion="'.$_PUT['descripcion'].'", color_estatus="'.$_PUT['color'].'"  WHERE id_estatus='.$_GET['id'].'';
             $result = mysqli_query($con,$sql);
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

        }
    //echo "Informacion".file_get_contents('php://input');

}else{
    echo "DB FOUND CONNECTED";
}
?>