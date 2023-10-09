<?php
// conexion con base de datos 
include '../conexion/conn.php';
include '../class/querys.php';
//incluir middleware
include '../middleware/validarToken.php';

// declarar array para respuestas 
$products = array();
$response = array();

date_default_timezone_set('America/Mexico_City');
$fecha = date('Y-m-d H:i:s');

$query = new Querys();

// insertamos cabeceras para permisos 

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept,Authorization, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");
header("Content-Type: JSON");
header('Content-Type: application/json;charset=utf-8'); 

// validamos si hay conexion 
if($con){
    if($token_access['token']){
        $methodApi = $_SERVER['REQUEST_METHOD'];

        if($methodApi == 'GET'){
            $sql = "SELECT *FROM view_proveedor_producto  WHERE codigo=?";
            $codigo = base64_decode($_GET['codigo']);

            $result = $query->getItemsCodigo($con,$sql,$codigo);

            $i=0;
            foreach($result as $row){
                $response[$i]['id_proveedor']     = $row['id_proveedor'];
                $response[$i]['nombre_proveedor'] = $row['nombre_proveedor'];
                $response[$i]['porcentaje_flete'] = $row['porcentaje_flete'];
                $response[$i]['precio_yuan']      =  $row['precio_yuan'];
                $response[$i]['precio_costo']     =  $row['precio_costo'];
                $response[$i]['selected']         =  true;
                $i++;
            }
            echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
            $con->close(); 

        }

        if($methodApi == 'POST'){
            $_POST = json_decode(file_get_contents('php://input'),true);

            $con->autocommit(false);
            $sql = "SELECT *FROM productos WHERE codigo LIKE ?";

            $codigo       = $_POST['codigo'];
            $precio_costo = $_POST['precio_costo'];
            $precio_yuan  =  $_POST['precio_yuan'];
            $tasa         = $_POST['tasa_cambio'];
            $id_producto  = $_POST['id_producto'];

            $proveedores  = $_POST['proveedores'];

            $result = $query->getItemsCodigo($con,$sql,$codigo);

            $insert    = false;
            $update    = false;
            $historial = false;

            $sqlUpdate = "UPDATE productos SET precio_costo_provisional=?,precio_yuan=? WHERE id=?";
            foreach($result as $row){
                $resultUpdate = $query->updatePrecioCosto($con,$sqlUpdate,$precio_costo,$precio_yuan,$row['id']);
                $update = $resultUpdate == 1 ? true : false;
            }

            $sqlInsert = "INSERT INTO admin_producto_proveedor (cod_producto,id_proveedor,id_producto,precio_costo,precio_yuan,porcentaje_flete,fecha_created) VALUES (?,?,?,?,?,?,?)";
            $sqlInsertHistorial = "INSERT INTO admin_histrorial_precio_proveedor (id_proveedor,cod_producto,precio_costo,precio_yuan,tasa_cambio,porcentaje_flete,fecha_created) VALUES (?,?,?,?,?,?,?)";
            $sqlSearch = "SELECT COUNT(*) AS total FROM admin_producto_proveedor WHERE cod_producto=? AND id_proveedor=?";
            $sqlUpdate = "UPDATE admin_producto_proveedor SET precio_costo=?,precio_yuan=?,porcentaje_flete=? WHERE cod_producto=? AND id_proveedor=?";

            foreach($proveedores as $fill){

                $existe = $query->searchProductoProveedor($con,$sqlSearch,$codigo,$fill['id_proveedor']);

                if($existe['total'] > 0){
                    $resultUpdate = $query->updateProductoProveedor($con,$sqlUpdate,$fill,$codigo);
                    $insert = $resultUpdate == 1 ? true : false;
                }else{
                    $resultInsert = $query->insertarProveedoresProducto($con,$sqlInsert,$codigo,$fill,$id_producto,$fecha);
                    $insert = $resultInsert == 1 ? true : false;
                }

                $resultInsertHistorial = $query->insertarHistorialProveedor($con,$sqlInsertHistorial,$fill,$tasa,$codigo,$fecha);
                $historial = $resultInsertHistorial == 1 ? true : false;

            }

            if($insert && $update && $historial){
                $con->commit();
                header("HTTP/1.1 200");
                $response['status'] = 200;
                $response['mensaje'] = 'Registros Guardados Exitosamente';
                echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT); 
            }else{
                $con->rollback();
                header("HTTP/1.1 400");
                $response['status'] = 400;
                $response['mensaje'] = 'Ocurrio un error al ejecutar proceso';
                echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT); 
            }
        }
    //echo "Informacion".file_get_contents('php://input');
    }else{
        echo $token_access['validate'];
    }
}else{
    echo "DB FOUND CONNECTED";
}
