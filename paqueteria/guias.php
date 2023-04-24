<?php
// conexion con base de datos 
include 'conexion/conn.php';
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
//$fecha = date('Y-m-d H:i:s');
if ($con) {
    $methodApi = $_SERVER['REQUEST_METHOD'];
    if ($methodApi == 'GET') {
        if(isset($_GET['listas'])){
            $sqlSelect = 'SELECT  id,nombres,orden,paqueteria,fecha_guias FROM folios   WHERE estatus LIKE "Guia enviada"';
            $result = mysqli_query($con, $sqlSelect);
            $i = 0;
            while ($row = mysqli_fetch_assoc($result)) {
                $response[$i]['id'] = $row['id'];
                $response[$i]['orden'] = $row['orden'];
                $response[$i]['nombres'] = $row['nombres'];
                $response[$i]['paqueteria'] = $row['paqueteria'];
                $response[$i]['fecha_guias'] = $row['fecha_guias'];
                $i++;
            }
            echo  json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        }else if(isset($_GET['bodega'])){
            $sql = 'SELECT f.id,f.nombres,f.orden,f.paqueteria,f.fecha_almacen,e.cajas FROM folios f INNER JOIN empaquetado e ON f.orden = e.orden WHERE f.estatus="Listo para salida" && f.envio="Si"';
            $resultado = mysqli_query($con,$sql);
            $i =0;
            while($row  = mysqli_fetch_assoc($resultado)){
                $response[$i]['id'] = $row['id'];
                $response[$i]['orden'] = $row['orden'];
                $response[$i]['nombres'] = $row['nombres'];
                $response[$i]['paqueteria'] = $row['paqueteria'];
                $response[$i]['cajas'] = $row['cajas'];
                $response[$i]['fecha_almacen'] = $row['fecha_almacen'];
                $i++;
            }
            echo  json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        }else if(isset($_GET['entregas'])){
            $sql = 'SELECT f.id,f.nombres,f.orden,f.fecha_almacen,e.cajas,e.bolsas FROM folios f INNER JOIN empaquetado e ON f.orden = e.orden WHERE f.estatus="Listo para salida" && f.envio="No"';
            $result = mysqli_query($con,$sql);
            $i = 0;
            while($row = mysqli_fetch_assoc($result)){
                $response[$i]['id'] = $row['id'];
                $response[$i]['orden'] = $row['orden'];
                $response[$i]['nombres'] = $row['nombres'];
                $response[$i]['bolsas'] = $row['bolsas'];
                $response[$i]['cajas'] = $row['cajas'];
                $response[$i]['fecha_almacen'] = $row['fecha_almacen'];
                $i++;
            }
            echo  json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        }
        
    }


} else {
    echo "DB FOUND CONNECTED";
}
