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
        $pass = md5('IMPSN168IZA');
        echo $pass;
    }

    if($methodApi == 'POST'){

        try{
            $con->autocommit(false);
            $_POST = json_decode(file_get_contents('php://input'),true);

            $productos = $_POST['productos'];
            $nuevo  = 'si';
            $status = 'Pedido Recibido';
            $band   = false;
            $pedido = $_POST['id_pedido'];
            $almacen = $_POST['id_almacen'];
            $price = '0';

            foreach($productos as $data){
                $tope   = strval($data['pz_caja']);
                $piezas = intval($data['pz_caja']);
                $cajas  = intval($data['num_cajas']);
                $stock = $piezas*$cajas;
                if($data['nuevo'] == 1){

                    //realizamos insert
                    $sqlInsert = 'INSERT INTO productos(nombre,codigo,preciou,preciom,precioc,topec,nuevo,fechan,almacen) VALUES(?,?,?,?,?,?,?,?,?)';
                    $stmt = $con->prepare($sqlInsert);
                    $stmt->bind_param('ssssssssi',$data['nombre_producto'],$data['codigo_producto'],$price,$price,$price,$tope,$nuevo,$fecha,$stock);
                    $reta = $stmt->execute();
                    $last_id = $con->insert_id;

                    $sqlInsertAlmacen = 'INSERT INTO almacen_producto (id_almacen,id_producto,cantidad) VALUES ('.$almacen.','.$last_id.','.$stock.')';
                    $resultado = mysqli_query($con,$sqlInsertAlmacen);

                    $sqlHistorial = 'INSERT INTO historial_carga_producto (id_producto,id_almacen,cantidad,fecha_created) VALUES ('.$last_id.','.$almacen.','.$stock.',"'.$fecha.'")';
                    $resHistorial = mysqli_query($con,$sqlHistorial);

                    if($reta == 1 && $resultado == 1 && $resHistorial == 1){
                        $band = true;
                    }else{
                        $band = false;
                        $con->rollback();
                        break;
                    }
                }else{
                    //actualizamos registro
                    $sqlUpdated = 'UPDATE productos SET almacen=almacen+'.$stock.' WHERE id='.$data['id_producto'].'';
                    $result = mysqli_query($con,$sqlUpdated);

                    $query_select = 'SELECT *FROM almacen_producto WHERE id_almacen='.$almacen.' AND id_producto='.$data['id_producto'].'';
                    $resultado = mysqli_query($con,$query_select);
                    $fill = mysqli_fetch_assoc($resultado);
                    $resultado = 0;

                    if($fill){
                        $sqlUpdate = 'UPDATE almacen_producto SET cantidad=cantidad+'.$stock.'  WHERE id_almacen='.$almacen.' AND id_producto='.$data['id_producto'].'';
                        $resultado = mysqli_query($con,$sqlUpdate);

                    }else{
                        $sqlInsertAlmacen = 'INSERT INTO almacen_producto (id_almacen,id_producto,cantidad) VALUES ('.$almacen.','.$data['id_producto'].','.$stock.')';
                        $resultado = mysqli_query($con,$sqlInsertAlmacen);
                    }

                    $sqlHistorial = 'INSERT INTO historial_carga_producto (id_producto,id_almacen,cantidad,fecha_created) VALUES ('.$data['id_producto'].','.$almacen.','.$stock.',"'.$fecha.'")';
                    $resHistorial = mysqli_query($con,$sqlHistorial);

                    if($result  == 1 && $resultado == 1 && $resHistorial == 1){
                        $band = true;
                    }else{
                        $band = false;
                        $con->rollback();
                        break;
                    }
                }
            }

            $sqlUpdatedPedido = 'UPDATE admin_pedido_contenedor SET estatus=?,fecha_recibido=? WHERE id_pedido_contenedor=?';
            $stmtu = $con->prepare($sqlUpdatedPedido);
            $stmtu->bind_param('ssi',$status,$fecha,$pedido);
            $retaUpdated = $stmtu->execute();

            if($retaUpdated == 1 && $band){
                $con->commit();
                header("HTTP/1.1 200 OK");
                $response['status'] = 200;
                $response['mensaje'] = 'Se proceso el pedido correctamente';
                echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT); 
            }else{
                header("HTTP/1.1 400");
                $con->rollback();
                $response['status'] = 400;
                $response['mensaje'] = 'No se pudo completar el proceso';
                echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
            }   
        }catch(Exception $e){
            header("HTTP/1.1 400");
            $con->rollback();
            $response['status'] = 400;
            $response['mensaje'] = $e->getMessage();
            echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
        }
    }

    if($methodApi == 'PUT'){
        $_PUT = json_decode(file_get_contents('php://input'),true);

        $id = $_GET['id'];
        $proveedor = $_PUT['proveedor'];

        $sql = 'UPDATE detalle_pedido_contenedor SET id_proveedor=? WHERE id_detalle_pedido_contenedor=?';
        $stmt = $con->prepare($sql);
        $stmt->bind_param('ii',$proveedor,$id);
        $reta = $stmt->execute();

        if($reta == 1){
            header("HTTP/1.1 200 OK");
            $response['status'] = 200;
            $response['mensaje'] = 'Se actualizo el registro correctamente';
            echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT); 
        }else{
            header("HTTP/1.1 400");
            $response['status'] = 400;
            $response['mensaje'] = 'No se pudo completar el proceso';
            echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
        }
    }



}else{
echo "DB FOUND CONNECTED";
}

?>