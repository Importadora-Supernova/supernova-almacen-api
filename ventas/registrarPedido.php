<?php
// conexion con base de datos 
include '../conexion/conn.php';
include '../middleware/midleware.php';
date_default_timezone_set('America/Mexico_City');
//incluir middleware


// declarar array para respuestas 
$response = array();
$fecha = date('Y-m-d H:i:s');

// insertamos cabeceras para permisos 

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type,Authorization, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");
header("Content-Type: JSON");
header('Content-Type: application/json;charset=utf-8'); 


// validamos si hay conexion 
if($con){
    if($validate === 'validado'){
        $methodApi = $_SERVER['REQUEST_METHOD'];

        if($methodApi == 'POST'){
             // METODO POST
            $_POST = json_decode(file_get_contents('php://input'),true);

            $con->autocommit(false);

            $sqlInsertFolio = 'INSERT INTO folios (id_usuario,nombres,orden,paqueteria,cantidad,total,estatus,fecha) VALUES 
            ('.$_POST['id'].',"'.$_POST['fullName'].'","'.$_POST['newOrden'].'","'.$_POST['paqueteria'].'","'.$_POST['cantidad'].'","'.$_POST['total'].'","Sin procesar","'.$fecha.'")';
            $resInsertFolio = mysqli_query($con,$sqlInsertFolio);

            $sqlDelete = 'DELETE FROM carrito WHERE id_usuario='.$_POST['id'].'';
            $resultDelete = mysqli_query($con,$sqlDelete);            
            if($resInsertFolio && $resultDelete){
                $data = [];
                $data = $_POST['pedido'];
                $i=0;
                $band = true;
                while($i<count($data)){
                        $sql = 'INSERT INTO registro_usuario(id_usuario,id_producto,nombre,codigo,precio,cantidad,fecha,paqueteria,rfc,orden,nombred,apellidod,direcciond,coloniad,ciudadd,estadod,codigopd,telefonod,estatus) VALUES ('.$_POST['id'].','.$data[$i]['id'].',"'.$data[$i]['nombre'].'","'.$data[$i]['codigo'].'","'.$data[$i]['precio'].'","'.$data[$i]['cantidad'].'","'.$fecha.'","'.$_POST['paqueteria'].'","'.$_POST['rfc'].'","'.$_POST['newOrden'].'","'.$_POST['nombre'].'","'.$_POST['apellido'].'","'.$_POST['direccion'].'","'.$_POST['colonia'].'","'.$_POST['ciudad'].'","'.$_POST['estado'].'","'.$_POST['codigo'].'","'.$_POST['telefono'].'","Sin procesar")';

                        $res = mysqli_query($con,$sql);
                        if($res){
                            $i++;
                        }else{  
                            $band = false;
                            $con->rollback();
                            break;
                        } 
                }

                if($band){
                    $con->commit();
                    header("HTTP/1.1 200");
                    $response['mensaje'] = 'Pedido registrado exitosamente';
                    $response['orden'] = $_POST['newOrden'];
                    echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                    
                }else{
                    $con->rollback();
                    header("HTTP/1.1 400");
                    $response['mensaje'] = 'No se podo completar la accion';
                    echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                }
            }else{
                $con->rollback();
                header("HTTP/1.1 400");
                $response['mensaje'] = 'No se podo completar la accion';
                echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
            }
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