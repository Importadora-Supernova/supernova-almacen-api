<?php

// conexion con base de datos 
include '../conexion/conn.php';
require_once('../class/pedidosTienda.class.php');

// declarar array para respuestas 
$response = array();

$pedido = new pedidosTienda();

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

    if($methodApi == 'POST'){
        $_POST = json_decode(file_get_contents('php://input'),true);

        if($_POST['accion'] == 'crear')
        {
            $estatus = 'Registrado por tienda';
            $sql = 'INSERT INTO tienda_envios_tienda (tienda,nombre_cliente,estado_dir,ciudad_dir,direccion_dir,colonia_dir,codigo_postal,rfc,numero_cajas,estatus_envio,paqueteria,fecha_registro) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)';

            $resultado = $pedido->insertPedido($con,$sql,$_POST,$estatus,$fecha);

            if($resultado == 1){
                header("HTTP/1.1 200 OK");
                $response['status'] = 200;
                $response['mensaje'] = 'Pedido Registrado con exito';
                echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
            }else{
                header("HTTP/1.1 400");
                $response['status'] = 400;
                $response['mensaje'] = 'No se pudo crear el registro, intentalo de nuevo';
                echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
            } 
        }else{
            $tienda = $_POST['tienda'];
            $sql = 'SELECT *FROM view_pedidos_tiendas WHERE tienda=? ORDER BY fecha_registro ASC';
            echo json_encode($pedido->getPedidoTiendaId($con,$sql,$tienda),JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
        }
        
    }

    if($methodApi == 'GET'){
            $sql = 'SELECT *FROM view_pedidos_tiendas ORDER BY fecha_registro DESC';
            $response = $pedido->getPedidosTienda($con,$sql,);
            echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
    } 
    
    if($methodApi == 'PUT'){
        $_PUT = json_decode(file_get_contents('php://input'),true);

        try
        {
            $con->autocommit(false);

            $cajas = $_PUT['cajas'];
            $id    = $_GET['id'];
            $band  = false;
            $status = 'Medidas cargadas';

            $sqlUpdate = 'UPDATE tienda_envios_tienda SET estatus_envio=?,fecha_medidas=? WHERE id_envio_tienda=?'; 

            $sqlInsert = 'INSERT INTO tienda_cajas_envio (id_envio,peso,alto,ancho,largo,fecha_register) VALUES (?,?,?,?,?,?)';

            $resultUpdate = $pedido->updatePedidoMedidas($con,$sqlUpdate,$status,$id,$fecha); 

            foreach($cajas as $row){
                $result = $pedido->insertMedidasPedidoTienda($con,$sqlInsert,$row,$id,$fecha);
                if($result == 1){
                    $band = true;
                }else{
                    $band = false;
                    break;
                }
            }

            if($resultUpdate == 1 && $band){
                $con->commit();
                header("HTTP/1.1 200 OK");
                $response['status'] = 200;
                $response['mensaje'] = 'Se registraron medidas correctamente';
                echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
            }else{
                $con->rollback();
                header("HTTP/1.1 400");
                $response['status'] = 400;
                $response['mensaje'] = 'No se pudo crear el registro, intentalo de nuevo';
                echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
            }

        }catch(Exception $e)
        {
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