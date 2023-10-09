<?php
// conexion con base de datos 
include '../conexion/conn.php';
//incluir middleware
include '../middleware/validarToken.php';

date_default_timezone_set('America/Mexico_City');
$fecha = date('Y-m-d H:i:s');

// declarar array para respuestas 
$response = array();

//SELECT nombre,precio_costo,precio_costo_provisional,((precio_costo-precio_costo_provisional)/precio_costo_provisional)*100 as porcentaje FROM `productos`;

// insertamos cabeceras para permisos 

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept,Authorization, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");
header("Content-Type: JSON");
header('Content-Type: application/json;charset=utf-8'); 



// validamos si hay conexion 
if($con){
    if($token_access['token']){
        $methodApi = $_SERVER['REQUEST_METHOD'];
        
        if($methodApi == 'POST'){

            $_POST = json_decode(file_get_contents('php://input'),true);

            $fecha_inicio = $_POST['fecha_inicio'];
            $fecha_fin    = $_POST['fecha_fin'];


            $sql = 'SELECT id_usuario,nombres,SUM(total) as total_comprado,COUNT(*) as total_compras FROM `folios` WHERE (fecha>=? AND fecha<=?)  AND (estatus="Listo para salida" OR estatus="Enviado a paqueteria" OR estatus="Enviado a Cliente" OR estatus="Entregado en bodega")  GROUP BY id_usuario ORDER BY total_comprado DESC';

            $stmt = $con->prepare($sql);
            $stmt->bind_param("ss",$fecha_inicio,$fecha_fin);
            $stmt->execute();
            $result = $stmt->get_result();
            $response = $result->fetch_all(MYSQLI_ASSOC);
            echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
        }

        if($methodApi == 'GET'){

            $sql = 'SELECT *FROM folios WHERE (fecha>=? AND fecha<=?) AND (estatus="Listo para salida" OR estatus="Enviado a paqueteria" OR estatus="Enviado a Cliente" OR estatus="Entregado en bodega") AND id_usuario =? ORDER BY id ASC';

            $fecha_inicio = $_GET['fecha_inicio'];
            $fecha_fin    = $_GET['fecha_fin'];
            $id           = $_GET['id'];

            $stmt = $con->prepare($sql);
            $stmt->bind_param("sss", $fecha_inicio,$fecha_fin,$id);
            $stmt->execute();
            $result = $stmt->get_result();
            $response = $result->fetch_all(MYSQLI_ASSOC);
            echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);

        }
    }else{
        echo $token_access['validate'];
    }
    //echo "Informacion".file_get_contents('php://input');

}else{
    echo "DB FOUND CONNECTED";
}
?>