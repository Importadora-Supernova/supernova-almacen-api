<?php
// conexion con base de datos 
include 'conexion/conn.php';
//incluir middleware
require_once 'class/usuario.class.php';
// declarar array para respuestas 
$response = array();
$productos = array();
$almacenes = array();

$user = new Users();

// insertamos cabeceras para permisos 

header('Access-Control-Allow-Origin:*');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept,Authorization, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");
header("Content-Type: JSON");
header('Content-Type: application/json;charset=utf-8'); 



// validamos si hay conexion 
if($con){

    $methodApi = $_SERVER['REQUEST_METHOD'];
        if($methodApi == 'GET'){

            try{
                $sql = 'SELECT a.id,a.id_producto,a.cantidad,p.codigo,p.nombre FROM almacen_producto a INNER JOIN productos p ON a.id_producto = p.id WHERE a.id_almacen=15 AND a.cantidad<0 AND a.buscar=1;'; 
                $result = mysqli_query($con,$sql);
                $i=0;
                while($row = mysqli_fetch_assoc($result)){
                    $productos[$i]['id'] = $row['id'];
                    $productos[$i]['id_producto'] = $row['id_producto'];
                    $productos[$i]['cantidad'] = $row['cantidad'];
                    $productos[$i]['codigo'] = $row['codigo'];
                    $productos[$i]['nombre'] = $row['nombre'];
    
                    $id = intval($row['id_producto']);
                    $sqlImg = 'SELECT a.id,a.id_almacen,a.cantidad,d.nombre_almacen FROM almacen_producto a INNER JOIN almacenes d ON a.id_almacen = d.id_almacen WHERE a.id_producto='.$id.' AND a.cantidad > 0';
                    $result2 = mysqli_query($con,$sqlImg);
                    $j=0;
                    while($fill = mysqli_fetch_assoc($result2)){
                        $almacenes[$j]['id'] = $fill['id'];
                        $almacenes[$j]['id_almacen'] = $fill['id_almacen'];
                        $almacenes[$j]['nombre_almacen'] = $fill['nombre_almacen'];
                        $almacenes[$j]['cantidad'] = $fill['cantidad'];
                        $j++;
                    }
                    $productos[$i]['almacenes'] = $almacenes;
                    $almacenes = [];
                    $i++;
                }
                $response['productos'] = $productos;
                header("HTTP/1.1 200");
                echo  json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT); 
            }catch(Exception $e){
                header("HTTP/1.1 400");
                $response['mensaje'] = $e->getMessage();
                echo  json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT); 
            }


        }

        if($methodApi == 'POST'){
            try{
                $_POST = json_decode(file_get_contents('php://input'),true);

                $result = $user->createUser($con,$_POST);
                if($result){
                    header("HTTP/1.1 200 OK");
                    $response['status'] = 200;
                    $response['mensaje'] = 'Se creo el usuario';
                    echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT); 
                }else{
                    header("HTTP/1.1 400");
                    $response['status'] = 400;
                    $response['mensaje'] = 'No se pudo completar el proceso';
                    echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                }

            }catch(Exception $e){
                header("HTTP/1.1 401");
                $response['status'] = 401;
                $response['mensaje'] = $e->getMessage();
                echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
            }
        }
}else{
    echo "DB FOUND CONNECTED";
}
?>