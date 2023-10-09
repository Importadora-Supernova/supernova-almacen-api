<?php
// conexion con base de datos 
include '../conexion/conn.php';
//import middleware
include '../middleware/validarToken.php';

// declarar array para respuestas 
$response = array();

// insertamos cabeceras para permisos 

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type,Authorization, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");
header("Content-Type: JSON");
header('Content-Type: application/json;charset=utf-8'); 

date_default_timezone_set('America/Mexico_City');
$fecha = date('Y-m-d H:i:s');


// validamos si hay conexion 
if($con){
    if($token_access['token']){
        $methodApi = $_SERVER['REQUEST_METHOD'];

        switch($methodApi){
            // metodo post 
            case 'POST':
            $_POST = json_decode(file_get_contents('php://input'),true);
            $nombre = $_POST['nombre_tipo_usuario'];
            $estatus =$_POST['estatus_tipo'];
            
            //validar campos vacios 
            if($nombre == '' || is_null($estatus)){
                header("HTTP/1.1 400");
                $response['status'] = 400;
                $response['mensaje'] = 'Debes agregar todos los campos, existen campos vacios';
                echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT); 
            }else{
                //preparar consulta 
                if(!($sentencia = $con->prepare("INSERT INTO app_tipo_usuario_bodega (nombre_tipo_usuario,estatus_tipo,fecha_created) VALUES (?,?,?)"))){
                    echo "Falló la preparación: (" . $con->errno . ") " . $con->error;
                }

                //pasar paramnetros a sentencia
                if(!$sentencia->bind_param("sss", $nombre,$estatus,$fecha)){
                    echo "Falló la vinculación de parámetros: (" . $sentencia->errno . ") " . $sentencia->error;
                }

                if (!$sentencia->execute()) {
                    echo "Falló la ejecución: (".$sentencia->errno.") " . $sentencia->error;
                    header("HTTP/1.1 400");
                    $response['status'] = 400;
                    $response['mensaje'] = 'No se pudo Guardar el registro';
                    echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                    $con->close();                
                }else{
                    header("HTTP/1.1 200 OK");
                    $response['status'] = 200;
                    $response['mensaje'] = 'Registro creado correctamente';
                    echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                    $con->close();
                } 
            }
            

             //
            break;
            // metodo get 
            case 'GET':
             // para obtener un registro especifico
             if(isset($_GET['id'])){ 
                 $sql = 'SELECT *FROM app_tipo_usuario_bodega  WHERE id_tipo_usuario='.$_GET['id'].'';
                 $result = mysqli_query($con,$sql);
                 $i=0;
                 while($row = mysqli_fetch_assoc($result)){
                    $response['id'] = $row['id_tipo_usuario'];
                    $response['nombre_tipo_usuario'] = $row['nombre_tipo_usuario'];
                    $response['estatus_tipo'] = $row['estatus_tipo'] == "1" ? true : false;
                    $response['fecha_created'] = $row['fecha_created'];
                     $i++;
                 }

                 echo  json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
              } else if(isset($_GET['activos'])){
                    $sqlPagados = 'SELECT * FROM app_tipo_usuario_bodega WHERE estatus_tipo="1"';
                    $resultPagados = mysqli_query($con,$sqlPagados);
                    $i=0;
                    while($row = mysqli_fetch_assoc($resultPagados)){
                        $response[$i]['id'] = $row['id_tipo_usuario'];
                        $response[$i]['nombre_tipo_usuario'] = $row['nombre_tipo_usuario'];
                        $i++;
                    }
                    echo  json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);

              } else {
                    $sqlPagados = 'SELECT * FROM app_tipo_usuario_bodega';
                    $resultPagados = mysqli_query($con,$sqlPagados);
                    $i=0;
                    while($row = mysqli_fetch_assoc($resultPagados)){
                        $response[$i]['id'] = $row['id_tipo_usuario'];
                        $response[$i]['nombre_tipo_usuario'] = $row['nombre_tipo_usuario'];
                        $response[$i]['estatus_tipo'] = $row['estatus_tipo'] == "1" ? true : false;
                        $response[$i]['fecha_created'] = $row['fecha_created'];
                        $i++;
                    }

                    echo  json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                 }
            break;
            case 'PUT':
                $_PUT = json_decode(file_get_contents('php://input'),true);
                $update = 'UPDATE app_tipo_usuario_bodega SET nombre_tipo_usuario="'.$_PUT['nombre_tipo_usuario'].'", estatus_tipo='.$_PUT['estatus'].' WHERE id_tipo_usuario='.$_GET['id'].'';
                $resUpdate = mysqli_query($con,$update);

                if($resUpdate){
                    header("HTTP/1.1 200 OK");
                    $response['status'] = 200;
                    $response['mensaje'] = 'Registro actulizado correctamente';
                    echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                }else{
                    header("HTTP/1.1 400");
                    $response['status'] = 400;
                    $response['mensaje'] = 'No se pudo actualizar el registro';
                    echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                }
            break;

        }
    }else{
        echo $token_access['validate'];
    }
    //echo "Informacion".file_get_contents('php://input');

}else{
    echo "DB FOUND CONNECTED";
}
?>