<?php

// conexion con base de datos 
include '../conexion/conn.php';
require_once('../class/pedidosTienda.class.php');

// declarar array para respuestas 
$response = array();

$pedido = new pedidosTienda();

date_default_timezone_set('America/Mexico_City');
$fecha = date('Y-m-d H:i:s');

// insertamos cabeceras para permisos 

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept,Authorization, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");
header("Content-Type: JSON");
header('Content-Type: application/json;charset=utf-8'); 

if($con){
    
    $methodApi = $_SERVER['REQUEST_METHOD'];

    if($methodApi == 'GET'){
        if(isset($_GET['medidas'])){
            $sql = 'SELECT *FROM tienda_cajas_envio WHERE id_envio=?';
            $id = $_GET['medidas'];
            $response = $pedido->getMedidas($con,$sql,$id); 
            echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
        }else{
            $estado = 'Registrado por tienda';
            $sql = 'SELECT *FROM view_pedidos_tiendas WHERE estatus_envio!=? ORDER BY  id_envio_tienda DESC';
            $response = $pedido->getPedidosMedidas($con,$sql,$estado);
            echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
        }
    }

    if($methodApi == 'POST'){
        // metodo post 
        $_POST = json_decode(file_get_contents('php://input'),true);
        try
        {
            //asignamos datos a variables
            $file_src      = $_FILES['file']['tmp_name'];
            $nombreArchivo = $_FILES['file']['name'];
            $id            = $_REQUEST['id'];
            $nombres       = $_REQUEST['nombres'];
            $monto         = $_REQUEST['monto'];

            $file          = 'guia_'.$id;


            $nombre_directorio = "guias_tienda/".$file."";

            $sql = 'UPDATE tienda_envios_tienda SET estatus_envio=?,costo_guia=?,fecha_guia=?,file_name=? WHERE id_envio_tienda=?';
            $estatus = 'Guias listas';

            
            if(file_exists($nombre_directorio)){
                if(file_exists(__DIR__."/$nombre_directorio/$nombreArchivo")){
                    header("HTTP/1.1 400 OK");
                    $response['status'] = 400;
                    $response['mensaje'] = 'existe el archivo en el directorio';
                    echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                }else{
                    move_uploaded_file($file_src, "".__DIR__."/$nombre_directorio/$nombreArchivo");
                    $resultado = $pedido->updatePedidoGuias($con,$sql,$estatus,$monto,$id,$fecha,$nombreArchivo);
                    if($resultado == 1){
                        header("HTTP/1.1 200 OK");
                        $response['status'] = 200;
                        $response['mensaje'] = "Se ha guardado el archivo correctamente";
                        echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                        $con->close();
                    }
                    
                }
                
            }else{
                $directorio = mkdir(__DIR__."/$nombre_directorio");

                if ($directorio) {
                    move_uploaded_file($file_src, "$nombre_directorio/$nombreArchivo");
                    $resultado = $pedido->updatePedidoGuias($con,$sql,$estatus,$monto,$id,$fecha,$nombreArchivo);
                    if($resultado == 1){
                        header("HTTP/1.1 200 OK");
                        $response['status'] = 200;
                        $response['mensaje'] = "Se ha guardado el archivo correctamente";
                        echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                        $con->close();
                    }
                    
                } else {
                    header("HTTP/1.1 400 OK");
                    $response['status'] = 400;
                    $response['mensaje'] = 'Error creando directorio';
                    echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                }

            }
        }catch(Exception $e)
        {
            header("HTTP/1.1 400 OK");
            $response['status'] = 400;
            $response['mensaje'] = $e->getMessage();
            echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
        }
       
    }

    if($methodApi == 'PUT'){
        $_PUT = json_decode(file_get_contents('php://input'),true);
        $num = $_PUT['num_rastreo'];
        $id  = $_GET['id'];

        $sql = 'UPDATE tienda_cajas_envio SET num_rastreo=? WHERE id_caja_envio=?';

        $result = $pedido->updateNumeroRastreo($con,$sql,$num,$id);

        if($result == 1){
            header("HTTP/1.1 200 OK");
            $response['status'] = 200;
            $response['mensaje'] = "Se guardo el numero de rastreo correctamente";
            echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
            $con->close();
        }else{
            header("HTTP/1.1 400");
            $response['status'] = 400;
            $response['mensaje'] = 'No se pudo crear el registro, intentalo de nuevo';
            echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
        }
    }
//echo "Informacion".file_get_contents('php://input');

}else{
echo "DB FOUND CONNECTED";
}

?>