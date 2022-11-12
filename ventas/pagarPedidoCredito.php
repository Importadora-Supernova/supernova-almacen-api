<?php
// conexion con base de datos 
include '../conexion/conn.php';
date_default_timezone_set('America/Mexico_City');
//incluir middleware
include '../middleware/midleware.php';


// declarar array para respuestas 
$response = array();

// insertamos cabeceras para permisos 

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");
header("Content-Type: JSON");
header('Content-Type: application/json;charset=utf-8'); 

$fecha = date('Y-m-d H:i:s');

// validamos si hay conexion 
if($con){
    if($validate === 'validado'){
        $methodApi = $_SERVER['REQUEST_METHOD'];

        if($methodApi == 'POST'){
            $_POST = json_decode(file_get_contents('php://input'),true);
            $orden = $_POST['orden'];
            //actualizar registro usuario 
            $sqlRegistro_usuario = 'UPDATE registro_usuario SET estatus="Pagado", fecha_procesado="'.$fecha.'" WHERE orden="'.$orden.'"';
            $resulRegistro_usuario = mysqli_query($con,$sqlRegistro_usuario);

            //actualizar folios
            $sqlFolios = 'UPDATE folios SET pendiente="Pagado",venta_paqueteria="'.$_POST['paqueteria'].'",iva="'.$_POST['iva'].'",saldo_pendiente="'.$_POST['saldo'].'",efectivo="'.$_POST['efectivo'].'" WHERE orden="'.$orden.'"';
            $resultFolios = mysqli_query($con,$sqlFolios);

            //insertar nuevo registro en pagos
            $sqlPagos = 'INSERT INTO pagos (orden,fecha,monto,banco,metodos_pago) VALUES ("'.$orden.'","'.$fecha.'",'.$_POST['monto'].',"'.$_POST['banco'].'","'.$_POST['metodos'].'")';
            $resultPagos = mysqli_query($con,$sqlPagos);  

            if($resulRegistro_usuario && $resultFolios && $resultPagos){
                $con->commit();
                header("HTTP/1.1 200 OK");
                $response['status'] = 200;
                $response['mensaje'] = 'El proceso de pago se ejecuto exitosamente';
                echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
            }else{
                $con->rollback();
                header("HTTP/1.1 400");
                $response['status'] = 400;
                $response['mensaje'] = 'Ocurrio un error en el proceso intente nuevamente';
                echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
            }
        }
    }else{
        header("HTTP/1.1 201");
        $response['status'] = 401;
        $response['mensaje'] = 'Token '.$validate;
        echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
    }
}else{
    echo "DB FOUND CONNECTED";
}