<?php
// conexion con base de datos 
include '../conexion/conn.php';
require_once '../class/querys.php';

//import middleware
include '../middleware/validarToken.php';

date_default_timezone_set('America/Mexico_City');
//incluir middleware
$fecha_actual = date('Y-m-d H:i:s');


// declarar array para respuestas 
$response = array();
$caracteristicas = array();
$atributos = array();

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

    if($token_access['token']){
        $methodApi = $_SERVER['REQUEST_METHOD'];

        if($methodApi == 'GET'){

            if(isset($_GET['id'])){

                $id = $_GET['id'];
                $sql = 'SELECT * FROM admin_caracteristicas WHERE id_admin_caracteristica=?';
                $result = $query->getQueryId($con,$sql,$id);
                echo json_encode($result,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                $con->close(); 

            }else if(isset($_GET['todas'])){

                $result = $query->getQuery($con,'SELECT *FROM admin_caracteristicas');
                $i=0;
                $sql = 'SELECT *FROM view_atributos_caracteristicas WHERE id_caracteristica=?';
                foreach ($result as $row) {
                    $caracteristicas[$i]['id_admin_caracteristica'] = $row["id_admin_caracteristica"];
                    $caracteristicas[$i]['nombre_caracteristica'] = $row["nombre_caracteristica"];
                    $resultado = $query->getQueryIdArray($con,$sql,$row["id_admin_caracteristica"]);
                    $j=0;
                    foreach($resultado as $fill){
                        $atributos[$j]['id'] = $fill['id'];
                        $atributos[$j]['nombre_atributo']= $fill['nombre_atributo'];
                        $atributos[$j]['id_caracteristica']= $fill['id_caracteristica'];
                        $atributos[$j]['type'] = $fill['type'];
                        $atributos[$j]['preffix'] = $fill['preffix'];
                        $atributos[$j]['selected'] = false;
                        $j++;
                    }
                    $caracteristicas[$i]['atributos'] = $atributos;
                    $i++;
                }
                $response['caracteristicas'] = $caracteristicas;
                echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);

            }else if(isset($_GET['atributos_subcategorias'])){

                $id = $_GET['atributos_subcategorias'];

                $result = $query->getQuery($con,'SELECT *FROM admin_caracteristicas');
                $i=0;
                $sql = "SELECT * FROM view_subcategorias_atributos WHERE id_subcategoria=".$id." and id_caracteristica=?";
                foreach ($result as $row) {
                    $caracteristicas[$i]['id_admin_caracteristica'] = $row["id_admin_caracteristica"];
                    $caracteristicas[$i]['nombre_caracteristica'] = $row["nombre_caracteristica"];
                    $resultado = $query->getQueryIdArray($con,$sql,$row["id_admin_caracteristica"]);
                    $j=0;
                    foreach($resultado as $fill){
                        $atributos[$j]['id'] = $fill['id'];
                        $atributos[$j]['nombre_atributo']= $fill['nombre_atributo'];
                        $atributos[$j]['id_caracteristica']= $fill['id_caracteristica'];
                        $atributos[$j]['type'] = $fill['type'];
                        $atributos[$j]['preffix'] = $fill['preffix'];
                        $atributos[$j]['selected'] = false;
                        $atributos[$j]['value'] = '';
                        $j++;   
                    }
                    $caracteristicas[$i]['atributos'] = $atributos;
                    $atributos = [];  
                    // if(!$resultado){
                    //     $caracteristicas[$i]['atributos'] = [];  
                    // }
                    $i++;
                }
                $response['caracteristicas'] = $caracteristicas;
                echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);

            }else{
                $result = $query->getQuery($con,'SELECT *FROM admin_caracteristicas');
                echo json_encode($result,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
            }
        }

        if($methodApi == 'POST'){
             // metodo POST
            $_POST = json_decode(file_get_contents('php://input'),true);
            $nombre  = $_POST['nombre_caracteristica'];

            //validacion de campos 
            if($nombre == ''){
                header("HTTP/1.1 400");
                $response['status'] = 400;
                $response['mensaje'] = 'Debes agregar todos los campos, existen campos vacios';
                echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT); 
            }else{
                //preparar sentencia
                if(!($sentencia = $con->prepare("INSERT INTO admin_caracteristicas (nombre_caracteristica,fecha_created) VALUES (?,?)"))){
                    echo "Falló la preparación: (" . $con->errno . ") " . $con->error;
                }

                //vincular datos, parametros y tipo de datos
                if(!$sentencia->bind_param("ss", $nombre,$fecha)){    
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
    }else{
        echo $token_access['validate'];
    }

    //echo "Informacion".file_get_contents('php://input');

}else{
    echo "DB FOUND CONNECTED";
}
?>