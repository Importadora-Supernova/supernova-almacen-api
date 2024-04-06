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
        
        $sql = 'SELECT *FROM eve_usuarios';
        $stmt = $con->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        $response = $result->fetch_all(MYSQLI_ASSOC);
        echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT); 
    }   

    if($methodApi == 'POST'){
        try{
            $con->autocommit(false);
            $_POST = json_decode(file_get_contents('php://input'),true);

            $nombre           = $_POST['nombre'];
            $apellido         = $_POST['apellido'];
            $email            = $_POST['email'];
            $telefono         = $_POST['telefono'];
            $direccion        = $_POST['direccion'];
            $facebook         = $_POST['facebook'];
            $instagram        = $_POST['instagram'];
            $tiktok           = $_POST['tiktok'];
            $id_evento        = $_POST['id_evento'];
    
            $sqlInsert = 'INSERT INTO eve_usuarios(nombre,apellido,email,telefono,direccion,facebook,instagram,tiktok,fecha_register) VALUES(?,?,?,?,?,?,?,?,?)';
            $stmti = $con->prepare($sqlInsert);
            $stmti->bind_param('sssssssss',$nombre,$apellido,$email,$telefono,$direccion,$facebook,$instagram,$tiktok,$fecha);
            $reta = $stmti->execute();
    
            if($reta == 1){
    
                $last_id = $con->insert_id; 
                $sqlTickets = 'SELECT *FROM eve_seccion WHERE id_evento=? AND ocupado=0 ORDER BY nombre_seccion LIMIT 1';
                $stmt = $con->prepare($sqlTickets);
                $stmt->bind_param('i',$id_evento);
                $stmt->execute();
                $result = $stmt->get_result();
                $tickets = $result->fetch_all(MYSQLI_ASSOC);
                $band = false;
    
                foreach($tickets as $data){
                    $sqlInsertTicket = 'INSERT INTO eve_ticket_user(id_ticket,id_user,fecha_register) VALUES (?,?,?)';
                    $stmInsert = $con->prepare($sqlInsertTicket);
                    $stmInsert->bind_param('iis',$data['id_seccion'],$last_id,$fecha);
                    $insert = $stmInsert->execute();
    
                    if($insert == 1){
                        $band = true;
                    }else{
                        $band = false;
                        break;
                    }
    
                    $sqlUpdate = 'UPDATE eve_seccion SET ocupado=1,estatus="Ocupado" WHERE id_seccion='.$data['id_seccion'].'';
                    $resultado = mysqli_query($con,$sqlUpdate);
    
                    $band = $resultado == 1 ? true : false;
                }
    
                if($band == true){
                    $con->commit();
                    header("HTTP/1.1 200");
                    $response['status'] = 200;
                    $response['mensaje'] = 'Registros Guardados Exitosamente';
                    echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT); 
                }else{
                    $con->rollback();
                    header("HTTP/1.1 400");
                    $response['status'] = 400;
                    $response['mensaje'] = 'Ocurrio un error al ejecutar proceso';
                    echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT); 
                }
            }else{
                $con->rollback();
                header("HTTP/1.1 400");
                $response['status'] = 400;
                $response['mensaje'] = 'Ocurrio un error al crear registro';
                echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT); 
            }
        }catch(Exception $e){
            $con->rollback();
            header("HTTP/1.1 400");
            $response['status'] = 400;
            $response['mensaje'] = $e->getMessage();
            echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT); 
        }
    }

    if($methodApi == 'PUT'){
        $_PUT = json_decode(file_get_contents('php://input'),true);

      
    }
}else{
echo "DB FOUND CONNECTED";
}

?>