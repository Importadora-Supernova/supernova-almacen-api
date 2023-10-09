<?php
// conexion con base de datos 
include '../conexion/conn.php';
include '../class/querys.php';

//import middleware
include '../middleware/validarToken.php';

// declarar array para respuestas 
$response = array();
$producto = array();

date_default_timezone_set('America/Mexico_City');
$fecha_actual = date('Y-m-d H:i:s');
// insertamos cabeceras para permisos 

header('Access-Control-Allow-Origin:*');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept,Authorization, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");
header("Content-Type: JSON");
header('Content-Type: application/json;charset=utf-8'); 

$query = new Querys();


// validamos si hay conexion 
if($con){
    if($token_access['token']){
        $methodApi = $_SERVER['REQUEST_METHOD'];
        if($methodApi == 'GET'){
            $id_producto = $_GET['id'];

            $Producto = $query->getQueryId($con,'SELECT *FROM productos WHERE id=?',$id_producto);
            $producto['id']                      =  $Producto['id'];
            $producto['nombre']                  =  $Producto['nombre'];
            $producto['codigo']                  =  $Producto['codigo'];
            $producto['almacen']                 =  $Producto['almacen'];
            $producto['preciou']                 =  $Producto['preciou'];
            $producto['preciom']                 =  $Producto['preciom'];
            $producto['precioc']                 =  $Producto['precioc'];
            $producto['preciov']                 =  $Producto['preciov'];
            $producto['topem']                   =  $Producto['topem'];
            $producto['topec']                   =  $Producto['topec'];
            $producto['topev']                   =  $Producto['topev'];
            $producto['descuento']               =  $Producto['descuento'];
            $producto['descuento_precio_docena'] =  $Producto['descuento_precio_docena'];
            
            $result = $query->getQuery($con,'SELECT *FROM admin_caracteristicas');
                $i=0;
                $sql = 'SELECT *FROM view_atributos_producto WHERE id_producto=? AND id_caracteristica=?';
                foreach ($result as $row) {
                    $caracteristicas[$i]['id_admin_caracteristica'] = $row["id_admin_caracteristica"];
                    $caracteristicas[$i]['nombre_caracteristica'] = $row["nombre_caracteristica"];
                    $resultado = $query->getQueryIdArrayAtrr($con,$sql,$id_producto,$row["id_admin_caracteristica"]);
                    $j=0;
                    foreach($resultado as $fill){
                        $atributos[$j]['id_atributo'] = $fill['id_atributo'];
                        $atributos[$j]['id_register'] = $fill['id_register'];
                        $atributos[$j]['nombre_atributo']= $fill['nombre_atributo'];
                        $atributos[$j]['valor_atributo'] = $fill['valor_atributo'];
                        $atributos[$j]['preffix'] = $fill['preffix'];
                        $j++;
                    }
                    $caracteristicas[$i]['atributos'] = $atributos;
                    $i++;
                }
                $response['producto'] = $producto;
                $response['caracteristicas'] = $caracteristicas;
                echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
        }

        if($methodApi == 'POST'){
            $_POST = json_decode(file_get_contents('php://input'),true);

            $con->autocommit(false);

            try{
                $atributos = $_POST['atributos'];
                $id = $_POST['id_producto'];
                $band = false;  
                 //ciclo para ejecutar segun la cantidad de atributos enviados
                foreach($atributos as $data){
                    //declaramos los registros y sentencia de insercion
                    $id_atributo = $data['id'];
                    $valor       = $data['value'];
                    $sentencia = 'INSERT INTO admin_atributos_productos (id_producto,id_atributo,valor_atributo,fecha_created) VALUES (?,?,?,?)';
                    $ok = $query->insertAtributosProducto($con,$sentencia,$id,$id_atributo,$valor,$fecha_actual);
    
                    if($ok == 1){
                        $band = true;
                    }else{
                        $band = false;
                    }
                } 
                 //fin del ciclo
                //validamos que el flag este en true y todo se haya ejecutado corretamente
                //para ejecutar el commit y enviar respuesta exitosa
                if($band){
                    $con->commit();
                    header("HTTP/1.1 200 OK");
                    $response['status'] = 200;
                    $response['mensaje'] = 'Registros creados correctamente';
                    echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                    $con->close();
                }else{
                    //si ocurrio un error en una de las transacciones ejecutamos un rollback
                    $con->rollback();
                    header("HTTP/1.1 400");
                    $response['status'] = 400;
                    $response['mensaje'] = 'No se pudo Guardar los registros';
                    echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);    
                    $con->close(); 
                }
            }catch(Exception $e){
                 //si ocurrio un error en el proceso ejecutamos roolback  
                $con->rollback();
                header("HTTP/1.1 400");
                $response['status'] = 400;
                $response['mensaje'] = 'No se pudo Guardar los registros'.$e;
                echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);    
                $con->close(); 
            }
        }

        if($methodApi == 'PUT'){
            $_PUT = json_decode(file_get_contents('php://input'),true);

            $con->autocommit(false);

            try{
                $atributos = $_PUT['atributos'];
                $band = false;  
                 //ciclo para ejecutar segun la cantidad de atributos enviados
                foreach($atributos as $data){
                    
                    //declaramos los registros y sentencia de insercion
                    $id          = $data['id_register'];
                    $valor       = $data['value'];
                    $sentencia = 'UPDATE admin_atributos_productos SET valor_atributo = ? WHERE id_admin_atributos_productos = ?';
                    $ok = $query->updateAtributos($con,$sentencia,$valor,$id);
                    if($ok == 1){
                        $band = true;
                    }else{
                        $band = false;
                    }
                } 
                    //fin del ciclo
                //validamos que el flag este en true y todo se haya ejecutado corretamente
                //para ejecutar el commit y enviar respuesta exitosa
                if($band){
                    $con->commit();
                    header("HTTP/1.1 200 OK");
                    $response['status'] = 200;
                    $response['mensaje'] = 'Registros creados correctamente';
                    echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                    $con->close();
                }else{
                    //si ocurrio un error en una de las transacciones ejecutamos un rollback
                    $con->rollback();
                    header("HTTP/1.1 400");
                    $response['status'] = 400;
                    $response['mensaje'] = 'No se pudo Guardar los registros';
                    echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);    
                    $con->close(); 
                }
            }catch(Exception $e){
                //si ocurrio un error en el proceso ejecutamos roolback  
                $con->rollback();
                header("HTTP/1.1 400");
                $response['status'] = 400;
                $response['mensaje'] = 'No se pudo Guardar los registros';
                echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);    
                $con->close(); 
            }
        }
    }else{
        echo $token_access['validate'];
    }

}else{
    echo "DB FOUND CONNECTED";
}
?>