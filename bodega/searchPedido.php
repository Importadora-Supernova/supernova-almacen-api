<?php
// conexion con base de datos 
include '../conexion/conn.php';
date_default_timezone_set('America/Mexico_City');

// declarar array para respuestas 
$response = array();

//incluir middleware
include '../middleware/validarToken.php';

// insertamos cabeceras para permisos 

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept,Authorization, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");
header("Content-Type: JSON");
header('Content-Type: application/json;charset=utf-8'); 

$fecha = date('Y-m-d H:i:s');

// validamos si hay conexion 
if($con){
    if($token_access['token']){
        function validarFecha($date, $format = 'Y-m-d H:i:s')
        {
            $d = DateTime::createFromFormat($format, $date);
            return $d && $d->format($format) == $date;
        }
    
        $methodApi = $_SERVER['REQUEST_METHOD'];

        switch($methodApi){
            // metodo post 
            case 'POST':
                try{
                    $_POST = json_decode(file_get_contents('php://input'),true);
                    $con->autocommit(false);
                    $id_usuario  = $_POST['id_usuario'];
                    $orden       = $_POST['orden'];
                    $motivo      = $_POST['motivo'];
                    $descripcion = $_POST['descripcion'];
                    $accion      = $_POST['accion'];
                    $noti        = 'si';
                    $productos   = $_POST['productos'];
    
                    // actualizamos orden a pausado
                    $sqlUpdate = 'UPDATE folios SET estatus="Pausado" WHERE orden="'.$_POST['orden'].'"';
                    $result = mysqli_query($con,$sqlUpdate);
    
                    // insertamos registro de notificacion
                    $sqlInsert = 'INSERT INTO notificaciones (id_empleado,orden,motivo,descripcion,accion,notificacion,fecha_pausado) VALUES (?,?,?,?,?,?,?)';
                    $stmt = $con->prepare($sqlInsert);
                    $stmt->bind_param('sssssss',$id_usuario,$orden,$motivo,$descripcion,$accion,$noti,$fecha);
                    $resultInsert = $stmt->execute();
    
                    $last_id = $con->insert_id;
                    $user = intval($id_usuario);
                    //insertamos un msj inicial en los comentarios
                    $sqlInsertMensaje = 'INSERT INTO notificaciones_mensajes(id_notificacion,mensaje,user_created,fecha_created) VALUES(?,?,?,?)';
                    $stmtMensaje = $con->prepare($sqlInsertMensaje);
                    $stmtMensaje->bind_param('isis',$last_id,$descripcion,$user,$fecha);
                    $resultInsertMensaje = $stmtMensaje->execute();
    
                    $band = false;
                    //creamos inserciones por productos faltantes
                    foreach($productos as $data){
                        $sqlInsertFaltante = 'INSERT INTO productos_pedido_pausado (id_producto,orden,cantidad_faltante,fecha_created,user_created)VALUES(?,?,?,?,?)';
                        $stmtInsert = $con->prepare($sqlInsertFaltante);
                        $stmtInsert->bind_param('isisi',$data['id_producto'],$orden,$data['cantidad'],$fecha,$user);
                        $res = $stmtInsert->execute();
                        if($res == 1){
                            $band = true;
                        }else{
                            $band = false;
                            $con->rollback();
                            break;
                        }
                    }
    
                    if($sqlUpdate && $resultInsert == 1 && $resultInsertMensaje == 1 && $band){
                        $con->commit();
                        header("HTTP/1.1 200");
                        $response['mensaje'] = 'La orden fue pausada correctamente';
                        echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                    }else{
                        $con->rollback();
                        header("HTTP/1.1 400");
                        $response['mensaje'] = 'Ocurrio un error,No se podo completar la accion';
                        echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                    }
                }catch(Exception $e){
                    $con->rollback();
                    header("HTTP/1.1 400");
                    $response['mensaje'] = $e->getMessage();
                    echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                }
            break;
            // metodo get 
            case 'GET':
             // para obtener un registro especifico
             if(isset($_GET['orden'])){ 
                 $sql = 'SELECT *FROM folios  WHERE orden='.$_GET['orden'].'';
                 $result = mysqli_query($con,$sql);
                 $i=0;
                 while($row = mysqli_fetch_assoc($result)){
                     $response[$i]  ['cliente'] = $row['nombres'];
                     $response[$i]['orden'] = $row['orden'];
                     $response[$i]['estatus'] = $row['estatus'];
                     $response[$i]['cajas'] = $row['cajas'];
                     $response[$i]['paqueteria'] = $row['paqueteria'];
                     $response[$i]['fecha'] = $row['fecha'];
                     $response[$i]['fecha_procesado'] = $row['fecha_procesado'];
                     $response[$i]['fecha_almacen'] = $row['fecha_almacen'];
                     $response[$i]['fecha_salida'] = $row['fecha_salida'];
                     $response[$i]['fecha_entrega'] = $row['fecha_entrega'];
                     $i++;
                 }

                 echo  json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
              } else if(isset($_GET['pagados'])){
                    $sqlPagados = 'SELECT * FROM folios WHERE estatus="Pagado" or estatus="Pausado" or estatus="Resuelto" order by fecha_entrega ';
                    $resultPagados = mysqli_query($con,$sqlPagados);
                    $i=0;
                    while($row = mysqli_fetch_assoc($resultPagados)){
                        $response[$i]  ['cliente'] = $row['nombres'];
                        $response[$i]['orden'] = $row['orden'];
                        $response[$i]['estatus'] = $row['estatus'];
                        $response[$i]['cajas'] = $row['cajas'];
                        $response[$i]['paqueteria'] = $row['paqueteria'];
                        $response[$i]['efectivo'] = $row['efectivo'];
                        $response[$i]['nota'] = $row['nota'];
                        $response[$i]['fecha'] = $row['fecha'];
                        $response[$i]['impresion'] = $row['impresion'];
                        $response[$i]['marca_tiempo'] = $row['marca_tiempo'];
                        $response[$i]['fecha_procesado'] = $row['fecha_procesado'];
                        $response[$i]['fecha_almacen'] = $row['fecha_almacen'];
                        $response[$i]['fecha_salida'] = $row['fecha_salida'];
                        $response[$i]['fecha_entrega'] = $row['fecha_entrega'];
                        $response[$i]['entrega'] = validarFecha($row['fecha_entrega']);
                        $i++;
                    }

                    echo  json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
            }else{
                // $sql = 'SELECT f.nombres,f.orden,f.paqueteria,f.fecha,f.fecha_almacen,e.cajas,e.bolsas FROM folios f INNER JOIN empaquetado e ON f.orden = e.orden WHERE f.estatus = "Listo para salida" or f.estatus = "Esperando por guia" or f.estatus = "Medidas enviadas" or f.estatus = "Guia enviada"';
                $sql = 'SELECT *FROM folios WHERE estatus = "Listo para salida" or estatus = "Esperando por guia" or estatus = "Medidas enviadas" or estatus = "Guia enviada"';
                $result = mysqli_query($con,$sql);
                $i=0;
                while($row = mysqli_fetch_assoc($result)){
                    $response[$i]  ['nombres'] = $row['nombres'];
                    $response[$i]['orden'] = $row['orden'];
                    $response[$i]['cajas'] = $row['cajas'];
                    $response[$i]['paqueteria'] = $row['paqueteria'];
                    // $response[$i]['bolsas'] = $row['bolsas'];
                    $response[$i]['fecha'] = $row['fecha'];
                    $response[$i]['fecha_almacen'] = $row['fecha_almacen'];
                    $i++;
                }
                echo  json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
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