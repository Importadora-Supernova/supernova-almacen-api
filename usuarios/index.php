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
             //
            break;
            // metodo get 
            case 'GET':
                if(isset($_GET['id'])){
                    // para obtener un registro especifico
                    $sqlPagados = 'SELECT * FROM usuario WHERE id='.$_GET['id'].'';
                    $resultPagados = mysqli_query($con,$sqlPagados);
                    $i=0;
                    while($row = mysqli_fetch_assoc($resultPagados)){
                        $response['id'] = $row['id'];
                        $response['fullName'] = $row['nombre'].' '.$row['apellido'];
                        $response['nombre'] = $row['nombre'];
                        $response['apellido'] = $row['apellido'];
                        $response['direccion'] = $row['direccion'];
                        $response['colonia'] = $row['colonia'];
                        $response['ciudad'] = $row['ciudad'];
                        $response['estado'] = $row['estado'];
                        $response['codigop'] = $row['codigop'];
                        $response['telefono'] = $row['telefono'];
                        $response['correo'] = $row['correo'];
                        $response['rfc'] = $row['rfc'];
                        $response['orden'] = $row['orden'];
                        $i++;
                    }
                echo  json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                }else if(isset($_GET['especial'])){
                    $sqlPagados = 'SELECT * FROM usuario WHERE especial = "si"';
                    $resultPagados = mysqli_query($con,$sqlPagados);
                    $i=0;
                    while($row = mysqli_fetch_assoc($resultPagados)){
                        $response[$i]['id'] = $row['id'];
                        $response[$i]['fullName'] = $row['nombre'].' '.$row['apellido'];
                        $response[$i]['nombre'] = $row['nombre'];
                        $response[$i]['apellido'] = $row['apellido'];
                        $response[$i]['direccion'] = $row['direccion'];
                        $response[$i]['colonia'] = $row['colonia'];
                        $response[$i]['ciudad'] = $row['ciudad'];
                        $response[$i]['estado'] = $row['estado'];
                        $response[$i]['codigop'] = $row['codigop'];
                        $response[$i]['telefono'] = $row['telefono'];
                        $response[$i]['correo'] = $row['correo'];
                        $response[$i]['rfc'] = $row['rfc'];
                        $response[$i]['orden'] = $row['orden'];
                        $i++;
                    }
                    echo  json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                }else{
                    $sqlPagados = 'SELECT * FROM usuario';
                    $resultPagados = mysqli_query($con,$sqlPagados);
                    $i=0;
                    while($row = mysqli_fetch_assoc($resultPagados)){
                        $response[$i]['id'] = $row['id'];
                        $response[$i]['fullName'] = $row['nombre'].' '.$row['apellido'];
                        $response[$i]['nombre'] = $row['nombre'];
                        $response[$i]['apellido'] = $row['apellido'];
                        $response[$i]['direccion'] = $row['direccion'];
                        $response[$i]['colonia'] = $row['colonia'];
                        $response[$i]['ciudad'] = $row['ciudad'];
                        $response[$i]['estado'] = $row['estado'];
                        $response[$i]['codigop'] = $row['codigop'];
                        $response[$i]['telefono'] = $row['telefono'];
                        $response[$i]['correo'] = $row['correo'];
                        $response[$i]['rfc'] = $row['rfc'];
                        $response[$i]['orden'] = $row['orden'];
                        $i++;
                    }
                    echo  json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT); 
                }
            break;
            case 'PUT':
                $_PUT = json_decode(file_get_contents('php://input'),true);
                $update = 'UPDATE app_tipo_usuario_ventas SET nombre_tipo_usuario="'.$_PUT['nombre_tipo_usuario'].'", estatus_tipo='.$_PUT['estatus'].' WHERE id_tipo_usuario='.$_GET['id'].'';
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
            break;

        }
    //echo "Informacion".file_get_contents('php://input');

}else{
    echo "DB FOUND CONNECTED";
}
?>  