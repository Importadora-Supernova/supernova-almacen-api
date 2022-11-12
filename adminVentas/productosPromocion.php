<?php
// conexion con base de datos 
include '../conexion/conn.php';

// declarar array para respuestas 
$response = array();

// insertamos cabeceras para permisos 

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type,Authorization, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");
header("Content-Type: JSON");
header('Content-Type: application/json;charset=utf-8'); 

date_default_timezone_set('America/Mexico_City');
$fecha = date('Y-m-d H:i:s');


// validamos si hay conexion 
if($con){
    
        $methodApi = $_SERVER['REQUEST_METHOD'];

        switch($methodApi){
            // metodo get 
            case 'GET':
             // para obtener un registro especifico
                    $sqlPagados = 'SELECT * FROM productos_promocion WHERE codigo="'.$_GET['codigo'].'"';
                    $resultPagados = mysqli_query($con,$sqlPagados);
                    $i=0;
                    while($row = mysqli_fetch_assoc($resultPagados)){
                        $response[$i]['id'] = $row['id'];
                        $response[$i]['nombre'] = $row['nombre'];
                        $response[$i]['codigo'] = $row['codigo'];
                        $response[$i]['descuento_precio_docena'] = $row['descuento_precio_docena'];
                        $response[$i]['ruta'] = $row['ruta'];
                        $response[$i]['a'] = $row['a'];
                        $i++;
                    }

                    echo  json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
            break;
        }
    //echo "Informacion".file_get_contents('php://input');

}else{
    echo "DB FOUND CONNECTED";
}
?>  