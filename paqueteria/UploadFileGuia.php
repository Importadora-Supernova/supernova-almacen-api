<?php

// conexion con base de datos 
include '../conexion/conn.php';
require_once('../class/guias.class.php');

// declarar array para respuestas 
$response = array();
$errores  = array();

$guia = new Guias();

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

    if($methodApi == 'POST'){
        // metodo post 
        $_POST = json_decode(file_get_contents('php://input'),true);
        try
        {
            //asignamos datos a variables
            $data          = $_FILES['file'];
            $file_src      = $_FILES['file']['tmp_name'];
            $nombreArchivo = $_FILES['file']['name'];
            $orden         = $_REQUEST['orden'];
            $monto         = $_REQUEST['monto'];


            $nombre_directorio = "guias/".$orden."";

            $sql = 'UPDATE guias SET fecha_cargado=?,monto=?,file_guia=? WHERE orden=?';
            $sqlUpdate = 'UPDATE folios SET estatus="Guia enviada" WHERE orden=?';

            
            if(file_exists($nombre_directorio)){
                if(file_exists(__DIR__."/$nombre_directorio/$nombreArchivo")){
                    header("HTTP/1.1 400 OK");
                    $response['status'] = 400;
                    $response['mensaje'] = 'existe el archivo en el directorio';
                    echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                }else{
                    move_uploaded_file($file_src, "".__DIR__."/$nombre_directorio/$nombreArchivo");
                    $resultado = $guia->updateFileGuia($con,$sql,$orden,$fecha,$monto,$nombreArchivo);
                    $resultStatus = $guia->updateFolioEstatus($con,$sqlUpdate,$orden);  
                    if($resultado == 1 && $resultStatus == 1){
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
                    $resultado = $guia->updateFileGuia($con,$sql,$orden,$fecha,$monto,$nombreArchivo);
                    $resultStatus = $guia->updateFolioEstatus($con,$sqlUpdate,$orden);   
                    if($resultado == 1 && $resultStatus == 1){
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
//echo "Informacion".file_get_contents('php://input');

}else{
echo "DB FOUND CONNECTED";
}

?>