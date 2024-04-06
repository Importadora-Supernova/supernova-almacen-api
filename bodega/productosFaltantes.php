<?php
// conexion con base de datos 
include '../conexion/conn.php';
date_default_timezone_set('America/Mexico_City');

// declarar array para respuestas 
$response = array();

//incluir middleware
include '../middleware/validarToken.php';

// insertamos cabeceras para permisos 

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept,Authorization, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");
header("Content-Type: JSON");
header('Content-Type: application/json;charset=utf-8'); 

$fecha = date('Y-m-d H:i:s');

// validamos si hay conexion 
if($con){
    if($token_access['token']){
        function validarFecha($date, $format = 'Y-m-d H:i:s')
        {
            $d = DateTime::createFromFormat($format, $date);
            return $d && $d->format($format) == $date;
        }
    
        $methodApi = $_SERVER['REQUEST_METHOD'];

        if($methodApi == 'GET'){

            if(isset($_GET['orden'])){
                $orden    = $_GET['orden'];
                $sql = 'SELECT f.id_producto_pedido_pausado,f.orden,f.id_producto,f.accion_tomada,r.codigo,r.nombre,f.cantidad_faltante as cantidad,r.precio FROM productos_pedido_pausado f INNER JOIN registro_usuario r ON f.id_producto = r.id_producto WHERE f.orden=? GROUP BY r.nombre';
                $stmt = $con->prepare($sql);
                $stmt->bind_param('s',$orden);
                $stmt->execute();
                $result = $stmt->get_result();
                $response = $result->fetch_all(MYSQLI_ASSOC);
                echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
            }else{
                $codigo   = $_GET['codigo'];
                $cantidad = $_GET['cantidad'];
                $sql = 'SELECT id,codigo,nombre,preciou FROM productos WHERE codigo=? AND almacen>"'.$cantidad.'"';
                $stmt = $con->prepare($sql);
                $stmt->bind_param('s',$codigo);
                $stmt->execute();
                $result = $stmt->get_result();
                $response = $result->fetch_all(MYSQLI_ASSOC);
                echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
            }
        }
        //actualizar producto faltante
        if($methodApi == 'PUT'){
            $_PUT = json_decode(file_get_contents('php://input'),true);
            $id                 = $_GET['id'];
            $accion             = $_PUT['accion'];
            $cantidad           = $_PUT['cantidad_faltante'];
            $producto           = $_PUT['producto_sustituto'];
            $cantidad_sustituto = $_PUT['cantidad_sustituto'];
            $user               = $_PUT['id_usuario'];

            $sql = 'UPDATE productos_pedido_pausado SET cantidad_faltante=?,accion_tomada=?,fecha_updated=?,user_updated=? WHERE id_producto_pedido_pausado=?';

            $stmt = $con->prepare($sql);
            $stmt->bind_param('issii',$cantidad,$accion,$producto,$cantidad_sustituto,$fecha,$user,$id);
            $reta = $stmt->execute();

            if($reta == 1){
                header("HTTP/1.1 200 OK");
                $response['status'] = 200;
                $response['mensaje'] = 'Se actualizo exitosamente';
                echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT); 
            }else{
                header("HTTP/1.1 400");
                $response['status'] = 400;
                $response['mensaje'] = 'No se pudo completar el proceso';
                echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
            }

        }
    }else{
        echo $token_access['validate'];
    }
    //echo "Informacion".file_get_contents('php://input');

}else{
    echo "DB FOUND CONNECTED";
}
?>