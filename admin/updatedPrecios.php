<?php
// conexion con base de datos 
include '../conexion/conn.php';
//import middleware
include '../middleware/validarToken.php';

// declarar array para respuestas 
$response = array();

// insertamos cabeceras para permisos 

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type,Authorization, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");
header("Content-Type: JSON");
header('Content-Type: application/json;charset=utf-8'); 



// validamos si hay conexion 
if($con){
    if($token_access['token']){
        $methodApi = $_SERVER['REQUEST_METHOD'];

        if($methodApi == 'PUT'){
            $_PUT = json_decode(file_get_contents('php://input'),true);
            $precio_u = $_PUT['precio_u'];
            $precio_m = $_PUT['precio_m'];
            $precio_c = $_PUT['precio_c'];
            $desc_general = $_PUT['descuento_general'];
            $desc_especial = $_PUT['descuento_especial'];
            $codigo = $_PUT['codigo'];

            //preparar sentencia 
            $sql = 'UPDATE productos SET precio_u=?,precio_m=?,precio_c=?,descuento_precio_unidad=?,descuento_precio_docena=?,descuento_precio_caja=?,descuento_general=?,descuento_especial=? WHERE codigo=?';

            if(!($sentencia = $con->prepare($sql))){
                echo "Falló la preparación: (" . $con->errno . ") " . $con->error;
            }

            //agregar parametros y tipo de datos
            if(!$sentencia->bind_param("sssssssss",$precio_u,$precio_m,$precio_c,$desc_general,$desc_general,$desc_general,$desc_general,$desc_especial,$codigo)){
                echo "Falló la vinculación de parámetros: (" . $sentencia->errno . ") " . $sentencia->error;
            }

                
            if (!$sentencia->execute()) {
                echo "Falló la ejecución: (".$sentencia->errno.") " . $sentencia->error;
                header("HTTP/1.1 400");
                $response['status'] = 400;
                $response['mensaje'] = 'No se pudo actuyalizar el registro';
                echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                $con->close();                
            }else{
                header("HTTP/1.1 200 OK");
                $response['status'] = 200;
                $response['mensaje'] = 'Precios actualizados correctamente';
                echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                $con->close();
            } 
        }
    }else{
        echo $token_access['validate'];
    }

        
    //echo "Informacion".file_get_contents('php://input');

}else{
    echo "DB FOUND CONNECTED";
}
?>