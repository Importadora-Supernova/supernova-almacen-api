<?php
// conexion con base de datos 
include '../conexion/conn.php';
date_default_timezone_set('America/Mexico_City');
// declarar array para respuestas 
$response       = array();
$notificaciones = array();
$mensajes       = array();

// insertamos cabeceras para permisos 

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept,Authorization,Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");
header("Content-Type: JSON");
header('Content-Type: application/json;charset=utf-8'); 
$fecha = date('Y-m-d H:i:s');
if($con){

    $methodApi = $_SERVER['REQUEST_METHOD'];
    
    if($methodApi == 'GET'){

        // es para obtener todos los registros  por codigo
        if(isset($_GET['orden'])){
            $sql = 'SELECT f.nombres,f.estatus,f.orden,n.id as id_notificacion,n.motivo,n.descripcion,n.accion,n.observacion,n.notificacion,n.fecha_pausado,n.fecha_resuelto FROM folios f INNER JOIN notificaciones n ON f.orden = n.orden WHERE f.estatus = "Pausado" AND n.orden="'.$_GET['orden'].'"';
            $result = mysqli_query($con,$sql);
    
            while($row = mysqli_fetch_assoc($result)){
                $response['orden'] = $row['orden'];
                $response['nombres'] = $row['nombres'];
                $response['estatus'] = $row['estatus'];
                $response['motivo'] = $row['motivo']; 
                $response['descripcion'] = $row['descripcion'];
                $response['accion']      = $row['accion'];
                $response['observacion'] = $row['observacion'];
                $response['notificacion'] = $row['notificacion'];
                $response['id_notificacion'] = $row['id_notificacion'];
                $response['fecha_pausado'] = $row['fecha_pausado'];
                $response['fecha_resuelto'] = $row['fecha_resuelto'];
    
                $sqlMensajes = 'SELECT n.id_notificaciones_mensajes as id_mensaje,n.mensaje,n.fecha_created,u.id_user_bodega,u.usuario_bodega FROM notificaciones_mensajes n INNER JOIN app_usuarios_bodega u ON n.user_created = u.id_user_bodega  WHERE n.id_notificacion='.$row['id_notificacion'].' ORDER BY n.id_notificaciones_mensajes DESC';
    
                $resultMensajes = mysqli_query($con,$sqlMensajes);
                $j=0;
                while($fill = mysqli_fetch_assoc($resultMensajes)){
                    $mensajes[$j]['id_mensaje'] = $fill['id_mensaje'];
                    $mensajes[$j]['mensaje'] = $fill['mensaje'];
                    $mensajes[$j]['id_user_bodega'] = $fill['id_user_bodega'];
                    $mensajes[$j]['usuario_bodega'] = $fill['usuario_bodega'];
                    $mensajes[$j]['fecha_created'] = $fill['fecha_created'];
                    $j++;
                }
                $response['mensajes'] = $mensajes;
                $mensajes  = [];    
            }
            //$response['notificaciones'] = $notificaciones;
            echo  json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
        }else{
            $sql = 'SELECT f.nombres,f.estatus,f.orden,n.id as id_notificacion,n.motivo,n.descripcion,n.accion,n.observacion,n.notificacion,n.fecha_pausado,n.fecha_resuelto FROM folios f INNER JOIN notificaciones n ON f.orden = n.orden WHERE f.estatus = "Pausado" ';
            $result = mysqli_query($con,$sql);
            $i=0;
    
            while($row = mysqli_fetch_assoc($result)){
                $response[$i]['orden'] = $row['orden'];
                $response[$i]['nombres'] = $row['nombres'];
                $response[$i]['estatus'] = $row['estatus'];
                $response[$i]['motivo'] = $row['motivo']; 
                $response[$i]['descripcion'] = $row['descripcion'];
                $response[$i]['accion']      = $row['accion'];
                $response[$i]['observacion'] = $row['observacion'];
                $response[$i]['notificacion'] = $row['notificacion'];
                $response[$i]['id_notificacion'] = $row['id_notificacion'];
                $response[$i]['fecha_pausado'] = $row['fecha_pausado'];
                $response[$i]['fecha_resuelto'] = $row['fecha_resuelto'];
    
                $sqlMensajes = 'SELECT n.id_notificaciones_mensajes as id_mensaje,n.mensaje,n.fecha_created,u.id_user_bodega,u.usuario_bodega FROM notificaciones_mensajes n INNER JOIN app_usuarios_bodega u ON n.user_created = u.id_user_bodega  WHERE n.id_notificacion='.$row['id_notificacion'].' ORDER BY n.id_notificaciones_mensajes DESC';
    
                $resultMensajes = mysqli_query($con,$sqlMensajes);
                $j=0;
                while($fill = mysqli_fetch_assoc($resultMensajes)){
                    $mensajes[$j]['id_mensaje'] = $fill['id_mensaje'];
                    $mensajes[$j]['mensaje'] = $fill['mensaje'];
                    $mensajes[$j]['id_user_bodega'] = $fill['id_user_bodega'];
                    $mensajes[$j]['usuario_bodega'] = $fill['usuario_bodega'];
                    $mensajes[$j]['fecha_created'] = $fill['fecha_created'];
                    $j++;
                }
                $response[$i]['mensajes'] = $mensajes;
                $mensajes  = [];    
                $i++;
            }
            //$response['notificaciones'] = $notificaciones;
            echo  json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
        }
    }

    if($methodApi == 'POST'){

        try
        {
            $_POST = json_decode(file_get_contents('php://input'),true);

            $con->autocommit(false);
            $orden       = $_POST['orden'];
            $accion      = $_POST['accion'];
            $observacion = $_POST['observacion'];
            $saldo       = $_POST['saldo']; 
            $id_usuario  = $_POST['id_usuario'];

            if($accion == 'Saldo a favor'){
                $sql = 'INSERT INTO saldo_favor (id_usuario,orden,descripcion,saldo,fecha_register) VALUES ('.$id_usuario.',"'.$orden.'","'.$accion.'",'.$saldo.',"'.$fecha.'")';
                $result = mysqli_query($con,$sql);

                $sqlUpdate = 'UPDATE usuario SET saldo_favor=saldo_favor+'.$saldo.' WHERE id='.$id_usuario.'';
                $resultado = mysqli_query($con,$sqlUpdate);

                if($result != 1 || $resultado != 1){
                    $con->rollback();
                    $con->close();
                    header("HTTP/1.1 400");
                    $response['mensaje'] = 'Ocurrio un error,No se podo completar la accion';
                    echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                }

            }
            
            $sqlUpdateFolio = 'UPDATE folios SET estatus="Resuelto" WHERE orden=?';
            $sqlUpdateNotification = 'UPDATE notificaciones SET accion=?,observacion=?,fecha_resuelto=? WHERE orden=?';
            
            $stmt = $con->prepare($sqlUpdateNotification);
            $stmt->bind_param("ssss",$accion,$observacion,$fecha,$orden);
            $result = $stmt->execute();

            if($result || $result == 1){
                $stmtFolio = $con->prepare($sqlUpdateFolio);
                $stmtFolio->bind_param("s",$orden);
                $resultUpdate = $stmtFolio->execute();
                if($resultUpdate || $resultUpdate == 1){
                    $con->commit();
                    $con->close();
                    header("HTTP/1.1 200");
                    $response['mensaje'] = 'La incidencia de la orden fue resuelta';
                    echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                }else{
                    $con->rollback();
                    $con->close();
                    header("HTTP/1.1 400");
                    $response['mensaje'] = 'Ocurrio un error,No se podo completar la accion';
                    echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                }
            }else{
                $con->rollback();
                $con->close();
                header("HTTP/1.1 400");
                $response['mensaje'] = 'Ocurrio un error,No se podo completar la accion';
                echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                }
        }catch(Exception $e)
        {
            $con->rollback();
            $con->close();
            header("HTTP/1.1 400");
            $response['mensaje'] = $e->getMessage();
            echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
        }
    }
}else{
    echo "DB FOUND CONNECTED";
}


?>