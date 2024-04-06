<?php
// conexion con base de datos 
include '../conexion/conn.php';
//incluir middleware

// declarar array para respuestas 
$response = array();
$producto = array();
$imagenes = array();

// insertamos cabeceras para permisos 

header('Access-Control-Allow-Origin:*');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept,Authorization, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");
header("Content-Type: JSON");
header('Content-Type: application/json;charset=utf-8'); 



// validamos si hay conexion 
if($con){

    $methodApi = $_SERVER['REQUEST_METHOD'];
        if($methodApi == 'GET'){

            $sql = 'SELECT *FROM productos WHERE almacen>=50'; 
            $result = mysqli_query($con,$sql);
            $i=0;
            while($row = mysqli_fetch_assoc($result)){
                $producto[$i]['id'] = $row['id'];
                $producto[$i]['nombre'] = $row['nombre'];
                $producto[$i]['codigo'] = $row['codigo'];
                $sqlImg = 'SELECT a FROM img WHERE id_producto = "'.$row['id'].'" limit 1';
                $result2 = mysqli_query($con,$sqlImg);
                $j=0;
                while($fill = mysqli_fetch_assoc($result2)){
                    $imagenes[$j]['a'] = $fill['a'];
                    $j++;
                }
                $producto[$i]['imagenes'] = $imagenes;
                $i++;
            }
            $response['productos'] = $producto;
            header("HTTP/1.1 200");
            echo  json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT); 
        }
}else{
    echo "DB FOUND CONNECTED";
}
?>