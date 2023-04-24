<?php
// conexion con base de datos 
include '../conexion/conn.php';
include '../middleware/midleware.php';
date_default_timezone_set('America/Mexico_City');
//incluir middleware


// declarar array para respuestas 
$response = array();

// insertamos cabeceras para permisos 

header('Access-Control-Allow-Origin:*');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept,Authorization, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");
header("Content-Type: JSON");
header('Content-Type: application/json;charset=utf-8'); 

$fecha = date('Y-m-d');

// validamos si hay conexion 
if($con){
        $methodApi = $_SERVER['REQUEST_METHOD'];
        if($validate === 'validado'){
            if($methodApi  == 'GET'){
                if(isset($_GET['orden'])){
                    $sql = 'SELECT f.id_usuario,f.orden,f.nombres,f.paqueteria,f.fecha,f.fecha_almacen,e.cajas,e.bolsas FROM folios f INNER JOIN empaquetado e ON f.orden=e.orden WHERE (f.estatus="Listo para salida" OR f.estatus="Esperando por Guia" OR f.estatus="Medidas Enviadas" OR f.estatus="Guia enviada") AND f.orden="'.$_GET['orden'].'"';
                    $result = mysqli_query($con,$sql);
                    $i=0;
                    while($row = mysqli_fetch_assoc($result)){
                        $response[$i]['nombres'] =  $row['nombres'];
                        $response[$i]['orden'] =  $row['orden'];
                        $response[$i]['cajas'] =  $row['cajas'];
                        $response[$i]['bolsas'] =  $row['bolsas'];
                        $response[$i]['paqueteria'] =  $row['paqueteria'];
                        $response[$i]['fecha'] =  $row['fecha'];
                        $response[$i]['fecha_almacen'] =  $row['fecha_almacen'];
                        $i++;
                    }
                    header("HTTP/1.1 200 OK");
                    echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);  
                }else{
                    $sql = 'SELECT f.id_usuario,f.orden,f.nombres,f.paqueteria,f.fecha,f.fecha_almacen,f.fecha_entrega,e.cajas,e.bolsas FROM folios f INNER JOIN empaquetado e ON f.orden=e.orden WHERE f.estatus="Listo para salida" OR f.estatus="Esperando por Guia" OR f.estatus="Medidas Enviadas" OR f.estatus="Guia enviada"';
                    $result = mysqli_query($con,$sql);
                    $i=0;
                    while($row = mysqli_fetch_assoc($result)){
                        $response[$i]['nombres'] =  $row['nombres'];
                        $response[$i]['orden'] =  $row['orden'];
                        $response[$i]['cajas'] =  $row['cajas'];
                        $response[$i]['bolsas'] =  $row['bolsas'];
                        $response[$i]['paqueteria'] =  $row['paqueteria'];
                        $response[$i]['fecha'] =  $row['fecha'];
                        $response[$i]['fecha_almacen'] =  $row['fecha_almacen'];
                        $response[$i]['fecha_entrega'] =  $row['fecha_entrega'];
                        $i++;
                    }
                    header("HTTP/1.1 200 OK");
                    echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);  
                }
                
            }  
        }else{
            header("HTTP/1.1 201");
            $response['status'] = 401;
            $response['mensaje'] = 'Token '.$validate;
            echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
            
        }
    //echo "Informacion".file_get_contents('php://input');

}else{
    echo "DB FOUND CONNECTED";
}
?>