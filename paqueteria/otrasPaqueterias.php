<?php
// conexion con base de datos 
include '../conexion/conn.php';
require_once('../class/guias.class.php');
date_default_timezone_set('America/Mexico_City');
$fecha = date('Y-m-d H:i:s');

// declarar array para respuestas 
$response = array();

//in
$guia = new Guias();
// insertamos cabeceras para permisos 

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");
header("Content-Type: JSON");
header('Content-Type: application/json;charset=utf-8');

if($con){
    
    $methodApi = $_SERVER['REQUEST_METHOD'];

    if($methodApi == 'POST'){
        $_POST = json_decode(file_get_contents('php://input'),true);
        $orden      = $_POST['orden'];
        $num        = $_POST['num_rastreo'];
        $paqueteria = $_POST['paqueteria'];
        $sql = 'INSERT INTO guias (orden,fecha,fecha_cargado,paqueteria,numero_rastreo) VALUES (?,?,?,?,?)';
        $sqlUpdate = 'UPDATE folios SET estatus="Enviado a cliente" WHERE orden=?';
        $resultUpdate = $guia->updateFolioEstatus($con,$sqlUpdate,$orden);
        $resultado = $guia->insertOtraPaqueteria($con,$sql,$orden,$num,$paqueteria,$fecha);


        if($resultado == 1 && $resultUpdate == 1){
            header("HTTP/1.1 200 OK");
            $response['status'] = 200;
            $response['mensaje'] = "Se ha guardado el número de rastreo correctamente";
            echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
            $con->close();
        }else{
            header("HTTP/1.1 400 OK");
            $response['status'] = 400;
            $response['mensaje'] = 'Error ejecutando peticion';
            echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
        }
    }

    if($methodApi == 'GET'){
            $sql = 'SELECT f.id,f.fecha,f.nombres,f.orden,f.estatus,f.paqueteria,g.numero_rastreo FROM folios f LEFT JOIN guias g ON f.orden = g.orden WHERE f.paqueteria!="FEDEX" AND f.paqueteria!="DHL" AND f.paqueteria!="ESTAFETA" AND f.paqueteria!="ENVIA SU GUIA" AND f.estatus="Enviado a cliente" AND f.fecha_almacen>="2022-08-01"';
            $response = $guia->getPedidosEstatus($con,$sql);
            echo  json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }
}else{
    echo "DB FOUND CONNECTED";
}


?>