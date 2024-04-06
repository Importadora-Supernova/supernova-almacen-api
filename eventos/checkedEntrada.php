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
        if(isset($_GET['id_evento'])){
            $evento = $_GET['id_evento'];

            $sql = 'SELECT t.id_ticket_user,t.id_ticket,t.id_user,t.checked_ticket,t.fecha_checked,s.id_evento,s.nombre_seccion,u.nombre,u.apellido,u.telefono,u.email FROM eve_ticket_user t INNER JOIN eve_seccion s ON t.id_ticket = s.id_seccion INNER JOIN eve_usuarios u ON t.id_user = u.id_usuario  WHERE s.id_evento=? ORDER BY t.checked_ticket';

            $stmt = $con->prepare($sql);
            $stmt->bind_param('i',$evento);
            $stmt->execute();
            $result = $stmt->get_result();
            $response = $result->fetch_all(MYSQLI_ASSOC);
            echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT); 
        }else if(isset($_GET['id_ticket'])){

            $ticket = $_GET['id_ticket'];

            $sql = 'SELECT t.id_ticket_user,t.id_ticket,t.id_user,t.checked_ticket,t.fecha_checked,s.id_evento,s.nombre_seccion,u.nombre,u.apellido,u.telefono,u.email FROM eve_ticket_user t INNER JOIN eve_seccion s ON t.id_ticket = s.id_seccion INNER JOIN eve_usuarios u ON t.id_user = u.id_usuario  WHERE t.id_ticket=? ORDER BY t.checked_ticket';

            $stmt = $con->prepare($sql);
            $stmt->bind_param('i',$ticket);
            $stmt->execute();
            $result = $stmt->get_result();
            $response = $result->fetch_assoc();
            echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
        }else{
            //
        }
        
    }


    if($methodApi == 'POST'){
        $_POST = json_decode(file_get_contents('php://input'),true);

        $user   = $_POST['user'];
        $ticket = $_POST['ticket'];

        $sql = 'SELECT t.id_ticket_user,t.id_ticket,t.id_user,t.checked_ticket,s.id_evento,s.nombre_seccion,u.nombre,u.apellido FROM eve_ticket_user t INNER JOIN eve_seccion s ON t.id_ticket = s.id_seccion INNER JOIN eve_usuarios u ON t.id_user = u.id_usuario WHERE t.id_ticket=? AND t.id_user=?';

        $stmt = $con->prepare($sql);
        $stmt->bind_param('ii',$ticket,$user);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();

        if($data == null){
            header("HTTP/1.1 200 OK");
            $response['status'] = 200;
            $response['process'] = false;
            $response['mensaje'] = 'No existe esta entrada';
        }else{
            if($data['checked_ticket'] == 1){
                header("HTTP/1.1 200 OK");
                $response['status'] = 200;
                $response['process'] = false;
                $response['mensaje'] = 'Esta entrada ya fue registrada';
            }else{
                $sqlUpdated = 'UPDATE eve_ticket_user SET checked_ticket=1,fecha_checked="'.$fecha.'" WHERE id_ticket_user='.$data['id_ticket_user'].'';
                $resultado = mysqli_query($con,$sqlUpdated);
                if($resultado == 1){
                    header("HTTP/1.1 200 OK");
                    $response['status'] = 200;
                    $response['data_user'] = $data['nombre'].' '.$data['apellido'];
                    $response['asiento']  = $data['nombre_seccion'];
                    $response['process'] = true;
                }else{
                    header("HTTP/1.1 400");
                    $response['status'] = 400;
                    $response['process'] = false;
                    $response['mensaje'] = 'Ocurrio un error, intentelo nuevamente';
                }
            }
        }
        echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT); 
    }


}else{
echo "DB FOUND CONNECTED";
}

?>