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
        if ($methodApi == 'GET') {
            if (isset($_GET['fecha'])) {
                $fecha = $_GET['fecha'];
                $response = $entregas->getEntregas($con, $fecha);
            } else {
                $response = $entregas->getEntregas($con);
            }
            echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        }

        if($methodApi == 'POST'){
            try {
                // Verificar si hay archivos en la solicitud
                if(isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {

                  // Obtener datos del formulario
                    $orden = $_POST['orden'] ?? null;

                    if (!$orden) {
                            throw new Exception("El campo 'orden' es requerido.");
                    }

                    $id = isset($_POST['id']) ? intval($_POST['id']) : null;

                    if (!$id) {
                        throw new Exception("El campo 'id' es requerido.");
                    }

                    // Registrar entrega con imagen
                    $resultado = $entregas->registrarEntregaConImagen($con, $id, $_FILES['imagen'], $orden);
                    
                    if($resultado) {
                        header("HTTP/1.1 200 OK");
                        $response['status'] = 200;
                        $response['mensaje'] = 'Entrega registrada correctamente con imagen';
                        echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
                    } else {
                        header("HTTP/1.1 400");
                        $response['status'] = 400;
                        $response['mensaje'] = 'Error al registrar la entrega';
                        echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
                    }
                } else {
                    // Si no hay imagen, procesar solo los datos JSON
                    $_POST = json_decode(file_get_contents('php://input'), true);
                    
                    // Aquí puedes agregar código para manejar solicitudes POST sin imágenes
                    header("HTTP/1.1 400");
                    $response['status'] = 400;
                    $response['mensaje'] = 'No se proporcionó una imagen válida';
                    echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
                }
            } catch(Exception $e) {
                header("HTTP/1.1 500");
                $response['status'] = 500;
                $response['mensaje'] = 'Error del servidor: ' . $e->getMessage();
                echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
            }
        }

        if($methodApi == 'PUT'){
            try{
                //metodo para actualizar la marca de una orden
                $_PUT = json_decode(file_get_contents('php://input'),true);
                $id =  intval($_GET['id']);
                $nota = $_PUT['nota'];

                $result = $entregas->entregarOrden($con, $id, $nota);
                if($result){
                    header("HTTP/1.1 200 OK");
                    $response['status'] = 200;
                    $response['mensaje'] = 'Orden entregada correctamente';
                    echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                }else{
                    header("HTTP/1.1 400");
                    $response['status'] = 400;
                    $response['mensaje'] = 'No se pudo actualizar el registro';
                    echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                }
            }catch(Exception $e){
                header("HTTP/1.1 400");
                $response['status'] = 400;
                $response['mensaje'] = $e->getMessage();
                echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
            }
        
        }
    } else {
        header("HTTP/1.1 401 Unauthorized");
        $response['status'] = 401;
        $response['mensaje'] = 'Invalid Token: ' . $token_access['validate'];
        echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }
} else {
    echo "DB FOUND CONNECTED";
}
