<?php
include 'vendor/autoload.php';
date_default_timezone_set('America/Mexico_City');
// conexion con base de datos 
include 'conexion/conn.php';

//var_dump($token_access);

use \Firebase\JWT\JWT;

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
    $hora = date('H')+8;
    $fecha_actual = date('Y-m-d H:i:s');
    $fecha_expire = date('Y-m-d '.$hora.':i:s');

    switch($methodApi){
       // metodo post 
       //enviar data
        case 'POST':
            $_POST = json_decode(file_get_contents('php://input'),true);

            $username = $_POST['username'];
            $password = md5($_POST['password']);

            try {
                //consultamos el usuario en la BD
                $sql = 'SELECT u.id_user_bodega,u.usuario_bodega,u.pass_usuario,t.id_tipo_usuario,t.nombre_tipo_usuario FROM app_usuarios_bodega u INNER JOIN app_tipo_usuario_bodega t ON u.tipo_usuario = t.id_tipo_usuario  WHERE u.usuario_bodega="'.$username.'" AND u.pass_usuario ="'.$password.'"';
                $result = mysqli_query($con,$sql);
                $row = mysqli_fetch_assoc($result);

                //COMPROBAMOS SI EL REGISTRO EXISTE
                if($row){

                    //si existe el usuario, comprobar que coincidan los credenciales 
                    if($row['usuario_bodega'] == $_POST['username'] and $row['pass_usuario'] == $password){
                        //si coinciden procedemos a crear la encriptacion del token
                        $fechaActual = date('Y-m-d');   
                        $key = hash('sha256', $fechaActual);
                        $payload = array(
                            "id" => $row['id_user_bodega'],
                            "usuario" => $row['usuario_bodega'],
                            "rol" => $row['nombre_tipo_usuario'],
                        );

                        //codificamos el token
                        $token = JWT::encode($payload, $key, 'HS256');
        
                        $sqlUpdate = 'UPDATE app_usuarios_bodega SET token="'.$token.'",expire_token="'.$fecha_expire.'" WHERE id_user_bodega='.$row['id_user_bodega'].'';
                        $result = mysqli_query($con,$sqlUpdate);
                        
                        //validamos que exista el token y se halla actualizado la tabla de usuarios bien
                        if($result && $token){
                            $token_caja_chica =  date('ndyzw');
                            $token_ventas = date('wzydn');

                            //$sqlToken = 'UPDATE tokens_acceso SET token_caja_chica="'.$token_caja_chica.'",token_venta="'.$token_ventas.'",fecha_actualizacion="'.$fecha_actual.'" WHERE id=1';
                            //$resultado = mysqli_query($con,$sqlToken);
                            
                            header("HTTP/1.1 200 OK");
                            $response['id'] = $row['id_user_bodega'];
                            $response['user'] = $row['usuario_bodega'];
                            $response['id_tipo'] = $row['id_tipo_usuario'];
                            $response['rol'] = $row['nombre_tipo_usuario'];
                            $response['token'] = $token;
                            $response['expire_token'] = $fecha_expire;
                            $response['mensaje'] = 'Usuario logueado correctamente';
                            echo  json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                        }else{
                            header("HTTP/1.1 400");
                            $response['mensaje'] = 'Ocurrio un error intente de nuevo';
                            echo  json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                        }
                    }else{
                        header("HTTP/1.1 400");
                        $response['mensaje'] = 'Ocurrio un error inesperado';
                        echo  json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                    }
                }else{
                    //SI NO EXISTE RESPONDEMOS
                    header("HTTP/1.1 401 Unauthorized");
                    $response['mensaje'] = 'Usuario o Password incorrectos';
                    echo  json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                }

                
            } catch (Exception $e) {
                echo 'Ocurrio un error: ' . $e->getMessage();
            }


        
        break;
        // metodo get 
        case 'GET':
            echo 'get login';
            // $fechaActual = date('Y-m-d');
            // $headers = getallheaders();
            // $headersToken = $headers['Authorization'];
            
            // $tokenParts = explode(' ', $headersToken);

            // if (count($tokenParts) !== 2 || $tokenParts[0] !== 'Bearer') {
            //     // Token inválido, retorna una respuesta de error o redirecciona, según tu caso
            //     echo 'ERROR';
            // }else{

            //     try {
            //         $fechaActual = date('Y-m-d');
            //         $key = hash('sha256', $fechaActual);
    
            //         if (isset($headers['Authorization'])) {
            //             $authorizationHeader = $headers['Authorization'];
            //         }
            //         $token = $tokenParts[1];
            //         $decoded = JWT::decode($token, new Key($key, 'HS256'));
                    
            //         $usuario = $decoded->usuario;
            //         $rol = $decoded->rol;
            //         $id = $decoded->id;
                    
            //         $sql = 'SELECT u.id_user_bodega,u.usuario_bodega,t.nombre_tipo_usuario FROM app_usuarios_bodega u INNER JOIN app_tipo_usuario_bodega t ON u.tipo_usuario = t.id_tipo_usuario  WHERE u.usuario_bodega="'.$usuario.'" AND u.token="'.$token.'"';
            //         $result = mysqli_query($con,$sql);
            //         $row = mysqli_fetch_assoc($result);

            //         if($row){
            //             var_dump($row['usuario_bodega']);
            //         }else{
            //             echo 'Ocurrio un error';
            //         }
                    
                    
            //         // Continúa con el procesamiento de la solicitud
            //     } catch (Exception $e) {
            //         // El token no es válido
            //         echo 'Error al decodificar el token: ' . $e->getMessage();
            //     }
            // }
            
        break;
        case 'PUT':
            $_PUT = json_decode(file_get_contents('php://input'),true);
            $sql = 'UPDATE estados SET nombre_status="'.$_PUT['nombre_status'].'"  WHERE id_status='.$_GET['id'].'';
            $result = mysqli_query($con,$sql);
            if($result)
                    echo 'registro actualizado correctamente';
                else
                    echo 'no se pudo actualizar';
        break;
        case 'DELETE':
            $sql = 'DELETE  from estados where id_status='.$_GET['id'].'';
            $result = mysqli_query($con,$sql);
            if($result)
                echo "registro eliminado satisfactoriamente";
            else
                echo "no se pudo eliminar el registro";
        break;
    }

}else{
    echo "DB FOUND CONNECTED";
}
?>