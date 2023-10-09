<?php
// conexion con base de datos 
include '../conexion/conn.php';
require_once '../class/querys.php';

date_default_timezone_set('America/Mexico_City');
//incluir middleware
$fecha_actual = date('Y-m-d H:i:s');


// declarar array para respuestas 
$response = array();

// insertamos cabeceras para permisos 

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type,Authorization, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");
header("Content-Type: JSON");
header('Content-Type: application/json;charset=utf-8'); 

$query = new Querys();

// validamos si hay conexion 
if($con){
        $methodApi = $_SERVER['REQUEST_METHOD'];

        if($methodApi == 'GET'){

            if(isset($_GET['id'])){

                $id = $_GET['id'];
                $sql = 'SELECT * FROM view_atributos_caracteristicas WHERE id=?';
                $result = $query->getQueryId($con,$sql,$id);
                echo json_encode($result,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                $con->close();

            }else if(isset($_GET['caracteristica'])){

                $id = $_GET['caracteristica'];
                $sql = 'SELECT *FROM view_atributos_caracteristicas WHERE id_caracteristica=?';
                $result = $query->getQueryIdArray($con,$sql,$id);
                echo json_encode($result,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                $con->close();

            }else{
                
                $result = $query->getQuery($con,'SELECT *FROM view_atributos_caracteristicas ORDER BY id_caracteristica');
                echo json_encode($result,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
            }
        }

        if($methodApi == 'POST'){
             // metodo POST
            $_POST = json_decode(file_get_contents('php://input'),true);
            $nombre  = $_POST['nombre_atributo'];
            $id      = $_POST['id_caracteristica'];
            $type    = $_POST['type'];
            $preffix = $_POST['preffix'];

            //validacion de campos 
            if($nombre == '' || $id == null || $type == ''){
                header("HTTP/1.1 400");
                $response['status'] = 400;
                $response['mensaje'] = 'Debes agregar todos los campos, existen campos vacios';
                echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT); 
            }else{
                //preparar sentencia
                if(!($sentencia = $con->prepare("INSERT INTO admin_atributos_caracteristica (nombre_atributo,id_caracteristica,type_atributo,preffix,fecha_created) VALUES (?,?,?,?,?)"))){
                    echo "Falló la preparación: (" . $con->errno . ") " . $con->error;
                }

                //vincular datos, parametros y tipo de datos
                if(!$sentencia->bind_param("sisss", $nombre,$id,$type,$preffix,$fecha_actual)){    
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
        
        }

        if($methodApi == 'PUT'){
            $_PUT = json_decode(file_get_contents('php://input'),true);
        

        }
    //echo "Informacion".file_get_contents('php://input');

}else{
    echo "DB FOUND CONNECTED";
}
?>