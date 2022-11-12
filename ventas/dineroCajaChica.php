<?php
// conexion con base de datos 
include '../conexion/conn.php';
include '../middleware/midleware.php';
date_default_timezone_set('America/Mexico_City');
//incluir middleware


// declarar array para respuestas 
$response = array();

// insertamos cabeceras para permisos 

header('Access-Control-Allow-Origin:*');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept,Authorization, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");
header("Content-Type: JSON");
header('Content-Type: application/json;charset=utf-8'); 

$fecha = date('Y-m-d');
$fecha_actual = date('Y-m-d H:i:s');

// validamos si hay conexion 
if($con){
        $methodApi = $_SERVER['REQUEST_METHOD'];
        if($validate === 'validado'){
            
            if($methodApi == 'GET'){
                $sql = 'SELECT  *FROM dinero_cajachica WHERE  id=1';
                $result = mysqli_query($con,$sql);
                while($row = mysqli_fetch_assoc($result)){
                        $response['id'] =  $row['id'];
                        $response['fecha_recarga'] =  $row['fecha_recarga'];
                        $response['saldo'] =  $row['saldo'];
                }
                header("HTTP/1.1 200 OK");
                echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT); 
                
            }
            
            if($methodApi == 'POST'){
                $_POST = json_decode(file_get_contents('php://input'),true);
                $action = $_POST['action'];
                if($action == 'add'){
                    $con->autocommit(false);
                    $sqlInsert = 'INSERT INTO caja_chica (fecha,monto,motivo)  VALUES ("'.$fecha_actual.'","'.$_POST['monto'].'","'.$_POST['motivo'].'")';
                    $result = mysqli_query($con,$sqlInsert);

                    $sqlUpdate = 'UPDATE dinero_cajachica SET saldo=saldo-'.$_POST['monto'].' WHERE id=1';
                    $resultUpdate = mysqli_query($con,$sqlUpdate);

                    if($result && $resultUpdate){
                        header("HTTP/1.1 200");
                        $con->commit();
                        $response['mensaje'] = 'Gasto registrado exitosamente';
                        echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                    }else{
                        header("HTTP/1.1 400");
                        $con->rollback();
                        $response['mensaje'] = 'No se puedo registrar la salida';
                        echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                    } 
                }

                if($action == 'delete'){
                    $con->autocommit(false);
                    $sql = 'DELETE FROM caja_chica WHERE id='.$_POST['id'].'';
                    $resultDelete = mysqli_query($con,$sql);

                    $sqlUpdate = 'UPDATE dinero_cajachica SET saldo=saldo+'.$_POST['monto'].' WHERE id=1';
                    $resultUpdate = mysqli_query($con,$sqlUpdate);

                    if($resultDelete && $resultUpdate){
                        header("HTTP/1.1 200");
                        $con->commit();
                        $response['mensaje'] = 'Gasto registrado exitosamente';
                        echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                    }else{
                        header("HTTP/1.1 400");
                        $con->rollback();
                        $response['mensaje'] = 'No se puedo registrar la salida';
                        echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                    } 
                }
                //TOTAL    =>    SELECT SUM(cantidad*precio) FROM `registro_usuario` WHERE fecha_procesado LIKE '2022-10-26%' AND estatus='Pagado'
                //total pagos => SELECT SUM(monto) FROM pagos WHERE fecha LIKE '2022-10-26%';
                //EFECTIVO =>    SELECT SUM(monto) as total FROM pagos WHERE banco='efectivo' AND fecha LIKE '2022-10-26%';
                //OTRO     =>    SELECT SUM(monto) as total FROM pagos WHERE fecha LIKE '2022-10-26%' AND banco!='lemussa-bbva' AND banco!='website-bbva' AND banco!='isn-hsbc' AND banco!='isn-bbva' AND banco!='efectivo';
                //BANCOS   =>    SELECT SUM(monto) as total FROM pagos WHERE fecha LIKE '2022-10-26%' AND banco='banco';
                //TOTAL folios=> SELECT SUM(venta_paqueteria) as total_paqueteria,SUM(iva) as total_iva,SUM(seguro) as seguro,SUM(saldo_pendiente) as total_saldo_pendiente, SUM(venta_paqueteria+iva+seguro) as total FROM folios WHERE  fecha_pago LIKE 'fecha%'
                //          =>   SELECT SUM(p.precio_costo*r.cantidad) as total FROM productos p INNER JOIN registro_usuario r ON p.id = r.id_producto WHERE r.estatus='Pagado' AND r.fecha_procesado LIKE 'fecha%';
                if($action == 'caja'){

                    $sqltoken = 'SELECT token_caja_chica  FROM tokens_acceso WHERE id=1';
                    $restoken = mysqli_query($con,$sqltoken);
                    $fill = mysqli_fetch_assoc($restoken);

                    if($fill['token_caja_chica'] == $_POST['token']){
                        $con->autocommit(false);
                        $sqlUpdate = 'UPDATE dinero_cajachica SET saldo=saldo+'.$_POST['monto'].',fecha_recarga="'.$fecha_actual.'" WHERE id=1';
                        $resultUpdate = mysqli_query($con,$sqlUpdate);

                        $sqlInsert = 'INSERT INTO historial_ingreso (saldo,fecha) VALUES ("'.$_POST['monto'].'","'.$fecha_actual.'")';
                        $resultInsert = mysqli_query($con,$sqlInsert);

                        if($resultUpdate && $resultInsert){
                            header("HTTP/1.1 200");
                            $con->commit();
                            $response['mensaje'] = 'Dinero agregado exitosamente';
                            echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                        }else{
                            header("HTTP/1.1 400");
                            $con->rollback();
                            $response['mensaje'] = 'No se puedo registrar el dinero';
                            echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                        }
                    }else{
                        header("HTTP/1.1 400");
                        $response['mensaje'] = 'El token es incorrecto';
                        echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                    }
                    
                }
            }
        }else{
            header("HTTP/1.1 201");
            $response['status'] = 401;
            $response['mensaje'] = 'Token '.$validate;
            echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
            
        }
    //echo "Informacion".file_get_contents('php://input');

}else{
    echo "DB FOUND CONNECTED";
}
?>