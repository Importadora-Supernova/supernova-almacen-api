<?php
// conexion con base de datos 
include '../conexion/conn.php';

// declarar array para respuestas 
$response = array();
$evento   = array();
$tickets  = array();


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
        $id = intval($_GET['id']);

        $sql = 'SELECT e.id_evento,e.nombre_evento,e.fecha,e.horario,e.lugar,d.capacidad FROM eve_eventos e LEFT JOIN eve_detalle_evento d ON e.id_evento = d.id_evento WHERE e.id_evento='.$id.'';

        $capacidad = '';
        $result = mysqli_query($con,$sql);
        while($row = mysqli_fetch_assoc($result)){
            $evento['id']        = $row['id_evento'];
            $evento['nombre']    = $row['nombre_evento'];
            $evento['fecha']     = $row['fecha'];
            $evento['horario']   = $row['horario'];
            $evento['lugar']     = $row['lugar'];
            $evento['capacidad'] = $row['capacidad'];
            $capacidad           = $row['capacidad'];
        }

        if($capacidad != NULL){
            $sqlDatos = 'SELECT * FROM eve_seccion WHERE id_evento=? ORDER BY nombre_seccion';
            $stmt = $con->prepare($sqlDatos);
            $stmt->bind_param('i',$id);
            $stmt->execute();
            $result = $stmt->get_result();
            $tickets = $result->fetch_all(MYSQLI_ASSOC);
        }

        $response['evento']  = $evento;
        $response['tickets'] = $tickets;
        echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT); 

    }   

    if($methodApi == 'POST'){
        $con->autocommit(false);
        try{    
            $_POST = json_decode(file_get_contents('php://input'),true);
            $estatus  = 'Disponible';
            $evento   = $_POST['id_evento'];
            $columnas = $_POST['columnas'];
            $filas    = $_POST['filas'];
            $personas = $_POST['personas'];
            $tickets  = $_POST['json_tickets'];
    
            $secciones = $columnas*$filas;
            $capacidad = $secciones*$personas;
            $ocupado   = 0;
    
            $sql = 'INSERT INTO eve_detalle_evento (id_evento,filas,columnas,secciones,personas_seccion,capacidad) VALUES (?,?,?,?,?,?)';
            $stmt = $con->prepare($sql);
            $stmt->bind_param('iiiiii',$evento,$filas,$columnas,$secciones,$personas,$capacidad);
            $reta = $stmt->execute();

            $sqlUpdate = 'UPDATE eve_eventos SET estatus=2 WHERE id_evento=?';
            $stmtu = $con->prepare($sqlUpdate);
            $stmtu->bind_param('i',$evento);
            $retaU = $stmtu->execute();
    
            if($reta == 1 && $retaU == 1){
                $band = false;
                foreach($tickets as $data)
                {
                    $sqlInsert = 'INSERT INTO eve_seccion(id_evento,nombre_seccion,ocupado,estatus,fecha_created)VALUES(?,?,?,?,?)';
                    $stmt2 = $con->prepare($sqlInsert);
                    $stmt2->bind_param('isiss',$evento,$data['ticket'],$ocupado,$estatus,$fecha);
                    $exito = $stmt2->execute();
                    if($exito == 1){
                        $band = true;
                    }else{
                        $band = false;
                        break;
                    }
                }
                if($band){
                    $con->commit();
                    header("HTTP/1.1 200 OK");
                    $response['status'] = 200;
                    $response['mensaje'] = 'Se completo la configuracion correctamente';
                    echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT); 
                }else{
                    $con->rollback();
                    header("HTTP/1.1 400");
                    $response['status'] = 400;
                    $response['mensaje'] = 'No se pudo completar el proceso';
                    echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                }
            }else{
                $con->rollback();
                header("HTTP/1.1 400");
                $response['status'] = 400;
                $response['mensaje'] = 'No se pudo completar el proceso';
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