<?php
// conexion con base de datos 
include 'conexion/conn.php';
date_default_timezone_set('America/Mexico_City');

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
   $methodApi = $_SERVER['REQUEST_METHOD'];
   
    switch($methodApi){
        // metodo post 
        case 'POST':
            
            $_POST = json_decode(file_get_contents('php://input'),true);
            $metodo = $_POST['metodo'];

            if($metodo == 'garantia'){
                $fecha = date('Y-m-d H:i:s');
    
                $con->autocommit(false);
    
                $sqlInsert = 'INSERT INTO almacen_garantia(id_producto,codigo_producto,nombre_producto,orden,tienda,cantidad,fecha_created) VALUES ('.$_POST['id_producto'].',"'.$_POST['codigo'].'","'.$_POST['nombre'].'","'.$_POST['orden'].'","","'.$_POST['cantidad'].'","'.$fecha.'")';
                $resInsert = mysqli_query($con,$sqlInsert);
    
                $sqlUpdate = 'UPDATE registro_usuario SET cantidad=cantidad-'.$_POST['cantidad'].' WHERE id_producto='.$_POST['id_producto'].' AND orden="'.$_POST['orden'].'"';
                $resUpdate = mysqli_query($con,$sqlUpdate);
    
    
                if($resInsert and $resUpdate){
                    $con->commit();
                    header("HTTP/1.1 200 OK");
                    $response['mensaje'] = 'Producto transferido exitosamente';
                    $response['status'] = 200;
                    echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                }else{
                    $con->rollback();
                    header("HTTP/1.1 400");
                    $response['mensaje'] = 'Ocurrio un error no se pudo guardar el registro';
                    $response['status'] = 400;
                    echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                }
            }else{
                $con->autocommit(false);
                $sqlSelect = 'SELECT * FROM productos_almacenes WHERE id='.$_POST['id_producto'].' AND id_almacen=1';
                $resultSelect = mysqli_query($con,$sqlSelect);
                $fill = mysqli_fetch_assoc($resultSelect);
                
                if($fill){
                    $sqlUpdateAlmacen = 'UPDATE almacen_producto SET cantidad=cantidad+'.$_POST['cantidad'].' WHERE id_producto='.$_POST['id_producto'].' AND id_almacen=15';
                    $resUpdate = mysqli_query($con,$sqlUpdateAlmacen);
                    
                    $sqlUpdateProductos = 'UPDATE productos SET almacen=almacen+'.$_POST['cantidad'].' WHERE id='.$_POST['id_producto'].''; 
                    $resUpdateProductos = mysqli_query($con,$sqlUpdateProductos);

                    if($resUpdate and $resUpdateProductos){
                        $con->commit();
                        header("HTTP/1.1 200 OK");
                        $response['mensaje'] = 'Producto transferido exitosamente';
                        $response['status'] = 200;
                        echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                    }else{
                        $con->rollback();
                        header("HTTP/1.1 400");
                        $response['mensaje'] = 'Ocurrio un error no se pudo guardar el registro';
                        $response['status'] = 400;
                        echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                    }
                }else{
                    $sqlInsertAlmacen1 = 'INSERT INTO almacen_producto(id_almacen,id_producto,cantidad) VALUES (15,'.$_POST['id_producto'].','.$_POST['cantidad'].')';
                    $resInsert = mysqli_query($con,$sqlInsertAlmacen1);

                    $sqlUpdateProductos = 'UPDATE productos SET almacen=almacen+'.$_POST['cantidad'].' WHERE id='.$_POST['id_producto'].''; 
                    $resUpdateProductos = mysqli_query($con,$sqlUpdateProductos);

                    if($resInsert and $resUpdateProductos){
                        $con->commit();
                        header("HTTP/1.1 200 OK");
                        $response['mensaje'] = 'Producto transferido exitosamente';
                        $response['status'] = 200;
                        echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                    }else{
                        $con->rollback();
                        header("HTTP/1.1 400");
                        $response['mensaje'] = 'Ocurrio un error no se pudo guardar el registro';
                        $response['status'] = 400;
                        echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                    }

                }
            }

        

        break;

        case 'GET':
            if(isset($_GET['orden'])){
                // es para obtener todos los registros  por codigo
                $sql = 'SELECT p.id,p.nombre,p.codigo,r.precio,r.cantidad,r.fecha,f.orden FROM productos p INNER JOIN registro_usuario r ON p.id = r.id_producto INNER JOIN folios f ON r.orden = f.orden where r.orden="'.$_GET['orden'].'"';
                $result = mysqli_query($con,$sql);
                $i=0;
                while($row = mysqli_fetch_assoc($result)){
                    $response[$i]['id'] = $row['id'];
                    $response[$i]['nombre'] = $row['nombre'];
                    $response[$i]['codigo'] = $row['codigo'];
                    $response[$i]['precio'] = $row['precio'];
                    $response[$i]['cantidad'] = $row['cantidad'];
                    $response[$i]['fecha'] = $row['fecha'];
                    $response[$i]['orden'] = $row['orden'];
                    $i++;
                }
               echo  json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
           }else{
             
               echo 'construccion';
           }
        break;

   }
}else{
    echo "DB FOUND CONNECTED";
}
?>