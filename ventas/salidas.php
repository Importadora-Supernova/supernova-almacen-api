<?php
// conexion con base de datos 
include '../conexion/conn.php';
include '../middleware/midleware.php';
date_default_timezone_set('America/Mexico_City');
//incluir middleware


// declarar array para respuestas 
$response = array();

// insertamos cabeceras para permisos 

header('Access-Control-Allow-Origin:*');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept,Authorization, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");
header("Content-Type: JSON");
header('Content-Type: application/json;charset=utf-8'); 

$fecha = date('Y-m-d');
$fecha_actual = date('Y-m-d H:i:s');

// validamos si hay conexion 
if($con){
        $methodApi = $_SERVER['REQUEST_METHOD'];
        if($validate === 'validado'){
            
            if($methodApi == 'GET'){
                if(isset($_GET['caja'])){
                    $sql = 'SELECT  *FROM caja_chica WHERE  fecha LIKE "'.$fecha.'%"';
                    $result = mysqli_query($con,$sql);
                    $i=0;
                    while($row = mysqli_fetch_assoc($result)){
                        $response[$i]['id'] =  $row['id'];
                        $response[$i]['fecha'] =  $row['fecha'];
                        $response[$i]['monto'] =  $row['monto'];
                        $response[$i]['motivo'] =  $row['motivo'];
                        $i++;
                    }
                    header("HTTP/1.1 200 OK");
                    echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT); 
                }else if(isset($_GET['fecha'])){
                    $sql = 'SELECT  *FROM caja_chica WHERE  fecha LIKE "'.$_GET['fecha'].'%"';
                    $result = mysqli_query($con,$sql);
                    $i=0;
                    while($row = mysqli_fetch_assoc($result)){
                        $response[$i]['id'] =  $row['id'];
                        $response[$i]['fecha'] =  $row['fecha'];
                        $response[$i]['monto'] =  $row['monto'];
                        $response[$i]['motivo'] =  $row['motivo'];
                        $i++;
                    }
                    header("HTTP/1.1 200 OK");
                    echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT); 
                }else if(isset($_GET['fecha_salida'])){
                    $sql = 'SELECT  *FROM salida_ventas WHERE  fecha LIKE "'.$_GET['fecha_salida'].'%"';
                    $result = mysqli_query($con,$sql);
                    $i=0;
                    while($row = mysqli_fetch_assoc($result)){
                        $response[$i]['id'] =  $row['id'];
                        $response[$i]['fecha'] =  $row['fecha'];
                        $response[$i]['cantidad'] =  $row['cantidad'];
                        $response[$i]['motivo'] =  $row['motivo'];
                        $i++;
                    }
                    header("HTTP/1.1 200 OK");
                    echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT); 
                }else{
                   $sql = 'SELECT  *FROM salida_ventas WHERE  fecha LIKE "'.$fecha.'%"';
                    $result = mysqli_query($con,$sql);
                    $i=0;
                    while($row = mysqli_fetch_assoc($result)){
                        $response[$i]['id'] =  $row['id'];
                        $response[$i]['fecha'] =  $row['fecha'];
                        $response[$i]['cantidad'] =  $row['cantidad'];
                        $response[$i]['motivo'] =  $row['motivo'];
                        $i++;
                    }
                    header("HTTP/1.1 200 OK");
                    echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT); 
                }
                
            }
            
            if($methodApi == 'POST'){
                $_POST = json_decode(file_get_contents('php://input'),true);
                $sqlInsert = 'INSERT INTO salida_ventas (fecha,cantidad,motivo)  VALUES ("'.$fecha_actual.'","'.$_POST['monto'].'","'.$_POST['motivo'].'")';
                $result = mysqli_query($con,$sqlInsert);

                if($result){
                    header("HTTP/1.1 200");
                    $response['mensaje'] = 'Salida registrada exitosamente';
                    echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                }else{
                    header("HTTP/1.1 400");
                    $response['mensaje'] = 'No se puedo registrar la salida';
                    echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                }
            }

            if($methodApi == 'DELETE'){
                $sql = 'DELETE FROM salida_ventas WHERE id='.$_GET['id'].'';
                $resultDelete = mysqli_query($con,$sql);

                if($resultDelete){
                    header("HTTP/1.1 200");
                    $response['mensaje'] = 'Salida eliminada correctamente';
                    echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                }else{
                    header("HTTP/1.1 400");
                    $response['mensaje'] = 'No se puedo eliminar la salida';
                    echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                }
            }
        }else{
            header("HTTP/1.1 201");
            $response['status'] = 401;
            $response['mensaje'] = 'Token '.$validate;
            echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
            
        }
    //echo "Informacion".file_get_contents('php://input');

}else{
    echo "DB FOUND CONNECTED";
}
?>