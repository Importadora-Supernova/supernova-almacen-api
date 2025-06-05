<?php
// -------------------- HEADERS PARA CORS --------------------
header('Access-Control-Allow-Origin: *'); // Cambia "*" por dominio en producción
header('Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE');
header('Access-Control-Allow-Headers: Authorization, Content-Type');
header('Content-Type: application/json');

// Manejar solicitud OPTIONS de preflight
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

// conexion con base de datos 
include '../conexion/conn.php';
require_once('entregas.class.php');
require_once('middleware/tokenMiddleware.php');
require_once('middleware/authMiddleware.php');
include '../vendor/autoload.php';
date_default_timezone_set('America/Mexico_City');

// declarar array para respuestas 
$response = array();

//instanciamos la clase de entregas y middleware AUTH
$entregas = new Entregas();
$auth = new Auth();


$fecha = date('Y-m-d H:i:s');

// Verificar token
$headers = getallheaders();
$token_access = array("validate" => "", "token" => false);

if (isset($headers['Authorization'])) {
    $token_access = $auth->verificarToken($headers['Authorization'], $con);
} else {
    $token_access['validate'] = "No se proporcionó token";
}

if ($con) {
    if ($token_access['token']) {
        $methodApi = $_SERVER['REQUEST_METHOD'];    
        if($methodApi == 'GET'){
            if(isset($_GET['id'])){
                $id = $_GET['id'] ?? null;
                if (!$id) {
                    throw new Exception("El campo 'id' es requerido.");
                }
                $result = $entregas->getImagenesEntrega($con, $id);
                echo json_encode($result,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
            }
}
    }else{
        echo $token_access['validate'];
    }
}else{
    echo "DB FOUND CONNECTED";
}





