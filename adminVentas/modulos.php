<?php
// conexion con base de datos 
include '../conexion/conn.php';
//incluir middleware
include '../middleware/midleware.php';

// declarar array para respuestas 
$response = array();

// insertamos cabeceras para permisos 

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept,Authorization, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");
header("Content-Type: JSON");
header('Content-Type: application/json;charset=utf-8'); 



// validamos si hay conexion 
if($con){
    if($validate){

        $methodApi = $_SERVER['REQUEST_METHOD'];

        switch($methodApi){
            // metodo post 
            case 'POST':
                $_POST = json_decode(file_get_contents('php://input'),true);
                $sqlInsert = 'INSERT INTO app_modulos_ventas (nombre_modulo,ruta,icono,estatus_modulo) VALUE ("'.$_POST['nombre_modulo'].'","'.$_POST['ruta'].'","'.$_POST['icono_modulo'].'","'.$_POST['estatus_modulo'].'")';
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
             //
            break;
            // metodo get 
            case 'GET':
             // para obtener un registro especifico
            if(isset($_GET['id'])){ 
                 $sql = 'SELECT *FROM app_modulos_ventas  WHERE id_modulo='.$_GET['id'].'';
                $result = mysqli_query($con,$sql);
                $i=0;
                while($row = mysqli_fetch_assoc($result)){
                    $response['id'] = $row['id_modulo'];
                    $response['nombre_modulo'] = $row['nombre_modulo'];
                    $response['ruta'] = $row['ruta'];
                    $response['icono'] = $row['icono'];
                    $response['estatus_modulo'] = $row['estatus_modulo'] == "1" ? true : false;
                    $response['posicion'] = $row['posicion'];
                    $i++;
                }

                    echo  json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                }else if(isset($_GET['all'])){
                    $sqlPagados = 'SELECT * FROM app_modulos_ventas';
                    $resultPagados = mysqli_query($con,$sqlPagados);
                    $i=0;
                    while($row = mysqli_fetch_assoc($resultPagados)){
                        $response[$i]['id'] = $row['id_modulo'];
                        $response[$i]['nombre_modulo'] = $row['nombre_modulo'];
                        $response[$i]['ruta'] = $row['ruta'];
                        $response[$i]['icono'] = $row['icono'];
                        $response[$i]['estatus_modulo'] = $row['estatus_modulo'] == "1" ? true : false;
                        $response[$i]['posicion'] = $row['posicion'];
                        $response[$i]['addView'] = false;  
                        $response[$i]['addEdit'] = false;   
                        $i++;
                    }

                    echo  json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                }else {
                    $sqlPagados = 'SELECT * FROM app_modulos_ventas WHERE estatus_modulo="1"';
                    $resultPagados = mysqli_query($con,$sqlPagados);
                    $i=0;
                    while($row = mysqli_fetch_assoc($resultPagados)){
                        $response[$i]['id'] = $row['id_modulo'];
                        $response[$i]['nombre_modulo'] = $row['nombre_modulo'];
                        $response[$i]['ruta'] = $row['ruta'];
                        $response[$i]['icono'] = $row['icono'];
                        $response[$i]['estatus_modulo'] = $row['estatus_modulo'] == "1" ? true : false;
                        $response[$i]['posicion'] = $row['posicion'];
                        $response[$i]['addView'] = false;  
                        $response[$i]['addEdit'] = false;   
                        $i++;
                    }

                    echo  json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                }
            break;
            case 'PUT':
                $_PUT = json_decode(file_get_contents('php://input'),true);
                $sqlUpdate = 'UPDATE  app_modulos_ventas SET nombre_modulo="'.$_PUT['nombre_modulo'].'", ruta="'.$_PUT['ruta'].'",icono="'.$_PUT['icono_modulo'].'",estatus_modulo="'.$_PUT['estatus_modulo'].'" WHERE id_modulo='.$_GET['id'].'';
                $result = mysqli_query($con,$sqlUpdate);

                if($result){
                    header("HTTP/1.1 200 OK");
                    $response['status'] = 200;
                    $response['mensaje'] = 'Registro actualizado correctamente';
                    echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                }else{
                    header("HTTP/1.1 400");
                    $response['status'] = 400;
                    $response['mensaje'] = 'No se pudo actualizar el registro';
                    echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                }
             //
            break;

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