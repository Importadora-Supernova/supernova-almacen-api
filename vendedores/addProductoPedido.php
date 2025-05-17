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

            try{

                $_POST = json_decode(file_get_contents('php://input'),true);
            
                $codigo      = $_POST['codigo'];
                $orden       = $_POST['orden'];
                $id_producto = $_POST['id_producto'];
                $cantidad    = $_POST['cantidad'];
                $nombre      = $_POST['nombre_producto'];
                $precio      = $_POST['precio'];

                $userJson = json_encode($_POST['user']);
    
                $sql = 'CALL RegisterProductPedido(?,?,?,?,?,?,?)';
                $stmt = $con->prepare($sql);
                $stmt->bind_param('iisssss',$id_producto,$cantidad,$precio,$codigo,$orden,$nombre,$userJson);
    
                if ($stmt->execute()) {
                    header("HTTP/1.1 200 OK");
                    $response['status'] = 200;
                    $response['mensaje'] = 'Se actualizo producto correctamente';
                    echo  json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                }else{
                    header("HTTP/1.1 400");
                    $response['mensaje'] = 'Ocurrio un error intente de nuevo';
                    echo  json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                }
            }catch(Exception $e){
                header("HTTP/1.1 400");
                $response['mensaje'] = $e->getMessage();
                echo  json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
            }
        }//end if post


        if( $methodApi == 'PUT'){
            $_PUT = json_decode(file_get_contents('php://input'),true);
            $accion = $_PUT['accion'];

            if($accion == 'actualizar'){
                try{
                    $id       = $_GET['id'];
                    $cantidad = $_PUT['cantidad'];
                    $precio   = $_PUT['precio'];
                    $codigo   = $_PUT['codigo'];
                    $orden    = $_PUT['orden'];
                    $cambio   = $_PUT['cambio'] ? 1 : 0;

                    $sql = 'CALL ActualizarCantidadProducto(?,?,?,?,?,?)';
                    $stmt = $con->prepare($sql);
                    $stmt->bind_param('iisssi',$id,$cantidad,$precio,$codigo,$orden,$cambio);

                    if ($stmt->execute()) {
                        header("HTTP/1.1 200 OK");
                        $response['status'] = 200;
                        $response['mensaje'] = 'Se actualizo producto correctamente';
                        echo  json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                    }else{
                        header("HTTP/1.1 400");
                        $response['mensaje'] = 'Ocurrio un error intente de nuevo';
                        echo  json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                    }

                }catch(Exception $e){
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