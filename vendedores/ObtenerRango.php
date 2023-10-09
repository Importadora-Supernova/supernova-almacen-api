<?php
// conexion con base de datos 
include '../conexion/conn.php';
//incluir middleware
include '../middleware/validarToken.php';

date_default_timezone_set('America/Mexico_City');
$fecha = date('Y-m-d');
$fecha_buscar = date("Y-m-d",strtotime($fecha."- 3 month"));

// declarar array para respuestas 
$response = array();

// insertamos cabeceras para permisos 
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept,Authorization,Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");
header("Content-Type: JSON");
header('Content-Type: application/json;charset=utf-8'); 

function calcularRango($total_ingreso)
{
    if($total_ingreso >= 0 and $total_ingreso<=10000){
        return 'Bronce';
    }
    if($total_ingreso >=10001 and $total_ingreso<=30000){
        return 'Plata';
    }
    if($total_ingreso >=30001 and $total_ingreso<=99999){
        return 'Oro';
    }
    if($total_ingreso >= 100000){
        return 'Diamante';
    }
}

function porcentajeNivel($conexion,$rango)
{
    $sql = 'SELECT porcentaje FROM admin_oferta_niveles WHERE nivel="'.$rango.'"';
    $resultado = mysqli_query($conexion,$sql);
    $fill = mysqli_fetch_assoc($resultado);;
    return $fill['porcentaje'];
}


// validamos si hay conexion 
if($con){
    if($token_access['token']){
        $methodApi = $_SERVER['REQUEST_METHOD'];

    
        if($methodApi == 'POST'){
            $_POST = json_decode(file_get_contents('php://input'),true);

            $id = $_POST['id'];
            $sql = 'SELECT SUM(total) as total FROM folios WHERE id_usuario=? AND fecha_pago>=?';

            $stmt = $con->prepare($sql);
            $stmt->bind_param("is",$id,$fecha_buscar);
            $stmt->execute();
            $result = $stmt->get_result();
            $data = $result->fetch_assoc(); 
            $response['monto'] = $data['total'];
            $response['rango'] = calcularRango($data['total']);
            $response['porcentaje'] = porcentajeNivel($con,calcularRango($data['total']));
            echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
        }
    }else{
        echo $token_access['validate'];
    }
}else{
    echo "DB FOUND CONNECTED";
}