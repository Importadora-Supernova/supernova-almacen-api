<?php
// conexion con base de datos 
include '../conexion/conn.php';
date_default_timezone_set('America/Mexico_City');
//incluir middleware


// declarar array para respuestas 
$response = array();

// insertamos cabeceras para permisos 

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
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
            if(isset($_GET['orden'])){
                $sql = 'SELECT  *FROM productos_orden WHERE orden="'.$_GET['orden'].'" AND delete_item != "1"';
                $result = mysqli_query($con,$sql);
                $i=0;
                while($row = mysqli_fetch_assoc($result)){      
                    $response[$i]['id'] =  $row['id'];
                    $response[$i]['id_producto'] =  $row['id_producto'];
                    $response[$i]['nombre'] =  $row['nombre'];
                    $response[$i]['codigo'] =  $row['codigo'];
                    $response[$i]['cantidad'] =  $row['cantidad'];
                    $response[$i]['precio'] =  $row['precio'];
                    $response[$i]['preciou'] =  $row['preciou'];
                    $response[$i]['preciom'] =  $row['preciom'];
                    $response[$i]['precioc'] =  $row['precioc'];
                    $response[$i]['preciov'] =  $row['preciov'];
                    $response[$i]['topem'] =  $row['topem'];
                    $response[$i]['topec'] =  $row['topec'];
                    $response[$i]['topev'] =  $row['topev'];
                    $response[$i]['descuento'] =  $row['descuento'];
                    $response[$i]['descuento_precio_docena'] =  $row['descuento_precio_docena'];
                    $response[$i]['almacen'] =  $row['almacen'];
                    $response[$i]['total'] =  $row['cantidad']*$row['precio'];
                    $response[$i]['check'] = false;
                    $i++;          
                }
                echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                
                } else if(isset($_GET['ordenLimit'])){
                    $sql = 'SELECT  *FROM registro_usuario WHERE orden="'.$_GET['ordenLimit'].'" LIMIT 1';
                    $result = mysqli_query($con,$sql);
                    $i=0;
                    while($row = mysqli_fetch_assoc($result)){
                        $response['id'] =  $row['id_usuario'];
                        $response['nombre'] =  $row['nombred'];
                        $response['apellido'] =  $row['apellidod'];
                        $response['direcciond'] =  $row['direcciond'];
                        $response['coloniad'] =  $row['coloniad'];
                        $response['ciudadd'] =  $row['ciudadd'];
                        $response['estadod'] =  $row['estadod'];
                        $response['codigopd'] =  $row['codigopd'];
                        $response['telefonod'] =  $row['telefonod'];
                        $response['rfc'] =  $row['rfc'];
                        $response['paqueteria'] =  $row['paqueteria'];
                        $response['fecha'] = $row['fecha'];
                        $i++;
                    }
                    echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                }
        }

    //echo "Informacion".file_get_contents('php://input');

}else{
    echo "DB FOUND CONNECTED";
}
?>