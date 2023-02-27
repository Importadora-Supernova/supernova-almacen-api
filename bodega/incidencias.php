<?php
// conexion con base de datos 
include '../conexion/conn.php';
date_default_timezone_set('America/Mexico_City');
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
if($con){

    $methodApi = $_SERVER['REQUEST_METHOD'];
    
    if($methodApi == 'GET'){

         // es para obtener todos los registros  por codigo
        $sql = 'SELECT f.nombres,f.estatus,f.orden,n.motivo,n.descripcion,n.accion,n.observacion,n.notificacion,n.fecha_pausado,n.fecha_resuelto FROM folios f INNER JOIN notificaciones n ON f.orden = n.orden WHERE f.estatus = "Pausado"';
        $result = mysqli_query($con,$sql);
        $i=0;
        while($row = mysqli_fetch_assoc($result)){
            $response[$i]['orden'] = $row['orden'];
            $response[$i]['nombres'] = $row['nombres'];
            $response[$i]['estatus'] = $row['estatus'];
            $response[$i]['motivo'] = $row['motivo']; 
            $response[$i]['descripcion'] = $row['descripcion'];
            $response[$i]['accion'] = $row['accion'];
            $response[$i]['observacion'] = $row['observacion'];
            $response[$i]['notificacion'] = $row['notificacion'];
            $response[$i]['fecha_pausado'] = $row['fecha_pausado'];
            $response[$i]['fecha_resuelto'] = $row['fecha_resuelto'];
            $i++;
        }
        echo  json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);

    }
}else{
    echo "DB FOUND CONNECTED";
}


?>