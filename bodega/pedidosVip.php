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

        if(isset($_GET['productOrden'])){
            $sql = 'SELECT r.id,r.orden,r.id_producto,r.nombre,r.codigo,r.cantidad,r.precio,p.preciou,p.preciom,p.precioc,p.preciov,p.topem,p.topec,p.topev,p.descuento,p.descuento_precio_docena,p.almacen,a.cantidad as cantidad_almacen FROM registro_usuario r INNER JOIN productos p ON r.id_producto = p.id INNER JOIN almacen_producto a ON p.id = a.id_producto WHERE r.orden = "'.$_GET['productOrden'].'" AND a.id_almacen = "15"';
            $result = mysqli_query($con,$sql);
            $i=0;
            while($row = mysqli_fetch_assoc($result)){
                $response[$i]['id'] = $row['id'];
                $response[$i]['orden'] = $row['orden'];
                $response[$i]['id_producto'] = $row['id_producto'];
                $response[$i]['nombre'] = $row['nombre'];
                $response[$i]['codigo'] = $row['codigo']; 
                $response[$i]['cantidad'] = $row['cantidad']; 
                $response[$i]['precio'] = $row['precio']; 
                $response[$i]['preciou'] = $row['preciou']; 
                $response[$i]['preciom'] = $row['preciom']; 
                $response[$i]['precioc'] = $row['precioc']; 
                $response[$i]['preciov'] = $row['preciov']; 
                $response[$i]['topem'] = $row['topem']; 
                $response[$i]['topec'] = $row['topec'];
                $response[$i]['topev'] = $row['topev'];  
                $response[$i]['almacen'] = $row['almacen'];  
                $response[$i]['cantidad_almacen'] = $row['cantidad_almacen'];  
                $i++;
            }
            echo  json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT); 

        }else{
           // es para obtener todos los registros  por codigo
            $sql = 'SELECT *FROM folios WHERE (id_usuario = "7659" or id_usuario = "7610" or id_usuario = "7586") and estatus = "En revision"';
            $result = mysqli_query($con,$sql);
            $i=0;
            while($row = mysqli_fetch_assoc($result)){
                $response[$i]['id'] = $row['id'];
                $response[$i]['id_usuario'] = $row['id_usuario'];
                $response[$i]['nombres'] = $row['nombres'];
                $response[$i]['orden'] = $row['orden'];
                $response[$i]['fecha'] = $row['fecha']; 
                $i++;
            }
            echo  json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);  
        }

        

    }
}else{
    echo "DB FOUND CONNECTED";
}


?>