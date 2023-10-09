<?php
// conexion con base de datos 
include '../conexion/conn.php';
// declarar array para respuestas 
$response = array();

date_default_timezone_set('America/Mexico_City');
$fecha = date('Y-m-d H:i:s');


// insertamos cabeceras para permisos 

header('Access-Control-Allow-Origin:*');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");
header("Content-Type: JSON");
header('Content-Type: application/json;charset=utf-8'); 



// validamos si hay conexion 
if($con){

    $methodApi = $_SERVER['REQUEST_METHOD'];

        if($methodApi == 'GET'){
            
        }

        if($methodApi == 'POST'){

            $con->autocommit(false);

            try{
                $_POST = json_decode(file_get_contents('php://input'),true);
                $id        = $_POST['id'];
                $nombre    = $_POST['nombre'];
                $apellido  = $_POST['apellido'];
                $nombres   = $nombre.' '.$apellido;
                $cantidad  = $_POST['cantidad'];
                $orden     = $_POST['orden'];
                $datos     = $_POST['productos'];

                $sql = 'SELECT orden FROM usuario WHERE  id=?';
                $stmt = $con->prepare($sql);
                $stmt->bind_param('i',$id);
                $stmt->execute();
                $result = $stmt->get_result();
                $data = $result->fetch_assoc(); 
                $neworden = intval($data['orden'])+1;
                $pedido = $id.'-'.$neworden;
                $nota   = "Pedido cashback con la orden,".$orden."";


                $sqlInsert = 'INSERT INTO folios (id_usuario,nombres,orden,cantidad,total,estatus,nota,fecha) VALUES ('.$id.',"'.$nombres.'","'.$pedido.'","'.$cantidad.'","0","Sin Procesar","'.$nota.'","'.$fecha.'")';
                $result = mysqli_query($con,$sqlInsert);

                $sqlUpdated = 'UPDATE usuarios_cashback SET compra_user=1,cashback=0 WHERE id_usuario='.$id.'';
                $resultUpdated = mysqli_query($con,$sqlUpdated);

                $sqlUsuario = 'UPDATE usuario SET orden='.$neworden.' WHERE id='.$id.'';
                $resultUsuario = mysqli_query($con,$sqlUsuario);

                $band = false;
                if($result == 1 && $resultUpdated == 1 && $resultUsuario == 1){
                    foreach($datos as $item)
                    {
                        $sqlInsertCarrito = 'INSERT INTO registro_usuario(id_usuario,id_producto,nombre,codigo,precio,cantidad,fecha,orden,nombred,apellidod) VALUES('.$id.','.$item['id'].',"'.$item['nombre'].'","'.$item['codigo'].'","0",'.$item['cantidad'].',"'.$fecha.'","'.$pedido.'","'.$nombre.'","'.$apellido.'")';

                        $resultado = mysqli_query($con,$sqlInsertCarrito);

                        if($resultado == 1){
                            $band = true;
                        }else{
                            $band = false;
                            $con->rollback();
                            break;
                        } 
                    }
                }else{
                    $con->rollback();
                }

                if($band){
                    $con->commit();
                    header("HTTP/1.1 200 OK");
                    $response['status'] = 200;
                    $response['mensaje'] = 'Su pedido se creo exitosamente';
                    echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                }else{
                    $con->rollback();
                    header("HTTP/1.1 400");
                    $response['status'] = 400;
                    $response['mensaje'] = 'No se pudo crear el registro, intentalo de nuevo';
                    echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                }
            }catch(Exception $e){
                $con->rollback();
                header("HTTP/1.1 400");
                $response['status'] = 400;
                $response['mensaje'] = $e->getMessage();
                echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
            }
            
        }

}else{
    echo "DB FOUND CONNECTED";
}
?>