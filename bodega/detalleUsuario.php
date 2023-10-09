<?php
// conexion con base de datos 
include '../conexion/conn.php';
date_default_timezone_set('America/Mexico_City');
//incluir middleware


// declarar array para respuestas 
$response = array();

// insertamos cabeceras para permisos 

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type,Accept, Authorization, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");
header("Content-Type: JSON");
header('Content-Type: application/json;charset=utf-8'); 

$fecha = date('Y-m-d');

$fecha_delete = date('Y-m-d H:i:s');

// validamos si hay conexion 
if($con){
        $methodApi = $_SERVER['REQUEST_METHOD'];


        if($methodApi == 'GET'){
             // metodo get 
             // para obtener un registro especifico

             $sql = 'SELECT  r.id_usuario,r.nombred,r.apellidod,r.fecha,f.nota,f.cajas,f.estatus,f.envio,f.fecha_almacen,f.paqueteria,n.descripcion FROM registro_usuario r INNER JOIN folios f ON r.orden = f.orden LEFT JOIN notificaciones n ON r.orden = n.orden WHERE r.orden="'.$_GET['orden'].'" LIMIT 1';
             $result = mysqli_query($con,$sql);
             $i=0;
             while($row = mysqli_fetch_assoc($result)){
                $response['id'] =  $row['id_usuario'];
                $response['cliente'] =  $row['nombred'].' '.$row['apellidod'];
                $response['paqueteria'] =  $row['paqueteria'];
                $response['orden'] = $_GET['orden'];
                $response['fecha'] = $row['fecha'];
                $response['nota'] = $row['nota'];
                $response['cajas'] = $row['cajas'];
                $response['estatus'] = $row['estatus'];
                $response['envio'] = $row['envio'];
                $response['descripcion'] = $row['descripcion'];
                $response['empaquetado'] = $row['fecha_almacen'] == '0000-00-00 00:00:00' ? false : true;
                $response['packeActive'] = $row['paqueteria'] === 'FEDEX' || $row['paqueteria'] === 'DHL' || $row['paqueteria'] === 'ESTAFETA' ? true : false;
                $i++;
             }
             echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                
        }

    //echo "Informacion".file_get_contents('php://input');

}else{
    echo "DB FOUND CONNECTED";
}
?>