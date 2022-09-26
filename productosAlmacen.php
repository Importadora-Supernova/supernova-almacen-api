<?php
// conexion con base de datos 
include 'conexion/conn.php';
//incluir middleware
include 'middleware/midleware.php';

date_default_timezone_set('America/Mexico_City');
$fecha = date('Y-m-d H:i:s');

// declarar array para respuestas 
$response = array();

// insertamos cabeceras para permisos 

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");
header("Content-Type: JSON");
header('Content-Type: application/json;charset=utf-8'); 



// validamos si hay conexion 
if($con){
    
    //echo "Informacion".file_get_contents('php://input');
        $methodApi = $_SERVER['REQUEST_METHOD'];

        switch($methodApi){
            // metodo post 
            case 'POST':

                $_POST = json_decode(file_get_contents('php://input'),true);
                //consultamos si el registro del producto existe en ese almacen
                $query_select = 'SELECT *FROM almacen_producto WHERE id_almacen='.$_POST['id_almacen'].' AND id_producto='.$_POST['id_producto'].'';
                $resultado = mysqli_query($con,$query_select);
                $fill = mysqli_fetch_assoc($resultado);

                //comprobando si el registro existe
                if($fill){
                    $con->autocommit(false);

                    $sqlUpdate = 'UPDATE almacen_producto SET cantidad=cantidad + '.$_POST['cantidad'].'  WHERE id_almacen='.$_POST['id_almacen'].' AND id_producto='.$_POST['id_producto'].'';
                    $res = mysqli_query($con,$sqlUpdate);

                    $sqlUpdateProducto = 'UPDATE productos SET almacen=almacen + '.$_POST['cantidad'].'  WHERE  id='.$_POST['id_producto'].'';
                    $resUpdate = mysqli_query($con,$sqlUpdateProducto);

                    $sqlHistorial = 'INSERT INTO historial_carga_producto (id_producto,id_almacen,cantidad,fecha_created) VALUES ('.$_POST['id_producto'].','.$_POST['id_almacen'].','.$_POST['cantidad'].',"'.$fecha.'")';
                    $resHistorial = mysqli_query($con,$sqlHistorial);

                    if($res && $resUpdate && $resHistorial){
                        $con->commit();
                        header("HTTP/1.1 200 OK");
                        $response['status'] = 200;
                        $response['mensaje'] = 'Registro actualizado correctamente';
                        echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                    }else{
                        $con->rollback();
                        header("HTTP/1.1 400");
                        $response['status'] = 400;
                        $response['mensaje'] = 'No se pudo Guardar el registro';
                        echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                    }

                }else{

                    $con->autocommit(false);

                    //caso contrario realizamos una insercion nueva
                    $sqlInsert = 'INSERT INTO almacen_producto (id_almacen,id_producto,cantidad) VALUES ("'.$_POST['id_almacen'].'","'.$_POST['id_producto'].'","'.$_POST['cantidad'].'")';
                    $resul = mysqli_query($con,$sqlInsert);

                    $sqlUpdateProducto = 'UPDATE productos SET almacen=almacen + '.$_POST['cantidad'].'  WHERE  id='.$_POST['id_producto'].'';
                    $resUpdate = mysqli_query($con,$sqlUpdateProducto);

                    $sqlHistorial = 'INSERT INTO historial_carga_producto (id_producto,id_almacen,cantidad,fecha_created) VALUES ('.$_POST['id_producto'].','.$_POST['id_almacen'].','.$_POST['cantidad'].',"'.$fecha.'")';
                    $resHistorial = mysqli_query($con,$sqlHistorial);

                        if($resul && $resUpdate && $resHistorial){
                            $con->commit();
                            header("HTTP/1.1 200 OK");
                            $response['status'] = 200;
                            $response['mensaje'] = 'Registro guardado correctamente';
                            echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                        }else{
                            $con->rollback();
                            header("HTTP/1.1 400");
                            $response['status'] = 400;
                            $response['mensaje'] = 'No se pudo Guardar el registro';
                            echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                        }
                }
            break;
            // metodo get 
            case 'GET':
                // para obtener un registro especifico
                if(isset($_GET['id'])){
                    $sql = 'SELECT a.id_almacen,a.nombre_almacen,p.id,p.codigo,p.nombre,r.cantidad FROM almacenes a INNER JOIN almacen_producto r ON a.id_almacen = r.id_almacen INNER JOIN productos p ON p.id = r.id_producto   where a.id_almacen="'.$_GET['id'].'"';
                    $result = mysqli_query($con,$sql);
                    $i=0;
                    while($row = mysqli_fetch_assoc($result)){
                        $response[$i]['id'] = $row['id_almacen'];
                        $response[$i]['nombre_almacen'] = $row['nombre_almacen'];
                        $response[$i]['id_producto'] = $row['id']; 
                        $response[$i]['codigo_producto'] = $row['codigo'];
                        $response[$i]['nombre_producto'] = $row['nombre'];
                        $response[$i]['cantidad_producto'] = $row['cantidad'];
                        $response[$i]['fullname'] = $row['codigo']." ".$row['nombre'];
                        $response[$i]['cantidad'] = 0;
                        $i++;
                    }
                    echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                } else{
                    // es para obtener todos los registros 
                    $sql = 'select *from productos_almacenes';
                    $result = mysqli_query($con,$sql);
                    $i=0;
                    while($row = mysqli_fetch_assoc($result)){
                        $response[$i]['id'] = $row['id_almacen'];
                        $response[$i]['nombre_almacen'] = $row['nombre_almacen'];
                        $response[$i]['id_producto'] = $row['id'];  
                        $response[$i]['codigo_producto'] = $row['codigo'];
                        $response[$i]['nombre_producto'] = $row['nombre'];
                        $response[$i]['cantidad_producto'] = $row['cantidad'];
                        $response[$i]['cantidad'] = 0;
                        $i++;
                    }
                echo  json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                }
            break;

            case 'DELETE':
                    $sql = 'DELETE  from almacen_producto where id='.$_GET['id'].'';
                    $result = mysqli_query($con,$sql);
                    if($result){ 
                        header("HTTP/1.1 200 OK");
                        $response['status'] = 200;
                        $response['mensaje'] = 'Registro se elimino correctamente';
                        echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                    }else{
                        header("HTTP/1.1 400");
                        $response['status'] = 400;
                        $response['mensaje'] = 'No se pudo eliminar el registro';
                        echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                    }
            break;
        }

}else{
    echo "DB FOUND CONNECTED";
}

?>