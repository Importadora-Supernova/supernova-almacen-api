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

    if($methodApi == 'POST'){
        $con->autocommit(false);
        try
        {
            $_POST = json_decode(file_get_contents('php://input'),true);

            $id_tienda = $_POST['id_tienda'];
            $total     = $_POST['total'];
            $user_id   = $_POST['user_id'];
            $jsonProductos = $_POST['productos_cantidades'];

            $sql = 'INSERT INTO tienda_pedido_creado (id_tienda,total_productos,fecha_register,user_created) VALUES ('.$id_tienda.','.$total.',"'.$fecha.'",'.$user_id.')';

            $result = mysqli_query($con,$sql);

            if($result == 1){
                $last_id = $con->insert_id;
                $band = false;

                foreach($jsonProductos as $data){
                    
                    $sqlInsert = 'INSERT INTO detalle_pedido_tienda (id_pedido,id_producto,cantidad) VALUES (?,?,?)';
                    $stmt = $con->prepare($sqlInsert);
                    $stmt->bind_param('iii',$last_id,$data['id_producto'],$data['cantidad']);
                    $reta = $stmt->execute();

                    $sqlUpdateProductos = 'UPDATE productos SET almacen=almacen-'.$data['cantidad'].' WHERE id='.$data['id_producto'].'';
                    $resultProductos  = mysqli_query($con,$sqlUpdateProductos);

                    $sqlUpdateAlmacen = 'UPDATE almacen_producto SET cantidad=cantidad-'.$data['cantidad'].' WHERE id_producto='.$data['id_producto'].' AND id_almacen=15';
                    $resultAlmacen = mysqli_query($con,$sqlUpdateAlmacen);

                    if($reta == 1 && $resultProductos == 1 && $resultAlmacen == 1){
                        $band = true;
                    }else{
                        $con->rollback();
                        $band = false;
                        break;
                    }

                }

                if($band){
                    $sqlDelete = 'DELETE FROM pedidos_tienda WHERE id_tienda='.$id_tienda.'';
                    $resultDelete = mysqli_query($con,$sqlDelete);

                    if($result == 1){
                        $con->commit();
                        header("HTTP/1.1 200 OK");
                        $response['status'] = 200;
                        $response['mensaje'] = 'Se registraron correctamente';
                        echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT); 
                    }else{
                        $con->rollback();
                        header("HTTP/1.1 400");
                        $response['status'] = 400;
                        $response['mensaje'] = 'No se pudo completar el proceso';
                        echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                    }
                    
                }else{
                    $con->rollback();
                    header("HTTP/1.1 400");
                    $response['status'] = 400;
                    $response['mensaje'] = 'No se pudo crear el registro, intentalo de nuevo';
                    echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                }
            }else{
                $con->rollback();
                header("HTTP/1.1 400");
                $response['status'] = 400;
                $response['mensaje'] = 'No se pudo crear el registro, intentalo de nuevo';
                echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
            }


        }catch(Exception $e){
            $con->rollback();
            header("HTTP/1.1 400");
            $response['status'] = 400;
            $response['mensaje'] = $e->getMessage();
            echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
        }  
    }
    
    if($methodApi == 'GET'){
        if(isset($_GET['id_tienda'])){
             $id_tienda = $_GET['id_tienda'];

            $sql = 'SELECT p.id_pedido_creado as id, p.id_tienda, p.total_productos, p.fecha_register, p.user_created, t.nombre_tienda, u.usuario_bodega FROM tienda_pedido_creado p INNER JOIN admin_tiendas t ON p.id_tienda = t.id INNER JOIN app_usuarios_bodega u ON p.user_created = u.id_user_bodega WHERE p.id_tienda=? ORDER BY p.id_pedido_creado DESC LIMIT 10';

            $stmt = $con->prepare($sql);
            $stmt->bind_param('i',$id_tienda);
            $stmt->execute();
            $result = $stmt->get_result();
            $response = $result->fetch_all(MYSQLI_ASSOC);
            echo  json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT); 
        }else if(isset($_GET['id_pedido'])){

            $id_pedido = $_GET['id_pedido'];

            $sql = 'SELECT t.id_pedido,t.id_producto,t.cantidad,p.codigo,p.nombre FROM detalle_pedido_tienda t INNER JOIN productos p ON t.id_producto = p.id WHERE t.id_pedido=?';

            $stmt = $con->prepare($sql);
            $stmt->bind_param('i',$id_pedido);
            $stmt->execute();
            $result = $stmt->get_result();
            $response = $result->fetch_all(MYSQLI_ASSOC);
            echo  json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT); 

        }else{
            //
        }
       
    }
}else{
echo "DB FOUND CONNECTED";
}

?>