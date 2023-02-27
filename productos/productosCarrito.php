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
            // metodo post 
            case 'POST':
                $_POST = json_decode(file_get_contents('php://input'),true);

                $action = $_POST['action'];

                if($action == 'add'){

                    $sqlConsultaPrecio = 'SELECT * FROM `registro_usuario` WHERE id_usuario = "'.$_POST['id_usuario'].'" AND codigo = "'.$_POST['codigo'].'" ORDER BY id DESC LIMIT 1';
                    $resultConsult = mysqli_query($con,$sqlConsultaPrecio);
                    $fill = mysqli_fetch_assoc($resultConsult);



                    if($_POST['cambio'] == true){
                        $sqlUpdate = 'UPDATE carrito_compras SET precio='.$_POST['precio'].' WHERE id_usuario="'.$_POST['id_usuario'].'" AND codigo="'.$_POST['codigo'].'"';
                        $resUpdate = mysqli_query($con,$sqlUpdate);
                    }

                    $sqlInsert = 'INSERT INTO carrito_compras (id_usuario,id_producto,codigo,precio,cantidad,fecha,color,huella,precio_viejo,ultima_orden_producto,orden_producto) VALUES ('.$_POST['id_usuario'].','.$_POST['id_producto'].',"'.$_POST['codigo'].'",'.$_POST['precio'].','.$_POST['cantidad'].',"'.$fecha.'","","","'.$fill['precio'].'","'.$fill['fecha'].'","'.$fill['orden'].'")';
                    $result = mysqli_query($con,$sqlInsert);
                    if($result){
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
                }else{
                    if($_POST['cambio'] == true){
                        $sqlUpdate = 'UPDATE carrito_compras SET precio='.$_POST['precio'].' WHERE id_usuario="'.$_POST['id_usuario'].'" AND codigo="'.$_POST['codigo'].'"';
                        $resUpdate = mysqli_query($con,$sqlUpdate);
                    }
                    $sqlDelete = 'DELETE FROM carrito_compras WHERE id='.$_POST['id_delete'].'';
                    $resultDelete = mysqli_query($con,$sqlDelete);
                    if($resultDelete){
                        header("HTTP/1.1 200 OK");
                        $response['status'] = 200;
                        $response['mensaje'] = 'Registro eliminado correctamente';
                        echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                    }else{
                        header("HTTP/1.1 400");
                        $response['status'] = 400;
                        $response['mensaje'] = 'No se pudo eliminar el registro';
                        echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                    }
                }

                
             //
            break;
            // metodo get 
            case 'GET':
                if(isset($_GET['id'])){
                    // para obtener un registro especifico
                    $sqlPagados = 'SELECT p.id,c.id as id_carrito,p.nombre,p.codigo,p.almacen,p.preciou,p.preciom,p.preciov,p.precioc,p.topem,p.topec,p.topev,p.descuento,p.descuento_precio_docena,c.precio,c.cantidad,c.precio_viejo,c.ultima_orden_producto,c.orden_producto FROM productos p INNER JOIN carrito_compras c ON p.id = c.id_producto  WHERE c.id_usuario = '.$_GET['id'].'';
                    $resultPagados = mysqli_query($con,$sqlPagados);
                    $i=0;
                    while($row = mysqli_fetch_assoc($resultPagados)){
                        $response[$i]['id']                      = $row['id'];
                        $response[$i]['id_carrito']              = $row['id_carrito'];
                        $response[$i]['nombre']                  = $row['nombre'];
                        $response[$i]['codigo']                  = $row['codigo'];
                        $response[$i]['precio']                  = $row['precio'];
                        $response[$i]['cantidad']                = $row['cantidad'];
                        $response[$i]['cantidad_old']            = $row['cantidad'];
                        $response[$i]['almacen']                 = $row['almacen'];
                        $response[$i]['preciou']                 = $row['preciou'];
                        $response[$i]['preciom']                 = $row['preciom'];
                        $response[$i]['precioc']                 = $row['precioc'];
                        $response[$i]['preciov']                 = $row['preciov'];
                        $response[$i]['topem']                   = $row['topem'];
                        $response[$i]['topec']                   = $row['topec'];
                        $response[$i]['topev']                   = $row['topev'];
                        $response[$i]['descuento']               = $row['descuento'];
                        $response[$i]['descuento_precio_docena'] = $row['descuento_precio_docena'];
                        $response[$i]['precio_viejo']            = $row['precio_viejo'];
                        $response[$i]['ultima_orden_producto']   = $row['ultima_orden_producto'];
                        $response[$i]['orden_producto']          = $row['orden_producto'];
                        $response[$i]['edit']                    = false;
                        $response[$i]['editPrice']               = false;
                        $i++;
                    }
                    echo  json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                }else{
                    if(isset($_GET['cars'])){
                        $sqlCar = 'SELECT *FROM usuario WHERE id IN (SELECT DISTINCT(id_usuario) FROM carrito_compras)';
                        $result = mysqli_query($con,$sqlCar);
                        $i=0;
                    }
                    while($row = mysqli_fetch_assoc($result)){
                        $response[$i]['id']        = $row['id'];
                        $response[$i]['nombre']    = $row['nombre'];
                        $response[$i]['apellido']  = $row['apellido'];
                        $response[$i]['direccion'] = $row['direccion'];
                        $response[$i]['correo']    = $row['correo'];
                        $response[$i]['telefono']  = $row['telefono'];
                        $response[$i]['ciudad']    = $row['ciudad'];
                        $response[$i]['colonia']   = $row['colonia'];
                        $response[$i]['estado']    = $row['estado'];
                        $response[$i]['codigop']   = $row['codigop'];
                        $response[$i]['rfc']       = $row['rfc'];
                        $response[$i]['orden']     = $row['orden'];
                        $i++;
                    }
                    echo  json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                }
            break;
            case 'PUT':
                $_PUT = json_decode(file_get_contents('php://input'),true);

                $modificar = $_PUT['modificar'];

                if($modificar == 'precio'){
                    $sqlUpdate = 'UPDATE carrito_compras SET precio='.$_PUT['precio'].' WHERE id_usuario="'.$_PUT['id_usuario'].'" AND codigo="'.$_PUT['codigo'].'"';
                    $resUpdate = mysqli_query($con,$sqlUpdate);
                    if($resUpdate){
                        header("HTTP/1.1 200 OK");
                        $response['status'] = 200;
                        $response['mensaje'] = 'Registro actulizado correctamente';
                        echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                    }else{
                        header("HTTP/1.1 400");
                        $response['status'] = 400;
                        $response['mensaje'] = 'No se pudo actualizar el registro';
                        echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                    }
                }else{
                    if($_PUT['cambio'] == true){
                        $sqlUpdate = 'UPDATE carrito_compras SET precio='.$_PUT['precio'].' WHERE id_usuario="'.$_PUT['id_usuario'].'" AND codigo="'.$_PUT['codigo'].'"';
                        $resUpdate = mysqli_query($con,$sqlUpdate);
                    }
    
                    $update = 'UPDATE carrito_compras SET cantidad='.$_PUT['cantidad'].'  WHERE id='.$_GET['id'].'';
                    $resUpdate = mysqli_query($con,$update);
    
                    if($resUpdate){
                        header("HTTP/1.1 200 OK");
                        $response['status'] = 200;
                        $response['mensaje'] = 'Registro actulizado correctamente';
                        echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                    }else{
                        header("HTTP/1.1 400");
                        $response['status'] = 400;
                        $response['mensaje'] = 'No se pudo actualizar el registro';
                        echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                    }
                }

            break;
            default:
            break;
        }
    //echo "Informacion".file_get_contents('php://input');

}else{
    echo "DB FOUND CONNECTED";
}
?>  