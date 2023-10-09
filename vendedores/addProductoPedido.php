<?php
// conexion con base de datos 
include '../conexion/conn.php';
//incluir middleware
include '../middleware/validarToken.php';

date_default_timezone_set('America/Mexico_City');
$fecha_actual = date('Y-m-d H:i:s');

// declarar array para respuestas 
$response = array();

// insertamos cabeceras para permisos 

header('Access-Control-Allow-Origin:*');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept,Authorization, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");
header("Content-Type: JSON");
header('Content-Type: application/json;charset=utf-8'); 



// validamos si hay conexion 
if($con){
    if($token_access['token']){
        $methodApi = $_SERVER['REQUEST_METHOD'];
        if($methodApi == 'POST'){
            $con->autocommit(false);
            $_POST = json_decode(file_get_contents('php://input'),true);
            
            $codigo      = $_POST['codigo'];
            $orden       = $_POST['orden'];
            $id_producto = $_POST['id_producto'];
            $cantidad    = $_POST['cantidad'];
            $nombre      = $_POST['nombre_producto'];
            $precio      = $_POST['precio'];

            $sqlSearch = 'SELECT id,id_producto,precio,cantidad FROM registro_usuario WHERE id_producto='.$id_producto.' AND orden="'.$orden.'"';
            $resultado = mysqli_query($con,$sqlSearch);
            $fill = mysqli_fetch_assoc($resultado);

            if($fill){
                $total = $cantidad + intval($fill['cantidad']);
                $sqlUpdated = 'UPDATE registro_usuario SET cantidad='.$total.' WHERE id='.$fill['id'].'';
                $result = mysqli_query($con,$sqlUpdated);
                if($result){
                    $sqlUpdatedAllCodigo = 'UPDATE registro_usuario SET precio="'.$precio.'" WHERE codigo="'.$codigo.'"';
                    $resultUpdated = mysqli_query($con,$sqlUpdatedAllCodigo);
                    if($resultUpdated){
                        $resultotal = mysqli_query($con,'SELECT SUM(cantidad*precio) as total,cantidad  FROM registro_usuario WHERE orden="'.$orden.'"');
                        $row = mysqli_fetch_assoc($resultotal);
                        if($row){
                            $new_cant = intval($row['cantidad'])+$cantidad;
                            $resultUpdatedTotal = mysqli_query($con,'UPDATE folios SET total="'.$row['total'].'",cantidad="'.$new_cant.'" WHERE orden="'.$orden.'"');
                            if($resultUpdatedTotal){
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
                        }else{
                            $con->rollback();
                            header("HTTP/1.1 400");
                            $response['status'] = 400;
                            $response['mensaje'] = 'No se pudo guardar los registros';
                            echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                        }
                    }else{
                        $con->rollback();
                        header("HTTP/1.1 400");
                        $response['status'] = 400;
                        $response['mensaje'] = 'No se pudo guardar los registros';
                        echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                    }
                }else{
                    $con->rollback();
                    header("HTTP/1.1 400");
                    $response['status'] = 400;
                    $response['mensaje'] = 'No se pudo guardar los registros';
                    echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                }
            }else{
                try
                {
                    $user = $_POST['user'];

                    $id_user = $user['id_usuario']; 
                    $envio   = $user['envio'];
                    $paque   = $user['paqueteria']; 
                    $rfc     = $user['rfc']; 
                    $orden   = $_POST['orden'];
                    $nombred = $user['nombred']; 
                    $ape     = $user['apellidod'];
                    $dir     = $user['direcciond'];
                    $colonia = $user['coloniad'];
                    $ciudad  = $user['ciudadd'];
                    $estado  = $user['estadod'];
                    $codigop = $user['codigopd'];
                    $telf    = $user['telefonod'];
                    $estatus = $user['estatus'];


                    $sql = 'INSERT INTO registro_usuario (id_usuario,id_producto,nombre,codigo,precio,cantidad,fecha,envio,paqueteria,rfc,orden,nombred,apellidod,direcciond,coloniad,ciudadd,estadod,codigopd,telefonod,estatus) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)';

                    $stmt = $con->prepare($sql);
                    $stmt->bind_param("iisssissssssssssssss",$id_user,$id_producto,$nombre,$codigo,$precio,$cantidad,$fecha_actual,$envio,$paque,$rfc,$orden,$nombred,$ape,$dir,$colonia,$ciudad,$estado,$codigop,$telf,$estatus);
                    $procesar = $stmt->execute();


                    $stmt = $con->prepare('SELECT total,cantidad FROM folios WHERE orden=?'); 
                    $stmt->bind_param("s", $orden);
                    $stmt->execute();
                    $result = $stmt->get_result(); 
                    $data = $result->fetch_assoc(); 

                    $suma = $precio*$cantidad;
                    $total = intval($data['total']);
                    $new_total = $suma+$total;
                    $new_cantidad = intval($data['cantidad'])+$cantidad;

                    $sqlUpdate = 'UPDATE folios SET total="'.$new_total.'",cantidad="'.$new_cantidad.'" WHERE orden="'.$orden.'"';
                    $resultado = mysqli_query($con,$sqlUpdate);


                    if($procesar == 1 && $resultado){
                        $con->commit();
                        header("HTTP/1.1 200 OK");
                        $response['status'] = 200;
                        $response['mensaje'] = 'Se agrego producto correctamente';
                        echo  json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                    }else{
                        $con->rollback();
                        header("HTTP/1.1 400");
                        $response['mensaje'] = 'Ocurrio un error intente de nuevo';
                        echo  json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                    }

                }catch(Exception $e){
                    $con->rollback();
                    header("HTTP/1.1 400");
                    $response['mensaje'] = $e->getMessage();
                    echo  json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                }
            }
        }
        if( $methodApi == 'PUT'){
            $_PUT = json_decode(file_get_contents('php://input'),true);
            $accion = $_PUT['accion'];

            if($accion == 'actualizar'){
                $con->autocommit(false);

                try{
                    $id       = $_GET['id'];
                    $cantidad = $_PUT['cantidad'];
                    $precio   = $_PUT['precio'];
                    $codigo   = $_PUT['codigo'];
                    $orden    = $_PUT['orden'];
                    $cambio   = $_PUT['cambio'];
        
                    $sqlUpdateItem = 'UPDATE registro_usuario SET cantidad='.$cantidad.',precio="'.$precio.'" WHERE id='.$id.'';
                    $resultUpdateItem = mysqli_query($con,$sqlUpdateItem);
                    
        
                    if($cambio && $resultUpdateItem){
                        $sqlUpdateCodigos = 'UPDATE registro_usuario SET precio="'.$precio.'" WHERE codigo="'.$codigo.'" AND orden="'.$orden.'"';
                        $resultUpdateCodigo = mysqli_query($con,$sqlUpdateCodigos);
                    }else{
                        $con->rollback();
                        header("HTTP/1.1 400");
                        $response['mensaje'] = 'Ocurrio un error intente de nuevo';
                        echo  json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                        $con->close();
                    }
        
                    $sqlTotal = 'SELECT SUM(cantidad*precio) as total,SUM(cantidad) as total_productos FROM registro_usuario WHERE orden="'.$orden.'"';
                    $resulTotal = mysqli_query($con,$sqlTotal);
                    $fill = mysqli_fetch_assoc($resulTotal);
        
                    if($fill){
                        $sqlUpdateFolio = 'UPDATE folios SET total="'.$fill['total'].'",cantidad="'.$fill['total_productos'].'" WHERE orden="'.$orden.'"';
                        $resultUpdateFolio = mysqli_query($con,$sqlUpdateFolio);
                    }else{
                        $con->rollback();
                        header("HTTP/1.1 400");
                        $response['mensaje'] = 'Ocurrio un error intente de nuevo';
                        echo  json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                        $con->close();
                    }
        
                    if($resultUpdateFolio){
                        $con->commit();
                        header("HTTP/1.1 200 OK");
                        $response['status'] = 200;
                        $response['mensaje'] = 'Se agrego producto correctamente';
                        echo  json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                        $con->close();
                    }
                }catch(Exception $e){
                    $con->rollback();
                    header("HTTP/1.1 400");
                    $response['mensaje'] = $e->getMessage();
                    echo  json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                    $con->close();
                } 
            }else{
                try{
                    $con->autocommit(false);
                    $id       = $_GET['id'];
                    $cantidad = $_PUT['cantidad'];
                    $precio   = $_PUT['precio'];
                    $codigo   = $_PUT['codigo'];
                    $orden    = $_PUT['orden'];
                    $cambio   = $_PUT['cambio'];
    
                    $sqlDeleteItem = 'DELETE FROM registro_usuario WHERE id='.$id.'';
                    $resultDelete = mysqli_query($con,$sqlDeleteItem);

    
                    if($cambio && $resultDelete){
                        $sqlUpdateCodigos = 'UPDATE registro_usuario SET precio="'.$precio.'" WHERE codigo="'.$codigo.'" AND orden="'.$orden.'"';
                        $resultUpdateCodigo = mysqli_query($con,$sqlUpdateCodigos);
                    }else{
                        $con->rollback();
                        header("HTTP/1.1 400");
                        $response['mensaje'] = 'Ocurrio un error intente de nuevo';
                        echo  json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                        $con->close();
                    }
    
                    $sqlTotal = 'SELECT SUM(cantidad*precio) as total,SUM(cantidad) as total_productos FROM registro_usuario WHERE orden="'.$orden.'"';
                    $resulTotal = mysqli_query($con,$sqlTotal);
                    $fill = mysqli_fetch_assoc($resulTotal);

                    if($fill){
                        $sqlUpdateFolio = 'UPDATE folios SET total="'.$fill['total'].'",cantidad="'.$fill['total_productos'].'" WHERE orden="'.$orden.'"';
                        $resultUpdateFolio = mysqli_query($con,$sqlUpdateFolio);
                        $con->commit();
                        header("HTTP/1.1 200 OK");
                        $response['status'] = 200;
                        $response['mensaje'] = 'Se agrego producto correctamente';
                        echo  json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                    }else{
                        $con->rollback();
                        header("HTTP/1.1 400");
                        $response['mensaje'] = 'Ocurrio un error intente de nuevo';
                        echo  json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                        $con->close();
                    }
                }catch(Exception $e){
                    $con->rollback();
                    header("HTTP/1.1 400");
                    $response['mensaje'] = $e->getMessage();
                    echo  json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                    $con->close();
                }
            }
        }
    }else{
        echo $token_access['validate'];
    }
}else{
    echo "DB FOUND CONNECTED";
}