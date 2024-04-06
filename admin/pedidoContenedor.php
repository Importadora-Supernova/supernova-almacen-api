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

            $id_pedido = $_GET['id_pedido'];

            $sql = 'SELECT d.id_detalle_pedido_contenedor,d.pedido,d.codigo_producto,d.nombre_producto,d.pz_caja,d.num_cajas,d.nuevo,d.id_producto,d.id_proveedor,p.nombre_proveedor FROM detalle_pedido_contenedor d INNER JOIN admin_proveedores p ON d.id_proveedor = p.id_proveedor WHERE d.pedido=?';

            $stmt = $con->prepare($sql);
            $stmt->bind_param('i',$id_pedido);
            $stmt->execute();
            $result = $stmt->get_result();
            $response = $result->fetch_all(MYSQLI_ASSOC);
            echo  json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT); 
    }

    if($methodApi == 'POST'){

        try{
            $_POST = json_decode(file_get_contents('php://input'),true);

            $pedido      = $_POST['pedido'];
            $codigo      = $_POST['codigo'];
            $nombre      = $_POST['nombre'];
            $pz_cajas    = $_POST['pz_caja'];
            $cajas       = $_POST['cajas'];
            $nuevo       = $_POST['nuevo'];
            $id_producto = $_POST['id_producto'];
            $proveedor   = $_POST['proveedor'];
    
            $sql = 'INSERT INTO detalle_pedido_contenedor(pedido,codigo_producto,nombre_producto,pz_caja,num_cajas,nuevo,id_producto,id_proveedor) VALUES (?,?,?,?,?,?,?,?)';
            $stmt = $con->prepare($sql);
            $stmt->bind_param('issiiiii',$pedido,$codigo,$nombre,$pz_cajas,$cajas,$nuevo,$id_producto,$proveedor);
            $reta = $stmt->execute();
    
            if($reta == 1){
                header("HTTP/1.1 200 OK");
                $response['status'] = 200;
                $response['mensaje'] = 'Se registro correctamente';
                echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT); 
            }else{
                header("HTTP/1.1 400");
                $response['status'] = 400;
                $response['mensaje'] = 'No se pudo completar el proceso';
                echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
            }
        }catch(Exception $e){
            header("HTTP/1.1 400");
            $response['status'] = 400;
            $response['mensaje'] = $e->getMessage();
            echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
        }
    }

    if($methodApi == 'PUT'){
        $_PUT = json_decode(file_get_contents('php://input'),true);
        
        $id = $_GET['id'];
        $codigo         = $_PUT['codigo'];
        $nombre         = $_PUT['nombre'];
        $pz_caja        = $_PUT['pz_caja'];
        $num_cajas      = $_PUT['num_cajas'];

        $sql = 'UPDATE detalle_pedido_contenedor SET codigo_producto=?,nombre_producto=?,pz_caja=?,num_cajas=? WHERE id_detalle_pedido_contenedor=?';
        $stmt = $con->prepare($sql);
        $stmt->bind_param('ssiii',$codigo,$nombre,$pz_caja,$num_cajas,$id);
        $reta = $stmt->execute();

        if($reta == 1){
            header("HTTP/1.1 200 OK");
            $response['status'] = 200;
            $response['mensaje'] = 'Se actualizo el registro correctamente';
            echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT); 
        }else{
            header("HTTP/1.1 400");
            $response['status'] = 400;
            $response['mensaje'] = 'No se pudo completar el proceso';
            echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
        }
    }

    if($methodApi == 'DELETE'){
        $id = $_GET['id'];

        $sql = 'DELETE FROM detalle_pedido_contenedor WHERE id_detalle_pedido_contenedor=?';
        $stmt = $con->prepare($sql);
        $stmt->bind_param('i', $id);
        $reta = $stmt->execute();

        if($reta == 1){
            header("HTTP/1.1 200 OK");
            $response['status'] = 200;
            $response['mensaje'] = 'Se elimino  correctamente';
            echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT); 
        }else{
            header("HTTP/1.1 400");
            $response['status'] = 400;
            $response['mensaje'] = 'No se pudo completar el proceso';
            echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
        }
    }

}else{
echo "DB FOUND CONNECTED";
}

?>