<?php
// conexion con base de datos 
include '../conexion/conn.php';

// declarar array para respuestas 
$response = array();


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
        $tienda = $_GET['id_tienda'];
        $sql = 'SELECT * FROM view_pedidos_tienda WHERE id_tienda='.$tienda.'';

        $result = mysqli_query($con,$sql);
        $i=0;
        while($row = mysqli_fetch_assoc($result)){
                $response[$i]['id_pedido_tienda']     = $row['id_pedido_tienda'];
                $response[$i]['id_producto']      = $row['id_producto'];
                $response[$i]['cantidad']         = $row['cantidad'];
                $response[$i]['id_tienda']       = $row['id_tienda'];
                $response[$i]['nombre_tienda']     = $row['nombre_tienda'];
                $response[$i]['id_almacen']       = $row['id_almacen'] == null ? "15" : $row['id_almacen'];
                $response[$i]['cantidad_almacen'] = $row['cantidad_almacen'] == null ? 0 : $row['cantidad_almacen'];
                $response[$i]['codigo']            = $row['codigo'];
                $response[$i]['producto']           = $row['producto'];
            $i++;
        }

        echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
    }

    if($methodApi == 'POST'){
        $_POST = json_decode(file_get_contents('php://input'),true);

        $tienda   = $_POST['id_tienda'];
        $producto = $_POST['id_producto'];
        $cantidad = $_POST['cantidad'];

        $reta = 0;
        $sql = 'INSERT INTO pedidos_tienda(id_tienda,id_producto,cantidad,fecha_register) VALUES (?,?,?,?)';
        $stmt = $con->prepare($sql);
        $stmt->bind_param('iiis',$tienda,$producto,$cantidad,$fecha);
        $reta = $stmt->execute();

        if($reta == 1){
            header("HTTP/1.1 200 OK");
            $response['status'] = 200;
            $response['mensaje'] = 'producto agregado exitosamente';
            echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
        }else{
            header("HTTP/1.1 400");
            $response['status'] = 400;
            $response['mensaje'] = 'No se pudo crear el registro, intentalo de nuevo';
            echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
        }
    }
    if($methodApi == 'PUT'){
        $_PUT = json_decode(file_get_contents('php://input'),true);

        $id       = $_GET['id'];
        $cantidad = $_PUT['cantidad'];

        $sql = 'UPDATE pedidos_tienda SET cantidad=? WHERE id_pedido_tienda=?';
        $stmt = $con->prepare($sql);
        $stmt->bind_param('ii',$cantidad,$id);
        $reta = $stmt->execute();
        if($reta == 1){
            header("HTTP/1.1 200 OK");
            $response['status'] = 200;
            $response['mensaje'] = 'registro actualizado exitosamente';
            echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
        }else{
            header("HTTP/1.1 400");
            $response['status'] = 400;
            $response['mensaje'] = 'No se pudo actualizar el registro, intentalo de nuevo';
            echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
        }

    }

    if($methodApi == 'DELETE'){
        $id = $_GET['id'];

        $sql = 'DELETE FROM pedidos_tienda WHERE id_pedido_tienda='.$id.'';
        $result = mysqli_query($con,$sql);
        if($result == 1){
            header("HTTP/1.1 200 OK");
            $response['status'] = 200;
            $response['mensaje'] = 'producto eliminado exitosamente';
            echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
        }else{
            header("HTTP/1.1 400");
            $response['status'] = 400;
            $response['mensaje'] = 'No se pudo eliminar el registro, intentalo de nuevo';
            echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
        }

    }
}else{
echo "DB FOUND CONNECTED";
}

?>