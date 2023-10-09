<?php
// conexion con base de datos 
include '../conexion/conn.php';
date_default_timezone_set('America/Mexico_City');
$fecha_actual = date('Y-m-d H:i:s');
// declarar array para respuestas 

//incluir middleware
include '../middleware/validarToken.php';

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
        if($methodApi == 'GET'){
            $id = $_GET['id_usuario'];
            $sql = 'SELECT id,nombre,apellido,direccion,colonia,ciudad,estado,codigop,telefono,correo FROM usuario WHERE id=?';
            $stmt = $con->prepare($sql);
            $stmt->bind_param('i',$id);
            $result = $stmt->get_result(); 
            $response = $result->fetch_assoc(); 
            echo  json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
        }

        if($methodApi == 'POST'){

            $_POST = json_decode(file_get_contents('php://input'),true);
            $action = $_POST['action'];

            if($action == 'add'){
                try
                {
                    $con->autocommit(false);
    
                    $user = $_POST['user'];
    
                    $id_user = $user['id_usuario']; 
                    $id_prod = $_POST['id_producto']; 
                    $nombre  = $_POST['nombre_producto'];
                    $codigo  = $_POST['codigo_producto'];
                    $precio  = $_POST['precio_producto'];
                    $cant    = $_POST['cantidad_producto'];
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
                    $stmt->bind_param("iisssissssssssssssss",$id_user,$id_prod,$nombre,$codigo,$precio,$cant,$fecha_actual,$envio,$paque,$rfc,$orden,$nombred,$ape,$dir,$colonia,$ciudad,$estado,$codigop,$telf,$estatus);
                    $procesar = $stmt->execute();
    
    
                    $stmt = $con->prepare('SELECT total FROM folios WHERE orden=?'); 
                    $stmt->bind_param("s", $orden);
                    $stmt->execute();
                    $result = $stmt->get_result(); 
                    $data = $result->fetch_assoc(); 
    
                    $suma = $precio*$cant;
                    $total = intval($data['total']);
                    $new_total = $suma+$total;
    
                    $sqlUpdate = 'UPDATE folios SET total="'.$new_total.'" WHERE orden="'.$orden.'"';
                    $resultado = mysqli_query($con,$sqlUpdate);
    
    
                    if($procesar == 1 && $resultado){
                        $con->commit();
                        header("HTTP/1.1 200 OK");
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

            if($action == 'delete'){

                try
                {
                    $con->autocommit(false);
                    $id    = $_POST['id'];
                    $monto = $_POST['monto'];
                    $orden = $_POST['orden'];
    
                    $sqlDelete = 'DELETE FROM registro_usuario WHERE id='.$id.'';
                    $result = mysqli_query($con,$sqlDelete);
    
                    if($result){
                        $sqlUpdate = 'UPDATE folios SET total=total-'.$monto.' WHERE orden="'.$orden.'"';
                        $resultado = mysqli_query($con,$sqlUpdate);
    
                        if($resultado){
                            $con->commit();
                            header("HTTP/1.1 200 OK");
                            $response['mensaje'] = 'Se elimino el registro correctamente';
                            echo  json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                        }else{
                            $con->rollback();
                            header("HTTP/1.1 400");
                            $response['mensaje'] = 'Ocurrio un error intente de nuevo';
                            echo  json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                        }
                    }
                }catch(Exception $e){
                    $con->rollback();
                    header("HTTP/1.1 400");
                    $response['mensaje'] = $e->getMessage();
                    echo  json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                }
            }
        }


    }else{
        echo $token_access['validate'];
    }
}else{
    echo "DB FOUND CONNECTED";
}
?>