<?php
// conexion con base de datos 
include '../conexion/conn.php';
require('../class/ventasClientes.class.php');
//incluir middleware
include '../middleware/validarToken.php';

date_default_timezone_set('America/Mexico_City');
$fecha = date('Y-m-d H:i:s');

// declarar array para respuestas 
$response = array();
$cliente = new pedidoCliente();

// insertamos cabeceras para permisos 
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept,Authorization, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");
header("Content-Type: JSON");
header('Content-Type: application/json;charset=utf-8'); 



// validamos si hay conexion 
if($con){
    if($token_access['token']){
        $methodApi = $_SERVER['REQUEST_METHOD'];

        if($methodApi == 'GET'){
    
            if(isset($_GET['orden'])){
                $orden = $_GET['orden'];
                $sql = 'SELECT r.id,r.id_usuario,r.id_producto,r.nombre,r.codigo,r.cantidad,r.precio,r.nombred,r.apellidod,r.envio,r.paqueteria,r.rfc,r.nombred,r.direcciond,r.coloniad,r.ciudadd,r.estadod,r.codigopd,r.telefonod,r.estatus,p.almacen FROM registro_usuario r INNER JOIN productos p ON r.id_producto = p.id WHERE r.orden=?';
                $stmt = $con->prepare($sql);
                $stmt->bind_param("s",$orden);
                $stmt->execute();
                $result = $stmt->get_result();
                $response= $result->fetch_all(MYSQLI_ASSOC);
    
                echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
            }else{
                $fecha = $_GET['fecha'].'%';
                $sql = 'SELECT f.id,f.id_usuario,f.nombres,f.orden,f.envio,f.paqueteria,f.cantidad,f.total,f.estatus,f.nota,f.efectivo,f.monto,f.cajas,f.fecha,f.marcado,f.vendedora,u.nombre,u.apellido,u.direccion,u.colonia,u.ciudad,u.estado,u.codigop,u.correo,u.telefono,u.saldo_favor,d.factura FROM folios f LEFT JOIN usuario u ON f.id_usuario = u.id LEFT JOIN doc_pagos_pedido d ON f.orden = d.orden WHERE f.fecha LIKE ?  AND f.estatus="Sin Procesar" ORDER BY f.id DESC';
                $response = $cliente->getPedidosCliente($con,$sql,$fecha);
                echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
            }
            
    
        }
    
        if($methodApi == 'POST'){
            $_POST = json_decode(file_get_contents('php://input'),true);
            $orden = $_POST['orden'];
            $action  = $_POST['action'];
            try{
                $con->autocommit(false);
                $sql = 'SELECT *FROM registro_usuario WHERE orden=?';
        
                $stmt = $con->prepare($sql);
                $stmt->bind_param("s",$orden);
                $stmt->execute();
        
                $result = $stmt->get_result();
                $data = $result->fetch_all(MYSQLI_ASSOC);
        
                $band = false;
                $totalCosto = 0;
                foreach($data as $item){
    
                    $sqlProduc = 'SELECT codigo,descuento_especial,descuento_general,precioc FROM productos WHERE id='.$item['id_producto'].'';
                    $result = mysqli_query($con,$sqlProduc);
                    $i=0;
                    while($row = mysqli_fetch_assoc($result)){
                        if($action == 'especial'){
                            if($row['descuento_especial'] == 0){
                                    //...
                                $totalCosto += $row['precioc'];
                                $sqlUpdate = 'UPDATE registro_usuario SET precio="'.$row['precioc'].'" WHERE id_producto='.$item['id_producto'].' AND orden="'.$orden.'"';
                                $resultado = mysqli_query($con,$sqlUpdate);
                                if($resultado){
                                    $band = true;
                                }else{
                                    $band = false;
                                    break;
                                } 
                            }else{
                                $totalCosto += $row['descuento_especial'];
                                $sqlUpdate = 'UPDATE registro_usuario SET precio="'.$row['descuento_especial'].'" WHERE id_producto='.$item['id_producto'].' AND orden="'.$orden.'"';
                                $resultado = mysqli_query($con,$sqlUpdate);
                                if($resultado){
                                    $band = true;
                                }else{
                                    $band = false;
                                    break;
                                } 
                            } 
                        }else if($action == 'general'){
                            if($row['descuento_general'] == 0){
                                //...
                                $totalCosto += $row['precioc'];
                                $sqlUpdate = 'UPDATE registro_usuario SET precio="'.$row['precioc'].'" WHERE id_producto='.$item['id_producto'].' AND orden="'.$orden.'"';
                                $resultado = mysqli_query($con,$sqlUpdate);
                                if($resultado){
                                    $band = true;
                                }else{
                                    $band = false;
                                    break;
                                } 
                            }else{
                                $totalCosto += $row['descuento_general'];
                                $sqlUpdate = 'UPDATE registro_usuario SET precio="'.$row['descuento_general'].'" WHERE id_producto='.$item['id_producto'].' AND orden="'.$orden.'"';
                                $resultado = mysqli_query($con,$sqlUpdate);
                                if($resultado){
                                    $band = true;
                                }else{
                                    $band = false;
                                    break;
                                } 
                            } 
                        }else{
                            $totalCosto += $row['precioc'];
                            $sqlUpdate = 'UPDATE registro_usuario SET precio="'.$row['precioc'].'" WHERE id_producto='.$item['id_producto'].' AND orden="'.$orden.'"';
                            $resultado = mysqli_query($con,$sqlUpdate);
                            if($resultado){
                                $band = true;
                            }else{
                                $band = false;
                                break;
                            } 
                        }
                        $i++;          
                    }
                }
    
                $sqlUpdateFolio = 'UPDATE folios SET total="'.$totalCosto.'" WHERE orden="'.$orden.'"';
                $resultado = mysqli_query($con,$sqlUpdateFolio);
                if($band && $resultado==1){
                    $con->commit();
                    header("HTTP/1.1 200 OK");
                    $response['status'] = 200;
                    $response['mensaje'] = 'Registros actualizados correctamente';
                    echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                }else{
                    $con->rollback();
                    header("HTTP/1.1 400");
                    $response['status'] = 400;
                    $response['mensaje'] = 'No se pudo guardar los registros';
                    echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                }
            }catch(Exception $e){
                header("HTTP/1.1 400");
                $response['status'] = 400;
                $response['mensaje'] = $e->getMessage();
                echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
            }
        }

        if($methodApi == 'PUT'){

            try
            {
                $con->autocommit(false);
                $_PUT = json_decode(file_get_contents('php://input'),true);
    
                $orden  = $_PUT['orden'];
                $id     = $_GET['id'];
                $result = null;
    
    
                if($_PUT['accion'] == 'monto'){
                    $precio = $_PUT['precio'];
                    $codigo = $_PUT['codigo'];
                    $sql = 'UPDATE registro_usuario SET precio="'.$precio.'" WHERE codigo="'.$codigo.'" AND orden="'.$orden.'"';
                    $result = mysqli_query($con,$sql);
                }else{
                    $cantidad = $_PUT['cantidad'];
                    $sql = 'UPDATE registro_usuario SET cantidad='.$cantidad.' WHERE id='.$id.'';
                    $result = mysqli_query($con,$sql);
                }
    
                if($result){
    
                    $sql = 'SELECT SUM(precio*cantidad) as total FROM registro_usuario WHERE orden=?';
                    $stmt = $con->prepare($sql);
                    $stmt->bind_param("s",$orden);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $data = $result->fetch_assoc(); 
    
                    $sqlUpdateFolio = 'UPDATE folios SET total="'.$data['total'].'" WHERE orden="'.$orden.'"';
                    $resulta = mysqli_query($con,$sqlUpdateFolio);
    
                    if($resulta == 1){
                        $con->commit();
                        header("HTTP/1.1 200 OK");
                        $response['status'] = 200;
                        $response['mensaje'] = 'Registros actualizados correctamente';
                        echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                    }else{
                        $con->rollback();
                        header("HTTP/1.1 400");
                        $response['status'] = 400;
                        $response['mensaje'] = 'No se pudo guardar los registros';
                        echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                    }
                }
            }catch(Exception $e){
                header("HTTP/1.1 400");
                $response['status'] = 400;
                $response['mensaje'] = $e->getMessage();
                echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
            }
        }
    }else{
        echo $token_access['validate'];
    }
}else{
    echo "DB FOUND CONNECTED";
}