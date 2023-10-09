<?php
// conexion con base de datos 
include '../conexion/conn.php';
//import middleware
include '../middleware/validarToken.php';

// declarar array para respuestas 
$response = array();


// insertamos cabeceras para permisos 

header('Access-Control-Allow-Origin:*');
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
            if(isset($_GET['codigo'])){
                // es para obtener todos los registros  por codigo
                $sql = 'SELECT *FROM productos where codigo="'.$_GET['codigo'].'"';
                $result = mysqli_query($con,$sql);
                $i=0;
                while($row = mysqli_fetch_assoc($result)){
                    $response[$i]['id'] = $row['id'];
                    $response[$i]['nombre'] = $row['nombre'];
                    $response[$i]['codigo'] = $row['codigo'];
                    $response[$i]['almacen'] = $row['almacen'];
                    $response[$i]['inventario'] = $row['inventario'];
                    $response[$i]['cantidad'] = null;
                    $response[$i]['precio'] = $row['preciou'];
                    $i++;
                }
                echo  json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
            }else{
                if(isset($_GET['id'])){
                    $sql = 'SELECT *FROM productos  where id="'.$_GET['id'].'"';
                    $result = mysqli_query($con,$sql);
                    $i=0;
                    while($row = mysqli_fetch_assoc($result)){
                        $response['id'] = $row['id'];
                        $response['nombre'] = $row['nombre'];
                        $response['codigo'] = $row['codigo'];
                        $response['almacen'] = $row['almacen'];
                        $i++;
                    }
                    echo json_encode($response,JSON_PRETTY_PRINT);
                } else{
                    if(isset($_GET['total'])){
                        // es para obtener todos los registros 
                        $sql = 'SELECT p.id,p.codigo,p.nombre,p.estatus,p.preciou,p.preciom,p.precioc,p.precio_costo,p.precio_yuan,p.almacen,p.descuento_general,p.descuento_especial,p.descuento,p.descuento_precio_docena,p.promocion,c.nombre_categoria as categoria,s.id_subcategoria,s.nombre_subcategoria as subcategoria FROM productos p LEFT JOIN admin_subcategorias s ON p.sub_categoria = s.id_subcategoria LEFT JOIN admin_categorias c ON s.id_categoria = c.id_categoria';
                        $result = mysqli_query($con,$sql);
                        $i=0;
                        while($row = mysqli_fetch_assoc($result)){
                            $response[$i]['id'] = $row['id'];
                            $response[$i]['nombre'] = $row['nombre'];
                            $response[$i]['codigo'] = $row['codigo'];
                            $response[$i]['almacen'] = $row['almacen'];
                            $response[$i]['categoria'] = $row['categoria'];
                            $response[$i]['preciou'] = $row['preciou'];
                            $response[$i]['preciom'] = $row['preciom'];
                            $response[$i]['precioc'] = $row['precioc'];
                            $response[$i]['precio_yuan'] = $row['precio_yuan'];
                            $response[$i]['precio_costo'] = $row['precio_costo'];
                            $response[$i]['id_subcategoria'] = $row['id_subcategoria'];
                            $response[$i]['descuento'] = $row['descuento'];
                            $response[$i]['promocion'] = $row['promocion'];
                            $response[$i]['descuento_precio_docena'] = $row['descuento_precio_docena'];
                            $response[$i]['subcategoria'] = $row['subcategoria'];
                            $response[$i]['descuento_general'] = $row['descuento_general'];
                            $response[$i]['descuento_especial'] = $row['descuento_especial'];
                            if($row['estatus'] == "0"){
                                $response[$i]['estatus'] = false;
                            }else{
                                $response[$i]['estatus'] = true;
                            }
                            $response[$i]['fullname'] = $row['codigo']." ".$row['nombre'];
                            $i++;
                        }
                        echo  json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT); 
                    }else{
                        // es para obtener todos los registros 
                        $sql = 'SELECT id,codigo,nombre,almacen FROM productos GROUP BY codigo';
                        $result = mysqli_query($con,$sql);
                        $i=0;
                        while($row = mysqli_fetch_assoc($result)){
                            $response[$i]['id'] = $row['id'];
                            $response[$i]['nombre'] = $row['nombre'];
                            $response[$i]['codigo'] = $row['codigo'];
                            $response[$i]['almacen'] = $row['almacen'];
                            $response[$i]['fullname'] = $row['codigo']." ".$row['nombre'];
                            $i++;
                        }
                        echo  json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT); 
                    }
                    
                }
            }
        }

        if($methodApi == 'POST'){
            $_POST = json_decode(file_get_contents('php://input'),true);
            $sqlUpdateProducto = 'UPDATE productos SET estatus='.$_POST['estatus'].' WHERE  id='.$_POST['id_producto'].'';
            $resUpdate = mysqli_query($con,$sqlUpdateProducto);

            if($resUpdate){
                header("HTTP/1.1 200 OK");
                $response['status'] = 200;
                $response['mensaje'] = 'Registro actualizado correctamente';
                echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
            }else{
                header("HTTP/1.1 400");
                $response['status'] = 400;
                $response['mensaje'] = 'No se pudo Guardar el registro';
                echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
            }
        }

        if($methodApi == 'PUT'){
            $_PUT = json_decode(file_get_contents('php://input'),true);

            if($_PUT['type'] == 'categoria'){
                $sqlUpdateSub =  'UPDATE productos SET sub_categoria='.$_PUT['id_subcategoria'].' WHERE id='.$_GET['id'].'';
                $resUpdate = mysqli_query($con,$sqlUpdateSub);

                if($resUpdate){
                    header("HTTP/1.1 200 OK");
                    $response['status'] = 200;
                    $response['mensaje'] = 'Registro actualizado correctamente';
                    echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                }else{
                    header("HTTP/1.1 400");
                    $response['status'] = 400;
                    $response['mensaje'] = 'No se pudo acualizar el registro';
                    echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                }
            }else{
                $sqlUpdatedEstatus = 'UPDATE productos SET estatus="'.$_PUT['estatus'].'" WHERE id='.$_GET['id'].'';
                $resultUpdated = mysqli_query($con,$sqlUpdatedEstatus);

                if($resultUpdated){
                    header("HTTP/1.1 200 OK");
                    $response['status'] = 200;
                    $response['mensaje'] = 'Registro actualizado correctamente';
                    echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                }else{
                    header("HTTP/1.1 400");
                    $response['status'] = 400;
                    $response['mensaje'] = 'No se pudo acualizar el registro';
                    echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                }

            }
            
        }
    }else{
        echo $token_access['validate'];
    }
}else{
    echo "DB FOUND CONNECTED";
}
?>