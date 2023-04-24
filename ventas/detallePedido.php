<?php
// conexion con base de datos 
include '../conexion/conn.php';
include '../middleware/midleware.php';
date_default_timezone_set('America/Mexico_City');
//incluir middleware


// declarar array para respuestas 
$response = array();

// insertamos cabeceras para permisos 

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type,Authorization, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");
header("Content-Type: JSON");
header('Content-Type: application/json;charset=utf-8'); 

$fecha = date('Y-m-d');

$fecha_delete = date('Y-m-d H:i:s');

// validamos si hay conexion 
if($con){
    if($validate === 'validado'){
        $methodApi = $_SERVER['REQUEST_METHOD'];

        if($methodApi == 'GET'){
             // metodo get 
             // para obtener un registro especifico
            if(isset($_GET['orden'])){
                $sql = 'SELECT  *FROM productos_orden WHERE orden="'.$_GET['orden'].'" ';
                $result = mysqli_query($con,$sql);
                $i=0;
                while($row = mysqli_fetch_assoc($result)){      
                    $response[$i]['id'] =  $row['id'];
                    $response[$i]['id_producto'] =  $row['id_producto'];
                    $response[$i]['nombre'] =  $row['nombre'];
                    $response[$i]['codigo'] =  $row['codigo'];
                    $response[$i]['cantidad'] =  $row['cantidad'];
                    $response[$i]['precio'] =  $row['precio'];
                    $response[$i]['preciou'] =  $row['preciou'];
                    $response[$i]['preciom'] =  $row['preciom'];
                    $response[$i]['precioc'] =  $row['precioc'];
                    $response[$i]['preciov'] =  $row['preciov'];
                    $response[$i]['topem'] =  $row['topem'];
                    $response[$i]['topec'] =  $row['topec'];
                    $response[$i]['topev'] =  $row['topev'];
                    $response[$i]['descuento'] =  $row['descuento'];
                    $response[$i]['descuento_precio_docena'] =  $row['descuento_precio_docena'];
                    $response[$i]['almacen'] =  $row['almacen'];
                    $response[$i]['total'] =  $row['cantidad']*$row['precio'];
                    $response[$i]['check'] = false;
                    $i++;          
                }
                echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                
                } else if(isset($_GET['ordenLimit'])){
                    $sql = 'SELECT  *FROM registro_usuario WHERE orden="'.$_GET['ordenLimit'].'" LIMIT 1';
                    $result = mysqli_query($con,$sql);
                    $i=0;
                    while($row = mysqli_fetch_assoc($result)){
                        $response['id'] =  $row['id_usuario'];
                        $response['nombre'] =  $row['nombred'];
                        $response['apellido'] =  $row['apellidod'];
                        $response['direcciond'] =  $row['direcciond'];
                        $response['coloniad'] =  $row['coloniad'];
                        $response['ciudadd'] =  $row['ciudadd'];
                        $response['estadod'] =  $row['estadod'];
                        $response['codigopd'] =  $row['codigopd'];
                        $response['telefonod'] =  $row['telefonod'];
                        $response['rfc'] =  $row['rfc'];
                        $response['paqueteria'] =  $row['paqueteria'];
                        $i++;
                    }
                    echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                }
        }else if($methodApi == 'POST'){

            $_POST = json_decode(file_get_contents('php://input'),true);

            $accion = $_POST['action'];
            $cambio = $_POST['cambio'];
            
            $con->autocommit(false);

            if($accion == 'update'){

                if($cambio){
                    $sqlUpdate = 'UPDATE registro_usuario  SET cantidad='.$_POST['cantidad'].' WHERE id='.$_POST['id'].'';
                    $result = mysqli_query($con,$sqlUpdate);

                    $sqlUpdatePrice  = 'UPDATE registro_usuario  SET precio='.$_POST['price'].' WHERE orden="'.$_POST['orden'].'" AND codigo="'.$_POST['codigo'].'"';
                    $resultPrice = mysqli_query($con,$sqlUpdatePrice);
                    
                    $consulta = 'SELECT SUM(precio*cantidad) as total FROM registro_usuario WHERE orden="'.$_POST['orden'].'" ';
                    $resPedido = mysqli_query($con,$consulta);
                    $fill = mysqli_fetch_assoc($resPedido);

                    $sqlUpdateFolio = 'UPDATE folios SET total='.$fill['total'].',cantidad='.$_POST['cantidades'].' WHERE orden="'.$_POST['orden'].'"';
                    $resultFolio = mysqli_query($con,$sqlUpdateFolio);



                    if($result && $resultPrice && $resultFolio){
                        $con->commit();
                        header("HTTP/1.1 200 OK");
                        $response['status'] = 200;
                        $response['mensaje'] = 'Registro actualizado correctamente';
                        echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                    }else{
                        $con->rollback();
                        header("HTTP/1.1 400");
                        $response['status'] = 400;
                        $response['mensaje'] = 'No se pudo actualizar el registro';
                        echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                    }

                }else{
                    $sqlUpdate = 'UPDATE registro_usuario  SET cantidad='.$_POST['cantidad'].' WHERE id='.$_POST['id'].'';
                    $result = mysqli_query($con,$sqlUpdate);

                    $sqlUpdateFolio = 'UPDATE folios SET total=total+'.$_POST['suma'].',cantidad='.$_POST['cantidades'].' WHERE orden="'.$_POST['orden'].'"';
                    $resultFolio = mysqli_query($con,$sqlUpdateFolio);

                    if($result && $resultFolio){
                        $con->commit();
                        header("HTTP/1.1 200 OK");
                        $response['status'] = 200;
                        $response['mensaje'] = 'Registro actualizado correctamente';
                        echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                    }else{
                        $con->rollback();
                        header("HTTP/1.1 400");
                        $response['status'] = 400;
                        $response['mensaje'] = 'No se pudo actualizar el registro';
                        echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                    }
                }
            }

            if($accion == 'delete'){

                if($cambio){

                    $sqlUpdatePrice  = 'UPDATE registro_usuario  SET precio='.$_POST['precio'].' WHERE orden="'.$_POST['orden'].'" AND codigo="'.$_POST['codigo'].'"';
                    $resultPrice = mysqli_query($con,$sqlUpdatePrice);

                    $sqlUpdate = 'DELETE FROM registro_usuario  WHERE id="'.$_POST['id'].'"';
                    $result = mysqli_query($con,$sqlUpdate);
                    
                    $consulta = 'SELECT SUM(precio*cantidad) as total FROM registro_usuario WHERE orden="'.$_POST['orden'].'"';
                    $resPedido = mysqli_query($con,$consulta);
                    $fill = mysqli_fetch_assoc($resPedido);

                    $sqlUpdateFolio = 'UPDATE folios SET total='.$fill['total'].',cantidad='.$_POST['cantidades'].' WHERE orden="'.$_POST['orden'].'"';
                    $resultFolio = mysqli_query($con,$sqlUpdateFolio);


                    if($result && $resultPrice && $resultFolio){
                        $con->commit();
                        header("HTTP/1.1 200 OK");
                        $response['status'] = 200;
                        $response['mensaje'] = 'Registro eliminado correctamente';
                        echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                    }else{
                        $con->rollback();
                        header("HTTP/1.1 400");
                        $response['status'] = 400;
                        $response['mensaje'] = 'No se pudo eliminar el registro';
                        echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                    }
                }else{
                    $sqlUpdate = 'DELETE FROM registro_usuario  WHERE id='.$_POST['id'].'';
                    $result = mysqli_query($con,$sqlUpdate);

                    $sqlUpdateFolio = 'UPDATE folios SET total=total-'.$_POST['suma'].',cantidad='.$_POST['cantidades'].' WHERE orden="'.$_POST['orden'].'"';
                    $resultFolio = mysqli_query($con,$sqlUpdateFolio);

                    if($result && $resultFolio){
                        $con->commit();
                        header("HTTP/1.1 200 OK");
                        $response['status'] = 200;
                        $response['mensaje'] = 'Registro eliminado correctamente';
                        echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                    }else{
                        $con->rollback();
                        header("HTTP/1.1 400");
                        $response['status'] = 400;
                        $response['mensaje'] = 'No se pudo eliminar el registro';
                        echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                    }

                }
            }

            if($accion == 'add'){
                if($cambio){

                    $con->autocommit(false);

                    $consulta = 'SELECT id_producto FROM registro_usuario WHERE orden="'.$_POST['orden'].'" AND id_producto='.$_POST['id_producto'].'';
                    $resultConsulta = mysqli_query($con,$consulta);
                    $exist = mysqli_fetch_assoc($resultConsulta);

                    $result = '';

                    if($exist){
                        $sqlUpdate = 'UPDATE registro_usuario  SET cantidad='.$_POST['cantidad'].' WHERE id_producto='.$_POST['id_producto'].'';
                        $result = mysqli_query($con,$sqlUpdate);
                    }else{
                        $sqlInsert = 'INSERT INTO registro_usuario (id_usuario,id_producto,nombre,codigo,precio,cantidad,fecha,color,paqueteria,rfc,orden,nombred,apellidod,direcciond,coloniad,ciudadd,estadod,codigopd,telefonod,estatus,fecha_procesado)
                        VALUES ('.$_POST['id_usuario'].','.$_POST['id_producto'].',"'.$_POST['nombre'].'","'.$_POST['codigo'].'","'.$_POST['precio'].'",'.$_POST['cantidad'].',"'.$fecha_delete.'","","'.$_POST['paqueteria'].'","'.$_POST['rfc'].'","'.$_POST['orden'].'","'.$_POST['nombred'].'","'.$_POST['apellidod'].'","'.$_POST['direcciond'].'","'.$_POST['coloniad'].'","'.$_POST['ciudadd'].'","'.$_POST['estadod'].'","'.$_POST['codigopd'].'","'.$_POST['telefonod'].'","","")';
                        $result = mysqli_query($con,$sqlInsert);
                    }

                    $sqlUpdatePrice  = 'UPDATE registro_usuario  SET precio="'.$_POST['precio'].'" WHERE orden="'.$_POST['orden'].'" AND codigo="'.$_POST['codigo'].'"';
                    $resultPrice = mysqli_query($con,$sqlUpdatePrice);
                    
                    $consulta = 'SELECT SUM(precio*cantidad) as total FROM registro_usuario WHERE orden="'.$_POST['orden'].'"';
                    $resPedido = mysqli_query($con,$consulta);
                    $fill = mysqli_fetch_assoc($resPedido);

                    $sqlUpdateFolio = 'UPDATE folios SET total="'.$fill['total'].'",cantidad=cantidad+'.$_POST['cantidad'].' WHERE orden="'.$_POST['orden'].'"';
                    $resultFolio = mysqli_query($con,$sqlUpdateFolio);

                    if($result && $resultPrice && $resultFolio){
                        $con->commit();
                        header("HTTP/1.1 200 OK");
                        $response['status'] = 200;
                        $response['mensaje'] = 'Producto añadido correctamente';
                        echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                    }else{
                        $con->rollback();
                        header("HTTP/1.1 400");
                        $response['status'] = 400;
                        $response['mensaje'] = 'No se pudo guardar el registro';
                        echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                    }

                }else{


                    $con->autocommit(false);

                    
                    $consulta = 'SELECT id_producto FROM registro_usuario WHERE orden="'.$_POST['orden'].'" AND id_producto='.$_POST['id_producto'].'';
                    $resultConsulta = mysqli_query($con,$consulta);
                    $exist = mysqli_fetch_assoc($resultConsulta);

                    $result = '';

                    if($exist){
                        $sqlUpdate = 'UPDATE registro_usuario  SET cantidad='.$_POST['cantidad'].' WHERE id_producto='.$_POST['id_producto'].'';
                        $result = mysqli_query($con,$sqlUpdate);
                    }else{
                        $sqlInsert = 'INSERT INTO registro_usuario (id_usuario,id_producto,nombre,codigo,precio,cantidad,fecha,color,paqueteria,rfc,orden,nombred,apellidod,direcciond,coloniad,ciudadd,estadod,codigopd,telefonod,estatus,fecha_procesado)
                        VALUES ('.$_POST['id_usuario'].','.$_POST['id_producto'].',"'.$_POST['nombre'].'","'.$_POST['codigo'].'","'.$_POST['precio'].'",'.$_POST['cantidad'].',"'.$fecha_delete.'","","'.$_POST['paqueteria'].'","'.$_POST['rfc'].'","'.$_POST['orden'].'","'.$_POST['nombred'].'","'.$_POST['apellidod'].'","'.$_POST['direcciond'].'","'.$_POST['coloniad'].'","'.$_POST['ciudadd'].'","'.$_POST['estadod'].'","'.$_POST['codigopd'].'","'.$_POST['telefonod'].'","","")';
                        $result = mysqli_query($con,$sqlInsert);
                    }


                    $sqlUpdateFolio = 'UPDATE folios SET total=total+'.$_POST['suma'].',cantidad=cantidad+'.$_POST['cantidad'].' WHERE orden="'.$_POST['orden'].'"';
                    $resultFolio = mysqli_query($con,$sqlUpdateFolio);

                    if($result  && $resultFolio){
                        $con->commit();
                        header("HTTP/1.1 200 OK");
                        $response['status'] = 200;
                        $response['mensaje'] = 'Registro creado correctamente';
                        echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                    }else{
                        $con->rollback();
                        header("HTTP/1.1 400");
                        $response['status'] = 400;
                        $response['mensaje'] = 'No se pudo hacer el registro';
                        echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                    }
                }
            }
        }else{
            echo 'otro metodo';
        }
    }else{
        header("HTTP/1.1 200");
        $response['status'] = 401;
        $response['mensaje'] = 'Token '.$validate;
        echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
    }
    //echo "Informacion".file_get_contents('php://input');

}else{
    echo "DB FOUND CONNECTED";
}
?>