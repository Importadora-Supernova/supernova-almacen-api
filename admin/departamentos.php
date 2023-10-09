<?php
// conexion con base de datos 
include '../conexion/conn.php';
//incluir middleware
include '../middleware/validarToken.php';


// declarar array para respuestas 
$response = array();

date_default_timezone_set('America/Mexico_City');
$fecha = date('Y-m-d H:i:s');

// insertamos cabeceras para permisos 

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type,Authorization, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");
header("Content-Type: JSON");
header('Content-Type: application/json;charset=utf-8'); 

// validamos si hay conexion 
if($con){
    
    if($token_access['token']){
        $methodApi = $_SERVER['REQUEST_METHOD'];

        switch($methodApi){
            // metodo post 
            case 'POST':
            $_POST = json_decode(file_get_contents('php://input'),true);
            $nombre  = $_POST['nombre_departamento'];
            $estatus = $_POST['estatus_departamento'];

            //validacion de campos 
            if($nombre == '' || $estatus == ''){
                header("HTTP/1.1 400");
                $response['status'] = 400;
                $response['mensaje'] = 'Debes agregar todos los campos, existen campos vacios';
                echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT); 
            }else{
                //preparar sentencia
                if(!($sentencia = $con->prepare("INSERT INTO admin_departamentos (nombre_departamento,estatus_departamento,fecha_created) VALUES (?,?,?)"))){
                    echo "Falló la preparación: (" . $con->errno . ") " . $con->error;
                }

                //vincular datos, parametros y tipo de datos
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

            break;
            // metodo get 
            case 'GET':
             // para obtener un registro especifico
            if(isset($_GET['id'])){
                $sql = 'SELECT  *FROM admin_departamentos WHERE id_departamento='.$_GET['id'].'';
                $result = mysqli_query($con,$sql);
                $i=0;
                while($row = mysqli_fetch_assoc($result)){
                    $response['id_departamento'] = $row['id_departamento'];
                    $response['nombre_departamento'] = $row['nombre_departamento'];

                    if( $row['estatus_departamento'] == "1"){
                        $response['estatus_departamento'] = true;
                    }else{
                        $response['estatus_departamento'] = false;
                    }
                    $response['fecha_created'] = $row['fecha_created'];
                    $i++;
                }
                echo json_encode($response,JSON_PRETTY_PRINT);
            }else if(isset($_GET['activos'])){
                $sql = 'SELECT *FROM admin_departamentos WHERE estatus_departamento="1"';
                $result = mysqli_query($con,$sql);
                $i=0;
                while($row = mysqli_fetch_assoc($result)){
                    $response[$i]['id_departamento'] = $row['id_departamento'];
                    $response[$i]['nombre_departamento'] = $row['nombre_departamento'];
                    if( $row['estatus_departamento'] == "1"){
                        $response[$i]['estatus_departamento'] = true;
                    }else{
                        $response[$i]['estatus_departamento'] = false;
                    }
                    $response[$i]['fecha_created'] = $row['fecha_created'];
                    $i++;
                }
                echo  json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
            }else{
                  // es para obtener todos los registros 
                $sql = 'SELECT *FROM admin_departamentos';
                $result = mysqli_query($con,$sql);
                $i=0;
                while($row = mysqli_fetch_assoc($result)){
                    $response[$i]['id_departamento'] = $row['id_departamento'];
                    $response[$i]['nombre_departamento'] = $row['nombre_departamento'];
                    if( $row['estatus_departamento'] == "1"){
                        $response[$i]['estatus_departamento'] = true;
                    }else{
                        $response[$i]['estatus_departamento'] = false;
                    }
                    $response[$i]['fecha_created'] = $row['fecha_created'];
                    $i++;
                }
                echo  json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);  
            }
            break;
            case 'PUT':
            $_PUT = json_decode(file_get_contents('php://input'),true);
            $sql = 'UPDATE admin_departamentos SET nombre_departamento="'.$_PUT['nombre_departamento'].'", estatus_departamento="'.$_PUT['estatus_departamento'].'"  WHERE id_departamento='.$_GET['id'].'';
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
    }else{
        echo $token_access['validate'];
    }
    //echo "Informacion".file_get_contents('php://input');

}else{
    echo "DB FOUND CONNECTED";
}
