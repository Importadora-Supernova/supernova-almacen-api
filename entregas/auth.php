<?php
// -------------------- HEADERS PARA CORS --------------------
header('Access-Control-Allow-Origin: *'); // Cambia "*" por dominio en producción
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Authorization, Content-Type');
header('Content-Type: application/json');

include '../conexion/conn.php';
include '../vendor/autoload.php';
date_default_timezone_set('America/Mexico_City');
// conexion con base de datos 


//var_dump($token_access);

use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

// declarar array para respuestas 
$response = array();

// validamos si hay conexion 
if($con){
    
    $methodApi = $_SERVER['REQUEST_METHOD'];
    $hora = date('H')+8;
    $fecha_actual = date('Y-m-d H:i:s');
    $fecha_expire = date('Y-m-d '.$hora.':i:s');

    /**
     * Funcion para actualizar el token
     * @param $con
     * @param $id
     * @param $token
     * @param $fecha
     * @return bool
     */
    function updateToken($con, $id, $token, $fecha){
        $sql = 'UPDATE entregas_users SET ultimo_ingreso = ?, token = ? WHERE id = ?';
        $stmt = $con->prepare($sql);
        $stmt->bind_param('ssi',$fecha,$token,$id);
        $result = $stmt->execute();
        return $result;
    }

    switch($methodApi){
       // metodo post 
       //enviar data
        case 'POST':

            try{
                //obtenemos los datos del usuario
                $_POST = json_decode(file_get_contents('php://input'),true);

                $username = $_POST['username'];
                $password = $_POST['password'] ?? '';

                if (empty($username) || empty($password)) {
                    http_response_code(400);
                    echo json_encode(['message' => 'Datos incompletos']);
                    return;
                }

                //consultamos el usuario en la BD
                $query = "SELECT * FROM entregas_users WHERE username = ? LIMIT 1";
                $stmt = $con->prepare($query);
                $stmt->bind_param('s', $username);
                $stmt->execute();
                $result = $stmt->get_result();
                $user = $result->fetch_assoc();

                //comprobamos si el usuario existe y si la contraseña es correcta
                if (!$user || !password_verify($password, $user['password'])) {
                    http_response_code(401);
                    echo json_encode(['message' => 'Credenciales incorrectas']);
                    return;
                }

                $key = "APP_ENTREGAS_168**";

                $payload = array(
                    "id" => $user['id'],
                    "usuario" => $user['username'],
                    "rol" => $user['rol_user']
                );

                $token = JWT::encode($payload, $key, 'HS256');

                $datos = [
                    'id' => $user['id'],
                    'username' => $user['username'],
                    'rol' => $user['rol_user'],
                    'ultima_sesion' => $fecha_actual,
                    'token' => $token
                ];
                //actualizamos el token
                if(updateToken($con, $user['id'], $token, $fecha_actual)){
                    http_response_code(200);
                    echo json_encode(['data' => $datos]);
                    return;
                }else{
                    http_response_code(500);
                    echo json_encode(['message' => 'Error al actualizar el token']);
                    return;
                }
            }catch(Exception $e){
                header("HTTP/1.1 400");
                $response['status'] = 400;
                $response['mensaje'] = $e->getMessage();
                echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
            }        
        break;
        // metodo get 
        case 'GET':

            
        break;
        default:
        break;
    }

}else{
    echo "DB FOUND CONNECTED";
}
?>
