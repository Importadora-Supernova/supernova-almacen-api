<?php
date_default_timezone_set('America/Mexico_City');
// conexion con base de datos 
include 'conexion/conn.php';
//incluir middleware
include 'middleware/midleware.php';
// declarar array para respuestas 
$response = array();

// insertamos cabeceras para permisos 

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");
header("Content-Type: JSON");
header('Content-Type: application/json;charset=utf-8'); 



// validamos si hay conexion 
if($con){
    
    if($validate == 'validado'){
        $methodApi = $_SERVER['REQUEST_METHOD'];

        switch($methodApi){
            // metodo post 
            case 'POST':
                        $_POST = json_decode(file_get_contents('php://input'),true);

                        $con->autocommit(false);

                        header("HTTP/1.1 200 OK");

                        $sqlUpdateOrigen = 'UPDATE almacen_producto SET cantidad=cantidad - '.$_POST['cantidad'].'  WHERE  id_almacen='.$_POST['id_almacen'].' AND id_producto='.$_POST['id_producto'].'';
                        $resUpdateOrigen = mysqli_query($con,$sqlUpdateOrigen);
                        
                        $sqlSelectDestino = 'SELECT *FROM almacen_producto WHERE id_producto='.$_POST['id_producto'].' AND id_almacen='.$_POST['id_almacen_destino'].'';
                        $resSelectDestino = mysqli_query($con,$sqlSelectDestino);
                        $fill = mysqli_fetch_assoc($resSelectDestino);

                        $resUpdateDestino='';

                        if($fill){
                            $sqlUpdateDestino = 'UPDATE almacen_producto SET cantidad=cantidad + '.$_POST['cantidad'].' WHERE id_almacen='.$_POST['id_almacen_destino'].'  AND id_producto='.$_POST['id_producto'].'';
                            $resUpdateDestino = mysqli_query($con,$sqlUpdateDestino);
                        }else{
                            $sqlUpdateDestino = 'INSERT INTO almacen_producto (id_almacen,id_producto,cantidad) VALUES ('.$_POST['id_almacen_destino'].','.$_POST['id_producto'].','.$_POST['cantidad'].')';
                            $resUpdateDestino = mysqli_query($con,$sqlUpdateDestino);
                        }

                        $InsertHistorial = 'INSERT INTO traslados (id_almacen_origen,id_almacen_destino,id_producto,cantidad,id_usuario,fecha_created) VAlUES ('.$_POST['id_almacen'].','.$_POST['id_almacen_destino'].','.$_POST['id_producto'].','.$_POST['cantidad'].',1,"'.$_POST['fecha_created'].'")';
                        $resInsertHistorial = mysqli_query($con,$InsertHistorial);

                        if($resUpdateOrigen and $resUpdateDestino and $resInsertHistorial){
                            $con->commit();
                            header("HTTP/1.1 200 OK");
                            $response['status'] = 200;
                            $response['mensaje'] = 'Transacción ejecutada correctamente';
                            echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                        }else{
                            $con->rollback();
                            header("HTTP/1.1 400");
                            $response['status'] = 400;
                            $response['mensaje'] = 'No se pudo ejecutar la transacción';
                            echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                        }
            break;
            // metodo get 
            case 'GET':
                // para obtener un registro especifico
                    if(isset($_GET['id'])){
                        $sql = 'SELECT * FROM productos  where a.id="'.$_GET['id'].'"';
                        $result = mysqli_query($con,$sql);
                        $i=0;
                        while($row = mysqli_fetch_assoc($result)){
                            $response['id'] = $row['id'];
                            $response['nombre'] = $row['nombre'];
                            $response['codigo'] = $row['codigo'];
                            $response['almacen'] = $row['almacen'];
                            $response['descripcion'] = $row['descripcion'];
                            $i++;
                        }
                        echo json_encode($response,JSON_PRETTY_PRINT);
                    } else{

                                $sql = 'select *from vista_traslados';
                                $result = mysqli_query($con,$sql);
                                $i=0;
                                while($row = mysqli_fetch_assoc($result)){
                                    $response[$i]['id'] = $row['id_traslado'];
                                    $response[$i]['almacen_origen'] = $row['almacen_origen'];
                                    $response[$i]['almacen_destino'] = $row['almacen_destino'];
                                    $response[$i]['nombre'] = $row['nombre'];
                                    $response[$i]['codigo'] = $row['codigo'];
                                    $response[$i]['cantidad'] = $row['cantidad'];
                                    $response[$i]['fecha_created'] = $row['fecha_created'];
                                    $response[$i][$row['fecha_created']] = $row['cantidad'];
                                    $i++;
                                }
                                echo  json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                    }
            break;
        }
    }else{
        header("HTTP/1.1 401");
        $response['mensaje'] = 'Tu TOKEN '.$validate.' ';
        echo  json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
    }
    //echo "Informacion".file_get_contents('php://input');

}else{
    echo "DB FOUND CONNECTED";
}
?>