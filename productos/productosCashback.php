<?php
// conexion con base de datos 
include '../conexion/conn.php';
// declarar array para respuestas 
$response = array();
$producto = array();

// insertamos cabeceras para permisos 

header('Access-Control-Allow-Origin:*');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");
header("Content-Type: JSON");
header('Content-Type: application/json;charset=utf-8'); 



// validamos si hay conexion 
if($con){

    $methodApi = $_SERVER['REQUEST_METHOD'];

        if($methodApi == 'GET'){
            if(isset($_GET['admin'])){
                $sql = 'SELECT p.id, p.codigo, p.nombre, p.preciou, p.precio_costo, p.almacen FROM productos p INNER JOIN productos_cashback c ON p.id = c.id_producto';
                $stmt = $con->prepare($sql);
                $stmt->execute();
                $result   = $stmt->get_result();
                $response = $result->fetch_all(MYSQLI_ASSOC);
                echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
            }else{
                $sql = 'SELECT p.id, p.codigo, p.nombre, p.preciou, p.almacen,p.descripcion FROM productos p INNER JOIN productos_cashback c ON p.id = c.id_producto';
                $stmt = $con->prepare($sql);
                $stmt->execute();
                $result   = $stmt->get_result();
                $data = $result->fetch_all(MYSQLI_ASSOC);

                $i =0;
                foreach($data as $row){
                    $producto[$i]['id'] = $row['id'];
                    $producto[$i]['codigo'] = $row['codigo'];
                    $producto[$i]['nombre'] = $row['nombre'];
                    $producto[$i]['precio'] = $row['preciou'];
                    $producto[$i]['almacen'] = $row['almacen'];
                    $producto[$i]['descripcion'] = $row['descripcion'];
                    $resultado = mysqli_query($con,'SELECT  ruta,a FROM img WHERE id_producto='.$row['id'].'');
        
                    $j = 0;
                    while($fill = mysqli_fetch_assoc($resultado)){
                        $images[$j]['ruta'] = $fill['ruta'];
                        $images[$j]['imagen'] = $fill['a'];
                        $j++;
                    }
                    $producto[$i]['imagenes'] = $images;
                    $i++;
                }
                echo json_encode($producto,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
            }
           
        }

        if($methodApi == 'POST'){

            $con->autocommit(false);

            try{
                $_POST = json_decode(file_get_contents('php://input'),true);
                $codigo = $_POST['codigo'];
    
                $sql = 'SELECT id,almacen FROM `productos` WHERE  codigo=?';
                $stmt = $con->prepare($sql);
                $stmt->bind_param('s',$codigo);
                $stmt->execute();
                $result = $stmt->get_result();
                $data = $result->fetch_all(MYSQLI_ASSOC);
                $band = true;
                foreach($data as $row){
                    $almacen = intval($row['almacen']);
                    $sqlInsert = 'INSERT INTO productos_cashback (id_producto,stock) VALUES ('.$row['id'].','.$almacen.')';
                    $resultInsert = mysqli_query($con,$sqlInsert);
                    if($resultInsert == 1){
                        $band = true;
                    }else{
                        $band = false;
                        break;
                    }
                }
                if($band){
                    $con->commit();
                    header("HTTP/1.1 200 OK");
                    $response['status'] = 200;
                    $response['mensaje'] = 'Se registro correctamente';
                    echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
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

        if($methodApi == 'DELETE'){
            $sql = 'DELETE FROM productos_cashback WHERE id_producto='.$_GET['id'].'';
            $result = mysqli_query($con,$sql);

            if($result || $result == 1){
                header("HTTP/1.1 200 OK");
                $response['status'] = 200;
                $response['mensaje'] = 'Se elimino correctamente';
                echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
            }
        }
  
}else{
    echo "DB FOUND CONNECTED";
}
?>