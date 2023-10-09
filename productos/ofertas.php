<?php
// conexion con base de datos 
include '../conexion/conn.php';



// declarar array para respuestas 
$response = array();
$producto = array();
$images   = array();

date_default_timezone_set('America/Mexico_City');
$fecha_actual = date('Y-m-d H:i:s');
// insertamos cabeceras para permisos 

header('Access-Control-Allow-Origin:*');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");
header("Content-Type: JSON");
header('Content-Type: application/json;charset=utf-8'); 




// validamos si hay conexion 
if($con){
    $methodApi = $_SERVER['REQUEST_METHOD'];
    if($methodApi == 'GET'){

        $sql = 'SELECT * FROM `productos` WHERE  (codigo LIKE "LA-%" OR codigo LIKE "EF-%" OR codigo LIKE "CO-%") and descuento="si"';
        $stmt = $con->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_all(MYSQLI_ASSOC);
        $i =0;
        foreach($data as $row){
            $producto[$i]['id'] = $row['id'];
            $producto[$i]['codigo'] = $row['codigo'];
            $producto[$i]['nombre'] = $row['nombre'];
            $producto[$i]['precio'] = $row['preciou'];
            $producto[$i]['precio_oferta'] = $row['descuento_precio_unidad'];
            $resultado = mysqli_query($con,'SELECT  ruta,a FROM img WHERE id_producto='.$row['id'].'');

            $j = 0;
            while($fill = mysqli_fetch_assoc($resultado)){
                $images[$j]['ruta'] = $fill['ruta'];
                $images[$j]['imagen'] = $fill['a'];
                $j++;
            }
            $producto[$i]['imagenes'] = $images;
            $i++;
        }
        echo json_encode($producto,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);

    }
}else{
    echo "DB FOUND CONNECTED";
}
?>