<?php
// conexion con base de datos 
include '../conexion/conn.php';
//incluir middleware
include '../middleware/validarToken.php';

date_default_timezone_set('America/Mexico_City');
$fecha_actual = date('Y-m-d H:i:s');

// declarar array para respuestas 
$response = array();

// insertamos cabeceras para permisos 

header('Access-Control-Allow-Origin:*');
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

            try{

                $_POST = json_decode(file_get_contents('php://input'),true);

                $sql = 'UPDATE clientes_vendedores SET num_cajas= ?,monto_envio=?,cuenta=?,paqueteria=?,factura=?,pagado=?,factura_enviada=?,fecha_cotizacion=?,fecha_pago=? WHERE orden=?';

                $stmt = $con->prepare($sql);
                $stmt->bind_param('idssiiisss',$_POST['num_cajas'],$_POST['monto_envio'],$_POST['cuenta'],$_POST['paqueteria'],$_POST['factura'],$_POST['pagado'],$_POST['factura_enviada'],$_POST['fecha_cotizacion'],$_POST['fecha_pago'],$_POST['orden']);

                if($stmt->execute()){
                    header("HTTP/1.1 200 OK");
                    $response['status'] = 200;
                    $response['mensaje'] = 'Se actualizo información correctamente';
                    echo  json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                }else{
                    header("HTTP/1.1 400");
                    $response['mensaje'] = 'Ocurrio un error,intente nuevamente';
                    echo  json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                }
    
            }catch(Exception $e){
                header("HTTP/1.1 400");
                $response['mensaje'] = $e->getMessage();
                echo  json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
            }
        }//end if post

    }else{
        echo $token_access['validate'];
    }
}else{
    echo "DB FOUND CONNECTED";
}