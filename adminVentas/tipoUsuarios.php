<?php
// conexion con base de datos 
include '../conexion/conn.php';
//incluir middleware
include '../middleware/midleware.php';

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
    if($validate === 'validado'){
        $methodApi = $_SERVER['REQUEST_METHOD'];

        switch($methodApi){
            // metodo post 
            case 'POST':
            $_POST = json_decode(file_get_contents('php://input'),true);
            $sqlInsert = 'INSERT INTO app_tipo_usuario_ventas (nombre_tipo_usuario,estatus_tipo,fecha_created) VALUES ("'.$_POST['nombre_tipo_usuario'].'","'.$_POST['estatus'].'","'.$fecha.'")';
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
                 $sql = 'SELECT *FROM app_tipo_usuario_ventas  WHERE id_tipo_usuario='.$_GET['id'].'';
                 $result = mysqli_query($con,$sql);
                 $i=0;
                 while($row = mysqli_fetch_assoc($result)){
                    $response['id'] = $row['id_tipo_usuario'];
                    $response['nombre_tipo_usuario'] = $row['nombre_tipo_usuario'];
                    $response['estatus_tipo'] = $row['estatus_tipo'] == "1" ? true : false;
                    $response['fecha_created'] = $row['fecha_created'];
                     $i++;
                 }

                 echo  json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
              } else if(isset($_GET['activos'])){
                    $sqlPagados = 'SELECT * FROM app_tipo_usuario_ventas WHERE estatus_tipo="1"';
                    $resultPagados = mysqli_query($con,$sqlPagados);
                    $i=0;
                    while($row = mysqli_fetch_assoc($resultPagados)){
                        $response[$i]['id'] = $row['id_tipo_usuario'];
                        $response[$i]['nombre_tipo_usuario'] = $row['nombre_tipo_usuario'];
                        $i++;
                    }
                    echo  json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);

              } else {
                    $sqlPagados = 'SELECT * FROM app_tipo_usuario_ventas';
                    $resultPagados = mysqli_query($con,$sqlPagados);
                    $i=0;
                    while($row = mysqli_fetch_assoc($resultPagados)){
                        $response[$i]['id'] = $row['id_tipo_usuario'];
                        $response[$i]['nombre_tipo_usuario'] = $row['nombre_tipo_usuario'];
                        $response[$i]['estatus_tipo'] = $row['estatus_tipo'] == "1" ? true : false;
                        $response[$i]['fecha_created'] = $row['fecha_created'];
                        $i++;
                    }

                    echo  json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                 }
            break;
            case 'PUT':
                $_PUT = json_decode(file_get_contents('php://input'),true);
                $update = 'UPDATE app_tipo_usuario_ventas SET nombre_tipo_usuario="'.$_PUT['nombre_tipo_usuario'].'", estatus_tipo='.$_PUT['estatus'].' WHERE id_tipo_usuario='.$_GET['id'].'';
                $resUpdate = mysqli_query($con,$update);
                $resCall = null;
                $idCall = $_PUT['id'];
                if($_PUT['estatus_tipo'] == "0"){
                    $sql = "CALL actualizarUsuario($idCall);";
                    $resCall = mysqli_query($con,$sql);

                }
                

                if($resUpdate){
                    header("HTTP/1.1 200 OK");
                    $response['status'] = 200;
                    $response['mensaje'] = 'Registro actulizado correctamente';
                    $response['call'] = $resCall;
                    echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                }else{
                    header("HTTP/1.1 400");
                    $response['status'] = 400;
                    $response['mensaje'] = 'No se pudo actualizar el registro';
                    echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                }
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