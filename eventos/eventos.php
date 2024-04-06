<?php
// conexion con base de datos 
include '../conexion/conn.php';

// declarar array para respuestas 
$response = array();


date_default_timezone_set('America/Mexico_City');
$fecha = date('Y-m-d H:i:s');

// insertamos cabeceras para permisos 

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept,Authorization, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");
header("Content-Type: JSON");
header('Content-Type: application/json;charset=utf-8'); 

if($con){
    
    $methodApi = $_SERVER['REQUEST_METHOD'];

    
    if($methodApi == 'GET'){
        if(isset($_GET['id'])){

            $id = $_GET['id'];
            $sql = 'SELECT *FROM eve_eventos WHERE id_evento=?';

            $stmt = $con->prepare($sql);
            $stmt->bind_param('i',$id);
            $stmt->execute();
            $result = $stmt->get_result();
            $response = $result->fetch_assoc();
            echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT); 

        }else if(isset($_GET['fecha'])){

            $fecha = $_GET['fecha'];
            $sql = 'SELECT *FROM eve_eventos WHERE LIKE fecha=?';

            $stmt = $con->prepare($sql);
            $stmt->bind_param('s',$fecha);
            $stmt->execute();
            $result = $stmt->get_result();
            $response = $result->fetch_all(MYSQLI_ASSOC);
            echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT); 

        }else if(isset($_GET['estatus'])){
            $estatus = intval($_GET['estatus']);
            $sql = 'SELECT *FROM eve_eventos WHERE  estatus=?';

            $stmt = $con->prepare($sql);
            $stmt->bind_param('i',$estatus);
            $stmt->execute();
            $result = $stmt->get_result();
            $response = $result->fetch_all(MYSQLI_ASSOC);
            echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT); 
        }else{
            $sql = 'SELECT *FROM eve_eventos';

            $stmt = $con->prepare($sql);
            $stmt->execute();
            $result = $stmt->get_result();
            $response = $result->fetch_all(MYSQLI_ASSOC);
            echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT); 
        }

    }   

    if($methodApi == 'POST'){
        $_POST = json_decode(file_get_contents('php://input'),true);

        $nombre       = $_POST['nombre_evento'];
        $descripcion  = $_POST['descripcion'];
        $fecha_evento = $_POST['fecha_evento'];
        $horario      = $_POST['horario'];
        $lugar        = $_POST['lugar'];
        $estatus      = 1;
        /** estatus-> 1=creado por iniciar, 2 = evento en proceso, 3 = evento terminado, 4 = evento cancelado */
        $sql = 'INSERT INTO eve_eventos (nombre_evento,descripcion,fecha,horario,lugar,estatus,fecha_created) VALUES (?,?,?,?,?,?,?)';
        $stmt = $con->prepare($sql);
        $stmt->bind_param('sssssis',$nombre,$descripcion,$fecha_evento,$horario,$lugar,$estatus,$fecha);
        $reta = $stmt->execute();
        if($reta == 1){
            header("HTTP/1.1 200 OK");
            $response['status'] = 200;
            $response['mensaje'] = 'Se registraron correctamente';
            echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT); 
        }else{
            header("HTTP/1.1 400");
            $response['status'] = 400;
            $response['mensaje'] = 'No se pudo completar el proceso';
            echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
        }

    }

    if($methodApi == 'PUT'){
        $_PUT = json_decode(file_get_contents('php://input'),true);

        $id           = $_GET['id'];
        $nombre       = $_PUT['nombre_evento'];
        $descripcion  = $_PUT['descripcion'];
        $fecha_evento = $_PUT['fecha_evento'];
        
        $horario      = $_PUT['horario'];
        $lugar        = $_PUT['lugar'];
        $latitud      = $_PUT['latitud'];
        $longitud     = $_PUT['longitud'];


        $sql = 'UPDATE eve_eventos SET nombre_evento=?,descripcion=?,fecha=?,horario=?,lugar=?,latitud=?,longitud=? WHERE id_evento=?';
        $stmt = $con->prepare($sql);
        $stmt->bind_param('sssssssi',$nombre,$descripcion,$fecha_evento,$horario,$lugar,$latitud,$longitud,$id);
        $reta = $stmt->execute();
        if($reta == 1){
            header("HTTP/1.1 200 OK");
            $response['status'] = 200;
            $response['mensaje'] = 'Se actualizo el evento correctamente';
            echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT); 
        }else{
            header("HTTP/1.1 400");
            $response['status'] = 400;
            $response['mensaje'] = 'No se pudo completar la accion';
            echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
        }
    }
}else{
echo "DB FOUND CONNECTED";
}

?>