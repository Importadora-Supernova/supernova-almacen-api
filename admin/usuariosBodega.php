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

$fecha_actual = date('Y-m-d H:i:s');

// validamos si hay conexion 
if($con){
    
        $methodApi = $_SERVER['REQUEST_METHOD'];

        switch($methodApi){
            // metodo post 
            case 'POST':
             // post
            $_POST = json_decode(file_get_contents('php://input'),true);
            
            $usuario = $_POST['usuario'];
            $pass    = md5($_POST['password']);
            $tipo    = $_POST['tipo'];
            $estatus = '1';

            if($usuario == '' || $pass == '' || is_null($tipo)){
                header("HTTP/1.1 400");
                $response['status'] = 400;
                $response['mensaje'] = 'Debes agregar todos los campos, existen campos vacios';
                echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT); 
            }else{
                //preparar sentencia
                if(!($sentencia = $con->prepare("INSERT INTO app_usuarios_bodega (usuario_bodega,pass_usuario,tipo_usuario,estatus_user,fecha_created) VALUES (?,?,?,?,?)"))){
                    echo "Falló la preparación: (" . $con->errno . ") " . $con->error;
                }

                //Alistar parametros y tipo de datos
                if(!$sentencia->bind_param("ssiss", $usuario,$pass,$tipo,$estatus,$fecha_actual)){
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

            break;
            // metodo get 
            case 'GET':
             // para obtener un registro especifico
                if(isset($_GET['id'])){
                echo 'en construccion';
                //  $sql = 'SELECT  *FROM app_usuarios_almacen WHERE id_usuario_bodega='.$_GET['id'].'';
                //  $result = mysqli_query($con,$sql);
                //  $i=0;
                //  while($row = mysqli_fetch_assoc($result)){                
                //  }
                //  echo json_encode($response,JSON_PRETTY_PRINT);
                } else{
                    $sql = 'SELECT  u.id_user_bodega,u.usuario_bodega,u.estatus_user,t.nombre_tipo_usuario FROM app_usuarios_bodega u LEFT JOIN app_tipo_usuario_bodega t ON u.tipo_usuario = t.id_tipo_usuario';
                    $result = mysqli_query($con,$sql);
                    $i=0;
                    while($row = mysqli_fetch_assoc($result)){
                        $response[$i]['id'] =  $row['id_user_bodega'];
                        $response[$i]['usuario_bodega'] =  $row['usuario_bodega'];
                        $response[$i]['estatus_user'] =  $row['estatus_user'];
                        $response[$i]['nombre_tipo_usuario'] =  $row['nombre_tipo_usuario'];
                        $i++;
                    }
                    echo json_encode($response,JSON_PRETTY_PRINT);
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
        }
    //echo "Informacion".file_get_contents('php://input');

}else{
    echo "DB FOUND CONNECTED";
}
?>