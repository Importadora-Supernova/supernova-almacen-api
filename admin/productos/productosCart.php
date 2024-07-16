<?php
// conexion con base de datos 
include '../../conexion/conn.php';
include '../../headers.php';
//import middleware

// declarar array para respuestas 
$response = array();


date_default_timezone_set('America/Mexico_City');
$fecha = date('Y-m-d H:i:s');

// insertamos cabeceras para permisos 

// validamos si hay conexion 
if($con){
    //if($token_access['token']){
        $methodApi = $_SERVER['REQUEST_METHOD'];

        //Peticion GET
        if($methodApi == 'GET'){
            $sql  = 'SELECT c.*,p.nombre,p.preciou,p.preciom,p.precioc,p.topem,p.topec FROM app_cart_products c INNER JOIN productos p ON c.product_id = p.id  WHERE c.user_id=?';
            $stmt = $con->prepare($sql);
            $stmt->bind_param('i',$_GET['user_id']);
            $stmt->execute();
            $result = $stmt->get_result();
            $data = $result->fetch_all(MYSQLI_ASSOC);
            echo json_encode($data,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
        }

        //PETICION POST
        if($methodApi == 'POST'){

            try{
                $_POST = json_decode(file_get_contents('php://input'),true);
                $user_id    = $_POST['user_id'];
                $product_id = $_POST['product_id'];
                $codigo     = $_POST['codigo'];
                $cantidad   = $_POST['cantidad'];
                $precio     = $_POST['precio'];
                $imagen     = $_POST['imagen'];
                $cambio     = $_POST['cambio'];
    
                if($cantidad == 0 || $cantidad == null || $codigo == '' || $precio == null || $precio <= 0 || $imagen == ''){
                    header("HTTP/1.1 400");
                    $response['status'] = 400;
                    $response['mensaje'] = 'Hubo un error en los datos, por favor verifique';
                    echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                }else{
    
                    if($cambio){
                        $precio_actualizado = floatval($precio);
                        $sqlUpdatedPrice = 'UPDATE app_cart_products SET precio=? WHERE codigo_product=? AND user_id=?';
                        $stmtUp = $con->prepare($sqlUpdatedPrice);
                        $stmtUp->bind_param('dsi',$precio_actualizado,$codigo,$user_id);
                        $result_updated = $stmtUp->execute();
                    }
    
                    $sqlBuscar = 'SELECT id_cart,cantidad FROM app_cart_products WHERE user_id=? AND product_id=?';
                    $stmtB = $con->prepare($sqlBuscar);
                    $stmtB->bind_param('ii',$user_id,$product_id);
                    $stmtB->execute();
                    $resultB = $stmtB->get_result(); 
                    $data    = $resultB->fetch_assoc(); 
    
                    $result = null;
    
                    if($data){
                        $total =  $data['cantidad']+intval($cantidad);
                        $sqlUpdate = 'UPDATE app_cart_products SET cantidad=? WHERE id_cart=?';
                        $stmtU = $con->prepare($sqlUpdate);
                        $stmtU->bind_param('ii',$total,$data['id_cart']);
                        $result = $stmtU->execute();
                    }else{
                        $sql = 'INSERT INTO app_cart_products (user_id,product_id,codigo_product,cantidad,precio,imagen,fecha_register) VALUES (?,?,?,?,?,?,?)';
                        $stmt = $con->prepare($sql);
                        $stmt->bind_param('iisidss',$user_id,$product_id,$codigo,$cantidad,$precio,$imagen,$fecha);
                        $result = $stmt->execute();
                    } 
                }
    
                if($result == 1){
                    header("HTTP/1.1 200 OK");
                    $response['status'] = 200;
                    $response['mensaje'] = 'Registro creado correctamente';
                    echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                }else{
                    header("HTTP/1.1 400");
                    $response['status'] = 400;
                    $response['mensaje'] = 'No se pudo Guardar el registro';
                    echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                }
            }catch(Exception $e){
                header("HTTP/1.1 400");
                $response['status'] = 400;
                $response['mensaje'] = $e->getMessage();
                echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
            }   
        }

        //PETICION PUT
        if($methodApi == 'PUT'){
            try{
                //iniciamos proceso de transaccion 
                $con->autocommit(false);

                $_PUT = json_decode(file_get_contents('php://input'),true);
                //definimos variables
                $id_cart  = $_GET['id'];
                $user_id  = $_PUT['user_id'];
                $cantidad = $_PUT['cantidad'];
                $precio   = $_PUT['precio'];
                $codigo   = $_PUT['codigo'];

                
                //sentencia para actualizar la cantidad de ese item
                $sqlUpdate = 'UPDATE app_cart_products SET cantidad=? WHERE id_cart=?';
                $stmt = $con->prepare($sqlUpdate);
                $stmt->bind_param('ii',$cantidad,$id_cart);
                $result = $stmt->execute();
                
                //sentencia para actualizar el precio de todos los productos con ese codigo
                $sqlUpdatedPrecio = 'UPDATE app_cart_products SET precio=? WHERE codigo_product=? AND user_id=?';
                $stmtUp = $con->prepare($sqlUpdatedPrecio);
                $stmtUp->bind_param('dsi',$precio,$codigo,$user_id);
                $result_updated = $stmtUp->execute();
                
                //validamos que ambos update se hallan ejecutado correctamente
                if($result == 1 && $result_updated == 1){
                    $con->commit();
                    header("HTTP/1.1 200 OK");
                    $response['status'] = 200;
                    $response['mensaje'] = 'Se actualizo registro  correctamente';
                    echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                }else{
                    $con->rollback();
                    header("HTTP/1.1 400");
                    $response['status'] = 400;
                    $response['mensaje'] = 'No se pudo actualizar el registro';
                    echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                }
            }catch(Exception $e){
                $con->rollback();
                header("HTTP/1.1 400");
                $response['status'] = 400;
                $response['mensaje'] =  $e->getMessage();
                echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
            }



        }

        //PETICION DELETE
        if($methodApi == 'DELETE'){
            try{
                $con->autocommit(false);
                $_DELETE = json_decode(file_get_contents('php://input'),true);
            
                $id         = $_GET['id'];
                $codigo     = $_DELETE['codigo'];
                $product_id = $_DELETE['product_id'];
                $user_id    = $_DELETE['user_id'];

    
                $sql = 'DELETE FROM app_cart_products WHERE id_cart=?';
                $stmt = $con->prepare($sql);
                $stmt->bind_param('i',$id);
                $result = $stmt->execute();
    
                $sqlRegister = 'INSERT INTO  app_historial_delete_cart (id_producto,cod_producto,id_usuario,fecha_delete) VALUES (?,?,?,?)';
                $stmtR = $con->prepare($sqlRegister);
                $stmtR->bind_param('isis',$product_id,$codigo,$user_id,$fecha);
                $resultInsert = $stmtR->execute();
    
                if($result == 1 && $resultInsert == 1){
                    $con->commit();
                    header("HTTP/1.1 200 OK");
                    $response['status'] = 200;
                    $response['mensaje'] = 'Registro eliminado correctamente';
                    echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                }else{
                    $con->rollback();
                    header("HTTP/1.1 400");
                    $response['status'] = 400;
                    $response['mensaje'] = 'No se pudo eliminar el registro';
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
}else{
    echo "DB FOUND CONNECTED";
}