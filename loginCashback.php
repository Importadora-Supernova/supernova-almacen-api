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
            $password = $_POST['password'];

            try {
                //consultamos el usuario en la BD
                $sql = 'SELECT u.id,u.nombre,u.apellido,u.orden,c.email,c.password,c.type,c.cashback,c.orden,c.compra_user FROM usuario u INNER JOIN usuarios_cashback c ON u.id = c.id_usuario  WHERE c.email="'.$username.'" AND c.password ="'.$password.'"';
                $result = mysqli_query($con,$sql);
                $row = mysqli_fetch_assoc($result);

                //COMPROBAMOS SI EL REGISTRO EXISTE
                if($row){

                    if($row['compra_user'] == 0){
                        //si existe el usuario, comprobar que coincidan los credenciales 
                        if($row['email'] == $_POST['username'] and $row['password'] == $password){
                            //si coinciden procedemos a crear la encriptacion del token
                            $fechaActual = date('Y-m-d');   
                            $key = hash('sha256', $fechaActual);
                            $payload = array(
                                "id" => $row['id'],
                                "usuario" => $row['email'],
                                "rol" => $row['type'],
                            );

                            //codificamos el token
                            $token = JWT::encode($payload, $key, 'HS256');
            
                            $sqlUpdate = 'UPDATE usuarios_cashback SET token="'.$token.'" WHERE id_usuario='.$row['id'].'';
                            $result = mysqli_query($con,$sqlUpdate);
                            
                            //validamos que exista el token y se halla actualizado la tabla de usuarios bien
                            if($result && $token){
                                
                                header("HTTP/1.1 200 OK");
                                $response['id'] = $row['id'];
                                $response['user'] = $row['email'];
                                $response['type'] = $row['type'] == 1 ? 'admin' : 'user';
                                $response['cashback'] = $row['cashback'];
                                $response['nombre'] = $row['nombre'];
                                $response['apellido'] = $row['apellido'];
                                $response['orden'] = $row['orden'];
                                $response['token'] = $token;
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
                        //USUARIO YA REALIZO COMPRA
                        header("HTTP/1.1 401 Unauthorized");
                        $response['mensaje'] = 'Usuario ya realizo compra cashback';
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
        
        default:
        break;
    }

}else{
    echo "DB FOUND CONNECTED";
}
?>