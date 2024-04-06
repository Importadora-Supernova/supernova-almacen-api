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

            $num_contenedor = $_POST['num_contenedor'];
            $num_pedido     = $_POST['num_pedido'];
            $proveedor      = $_POST['proveedor'];
            $almacen        = $_POST['almacen'];
            $jsonProductos  = $_POST['productos'];

            $sql = 'INSERT INTO admin_pedido_contenedor(num_contenedor,num_pedido,proveedor,almacen,estatus,fecha_register) VALUES ("'.$num_contenedor.'","'.$num_pedido.'",'.$proveedor.','.$almacen.',"Pedido Creado","'.$fecha.'")';

            $result = mysqli_query($con,$sql);

            if($result == 1){
                $last_id = $con->insert_id;
                $band = false;

                foreach($jsonProductos as $data){
                    
                    $sqlInsert = 'INSERT INTO detalle_pedido_contenedor (pedido,codigo_producto,nombre_producto,pz_caja,num_cajas,nuevo,id_producto,id_proveedor) VALUES (?,?,?,?,?,?,?,?)';
                    $stmt = $con->prepare($sqlInsert);
                    $stmt->bind_param('issiiiii',$last_id,$data['codigo'],$data['nombre'],$data['pz_caja'],$data['cajas'],$data['nuevo'],$data['id_producto'],$data['id_proveedor']);
                    $reta = $stmt->execute();
                    if($reta == 1 ){
                        $band = true;
                    }else{
                        $con->rollback();
                        $band = false;
                        break;
                    }
                }
                if($band){
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
        if(isset($_GET['id'])){
            $id = $_GET['id'];

            $sql = 'SELECT c.id_pedido_contenedor as id,c.num_contenedor,c.num_pedido,c.proveedor as id_proveedor,p.nombre_proveedor,c.almacen as id_almacen,a.nombre_almacen,c.estatus,c.fecha_register FROM admin_pedido_contenedor c INNER JOIN admin_proveedores p ON c.proveedor = p.id_proveedor INNER JOIN almacenes a ON c.almacen = a.id_almacen WHERE c.id_pedido_contenedor=?';

            $stmt = $con->prepare($sql);
            $stmt->bind_param('i',$id);
            $stmt->execute();
            $result = $stmt->get_result();
            $response = $result->fetch_assoc();
            echo  json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);

        }else{
            $fecha         = $_GET['fecha'] ?? '';
            $numero_pedido = $_GET['numero_pedido'] ?? '';
            $proveedor     = $_GET['proveedor'] ?? '';
            $almacen       = $_GET['almacen'] ?? '';
            $total         = $_GET['total'] ?? '';
            
            $sql = 'SELECT c.id_pedido_contenedor as id,c.num_contenedor,c.num_pedido,c.proveedor as id_proveedor,p.nombre_proveedor,c.almacen as id_almacen,a.nombre_almacen,c.estatus,c.fecha_register,c.fecha_recibido FROM admin_pedido_contenedor c INNER JOIN admin_proveedores p ON c.proveedor = p.id_proveedor INNER JOIN almacenes a ON c.almacen = a.id_almacen WHERE 1 = 1';
    
            if (!empty($fecha)) {
                $sql .= " AND fecha_register = ?";
            }
    
            if (!empty($numero_pedido)) {
                $sql .= " AND num_pedido = ?";
            }
    
            if (!empty($proveedor)) {
                $sql .= " AND proveedor = ?";
            }
    
            if (!empty($almacen)) {
                $sql .= " AND almacen = ?";
            }
    
            if (!empty($total)) {
                $sql .= " ORDER BY id_pedido_contenedor DESC";
            }
    
            $stmt = $con->prepare($sql);
            
            // Bind de los parámetros
            if (!empty($fecha)) {
                $stmt->bind_param('s', $fecha);
            }
    
            if (!empty($numero_pedido)) {
                $stmt->bind_param('i', $numero_pedido);
            }
    
            if (!empty($proveedor)) {
                $stmt->bind_param('i', $proveedor);
            }
    
            if (!empty($almacen)) {
                $stmt->bind_param('i', $almacen);
            }
            $stmt->execute();
            $result = $stmt->get_result();
            $response = $result->fetch_all(MYSQLI_ASSOC);
            echo  json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
        }

    }   

    if($methodApi == 'PUT'){
        $_PUT = json_decode(file_get_contents('php://input'),true);

        $id    = $_GET['id'];

        $num_contenedor = $_PUT['num_contenedor'];
        $num_pedido     = $_PUT['num_pedido'];
        $proveedor      = $_PUT['proveedor'];
        $proveedorViejo = $_PUT['proveedor_viejo'];
        $almacen        = $_PUT['almacen'];
        $cambio         = $_PUT['cambio'];
        $estatus        = 'Pedido Actualizado';

    
        $sql = 'UPDATE admin_pedido_contenedor SET num_contenedor=?,num_pedido=?,proveedor=?,almacen=?,estatus=? WHERE id_pedido_contenedor=?';
        $stmt = $con->prepare($sql);
        $stmt->bind_param('ssiisi',$num_contenedor,$num_pedido,$proveedor,$almacen,$estatus,$id);
        $reta = $stmt->execute();

        if($cambio){
            $sqlUpdatedDetalle = 'UPDATE detalle_pedido_contenedor SET id_proveedor=? WHERE id_proveedor=?';
            $stmt2 = $con->prepare($sqlUpdatedDetalle);
            $stmt2->bind_param('ii',$proveedor,$proveedorViejo);
            $reta2 = $stmt2->execute();
        }

        

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
}else{
echo "DB FOUND CONNECTED";
}

?>