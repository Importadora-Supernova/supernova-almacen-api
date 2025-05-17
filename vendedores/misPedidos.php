<?php
// insertamos cabeceras para permisos 
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept,Authorization, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");
header("Content-Type: JSON");
header('Content-Type: application/json;charset=utf-8'); 
// conexion con base de datos 
include '../conexion/conn.php';
//incluir middleware
include '../middleware/validarToken.php';

date_default_timezone_set('America/Mexico_City');
$fecha = date('Y-m-d H:i:s');

// declarar array para respuestas 
$response = array();



// validamos si hay conexion 
if($con){
        $methodApi = $_SERVER['REQUEST_METHOD'];

        if($methodApi == 'GET'){
            if(isset($_GET['vendedor'])){

                $fecha = $_GET['fecha'] == "" ? date('Y-m-d') : $_GET['fecha'];

                $sql = 'SELECT *FROM view_pedidos_clientes_vendedor WHERE vendedora=? AND LOWER(estatus) = LOWER("Sin Procesar") AND DATE(fecha_creado)=?';
                $stmt = $con->prepare($sql);
                $stmt->bind_param('ss',$_GET['vendedor'],$fecha);
                $stmt->execute();
                $result   = $stmt->get_result();
                $response = $result->fetch_all(MYSQLI_ASSOC);
                echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
            }
        }

        if($methodApi == 'POST'){
            $_POST = json_decode(file_get_contents('php://input'),true);

            $fecha_procesado = $_POST['fecha'] . '%';
            $vendedor = $_POST['vendedor'];
            $sql = 'SELECT * FROM `view_pedidos_vendedor` WHERE vendedora=? AND DATE(fecha_procesado) = ?';
            $stmt = $con->prepare($sql);
            $stmt->bind_param('ss', $vendedor, $fecha_procesado);
            $stmt->execute();
            $result   = $stmt->get_result();
            $response = $result->fetch_all(MYSQLI_ASSOC);
            echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
        }

        if($methodApi == 'PUT'){

            $con->autocommit(false);
            try{
                $_PUT = json_decode(file_get_contents('php://input'),true);

                $vendedora = $_PUT['vendedora'];
                $orden = $_PUT['orden'];
                // $num_cajas = $_PUT['num_cajas'];
                // $monto_envio = $_PUT['monto_envio'];
                // $cuenta = $_PUT['cuenta'];
                // $paqueteria = $_PUT['paqueteria'];
                // $factura = $_PUT['factura'];
                // $pagado = $_PUT['pagado'];
                // $factura_enviada = $_PUT['factura_enviada'];
                // $fecha_cotizacion = $_PUT['fecha_cotizacion'];
                $id = $_GET['id'];
                
                $marca = '1';
    
                $sql = 'UPDATE folios SET marcado=?,vendedora=? WHERE id=?';
                $stmt = $con->prepare($sql);
                $stmt->bind_param('ssi',$marca,$vendedora,$id);
                $result = $stmt->execute();
    
                // $sql_insert = 'INSERT INTO clientes_vendedores (orden,folioId,vendedora,num_cajas,monto_envio,cuenta,paqueteria,factura,pagado,factura_enviada,fecha_cotizacion,fecha_creado) VALUES(?,?,?,?,?,?,?,?,?,?,?,?)';
                // $stmt_insert = $con->prepare($sql);
                // $stmt->bind_param('sisidssiiiss',$orden,$id,$vendedora,$num_cajas,$monto_envio,$cuenta,$paqueteria,$factura,$pagado,$factura_enviada,$fecha_cotizacion,$fecha);
                // $result_insert = $stmt_insert->execute();
                $idFolio = intval($id);
                $sql_insert = 'INSERT INTO clientes_vendedores (orden,folioId,vendedora,fecha_creado) VALUES(?,?,?,?)';
                $stmt_insert = $con->prepare($sql_insert);
                $stmt_insert->bind_param('siss',$orden,$idFolio,$vendedora,$fecha);
                $result_insert = $stmt_insert->execute();
    
                if($result && $result_insert){
                    $con->commit();
                    header("HTTP/1.1 200 OK");
                    $response['mensaje'] = 'Cliente contactado';
                    echo  json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                }else{
                    $con->rollback();
                    header("HTTP/1.1 400");
                    $response['mensaje'] = 'Ocurrio un error,intente nuevamente';
                    echo  json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                }
            }catch(Exception $e){
                $con->rollback();
                header("HTTP/1.1 400");
                $response['mensaje'] = $e->getMessage();
                echo  json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
            }


        }
}else{
    echo "DB FOUND CONNECTED";
}