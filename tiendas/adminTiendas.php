<?php

// conexion con base de datos 
include '../conexion/conn.php';

// declarar array para respuestas 
$response = array();
$total    = array();
$tienda   = array();


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

    
    if($methodApi == 'GET'){
        
        $sql = 'SELECT *FROM admin_tiendas';
        $result = mysqli_query($con,$sql);
        $i=0;
        while ($row = mysqli_fetch_assoc($result)) {
            $total = 0;
            $tienda[$i]['id'] = $row['id'];
            $tienda[$i]['nombre_tienda'] = $row['nombre_tienda'];
            $tienda[$i]['identificador'] = $row['identificador'];
            $sqlP = 'SELECT SUM(cantidad) as total FROM pedidos_tienda WHERE id_tienda='.$row['id'].'';
            $resultado = mysqli_query($con,$sqlP);
            while ($fill = mysqli_fetch_assoc($resultado)) {
                $tienda[$i]['total'] = $fill['total'];
            }
            $i++;
        }
        echo json_encode($tienda,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
    }
}else{
echo "DB FOUND CONNECTED";
}

?>