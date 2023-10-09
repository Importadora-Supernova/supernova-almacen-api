<?php 
// conexion con base de datos 
include '../conexion/conn.php';
//IMPORT CLASS
require_once('../class/filePedido.class.php');

date_default_timezone_set('America/Mexico_City');
$fecha = date('Y-m-d H:i:s');

$response = array();
$datos    = array();
$files    = array();

$file = new FilePedido();

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
        $orden = $_GET['orden'];

        $sql = 'SELECT *FROM doc_pagos_pedido WHERE orden="'.$orden.'"';
        $result = mysqli_query($con,$sql);
        $i = 0;
        while ($row = mysqli_fetch_assoc($result)){
            $datos['id']             = $row['id_doc'];
            $datos['orden']          = $row['orden'];
            $datos['monto']          = $row['monto'];
            $datos['nota_pago']      = $row['nota_pago'];
            $datos['factura']        = $row['factura'];
            $datos['razon_social']   = $row['razon_social'];
            $datos['direccion']      = $row['direccion'];
            $datos['colonia']        = $row['colonia'];
            $datos['ciudad']         = $row['ciudad'];
            $datos['estado']         = $row['estado'];
            $datos['codigo_postal']  = $row['codigo_postal'];
            $datos['telefono']       = $row['telefono'];
            $datos['rfc']            = $row['rfc'];
            $datos['correo']         = $row['correo'];
            $datos['concepto']       = $row['concepto'];
            $datos['cfdi']           = $row['cfdi'];
            $datos['forma_pago']     = $row['forma_pago'];
            $datos['fecha_register'] = $row['fecha_register'];
        }

        $sqlFile = 'SELECT *FROM archivos_pagos WHERE orden="'.$orden.'"';
        $resultado = mysqli_query($con,$sqlFile);
        while ($row = mysqli_fetch_assoc($resultado)){
            $files[$i]['id_archivo']     = $row['id_archivo'];
            $files[$i]['nombre_archivo'] = $row['nombre_archivo'];
            $files[$i]['tipo_archivo']   = $row['tipo_archivo'];
            $files[$i]['fecha_registro'] = $row['fecha_registro'];
            $i++;
        }
        $datos['files'] = $files;
        $response = $datos;
        echo  json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }

    if($methodApi == 'POST'){
        $con->autocommit(false);
        $_POST = json_decode(file_get_contents('php://input'),true);
        try
        {
            //asignamos datos a variables
            // $file_src      = $_FILES['file']['tmp_name'];
            // $nombreArchivo = $_FILES['file']['name'];
            $cantidad      = $_REQUEST['cantidad'];
            $orden         = $_REQUEST['orden'];
            $monto         = $_REQUEST['monto'];
            $razon         = $_REQUEST['razon'];
            $direccion     = $_REQUEST['direccion'];
            $colonia       = $_REQUEST['colonia'];
            $factura       = $_REQUEST['factura'];
            $ciudad        = $_REQUEST['ciudad'];
            $estado        = $_REQUEST['estado'];
            $codigoPostal  = $_REQUEST['codigoPostal'];
            $telefono      = $_REQUEST['telefono'];
            $rfc           = $_REQUEST['rfc'];
            $correo        = $_REQUEST['correo'];
            $concepto      = $_REQUEST['concepto'];
            $cfdi          = $_REQUEST['cfdi'];
            $formaPago     = $_REQUEST['formaPago'];
            $nota_pago     = $_REQUEST['nota_pago'];

            $nombre_directorio = "pagos/".$orden."";


            $sql = 'INSERT INTO doc_pagos_pedido (orden,monto,nota_pago,factura,razon_social,direccion,colonia,ciudad,estado,codigo_postal,telefono,rfc,correo,concepto,cfdi,forma_pago,fecha_register) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)';

            $sqlUpload = 'INSERT INTO archivos_pagos (nombre_archivo,orden,tipo_archivo,fecha_registro) VALUES (?,?,?,?)';

            if(file_exists($nombre_directorio)){
                if(file_exists(__DIR__."/$nombre_directorio/$nombreArchivo")){
                    header("HTTP/1.1 400 OK");
                    $response['status'] = 400;
                    $response['mensaje'] = 'existe el archivo en el directorio';
                    echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                }else{   
                    $resultado = $file->registerPago($con,$sql,$orden,$monto,$nota_pago,$factura,$razon,$direccion,$colonia,$ciudad,$estado,$codigoPostal,$telefono,$rfc,$correo,$concepto,$cfdi,$formaPago,$fecha);

                    $brand = false;
                    for ($i = 0; $i <$cantidad; $i++) {

                        $fil = 'file'.$i;
                        $file_src      = $_FILES[$fil]['tmp_name'];
                        $nombre_archivo =  $_FILES[$fil]['name']; 
                        $type =  $_FILES[$fil]['type']; 
                        move_uploaded_file($file_src, "".__DIR__."/$nombre_directorio/$nombre_archivo"); 

                        $result_file = $file->uploadFile($con,$sqlUpload,$nombre_archivo,$orden,$type,$fecha);

                        if($result_file == 1){
                            $brand = true;
                        }else{
                            $brand = false;
                            break;
                        }
                        
                    }

                    if($resultado == 1 && $brand){
                        $con->commit();
                        header("HTTP/1.1 200 OK");
                        $response['status'] = 200;
                        $response['mensaje'] = "Se ha guardado el pago correctamente";
                        echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                        $con->close();
                    }else{
                        $con->rollback();
                        header("HTTP/1.1 400 OK");
                        $response['status'] = 400;
                        $response['mensaje'] = 'Ocurrio un error';
                        echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                    }
                }
                
            }else{
                $directorio = mkdir(__DIR__."/$nombre_directorio");

                if ($directorio) {

                    $resultado = $file->registerPago($con,$sql,$orden,$monto,$nota_pago,$factura,$razon,$direccion,$colonia,$ciudad,$estado,$codigoPostal,$telefono,$rfc,$correo,$concepto,$cfdi,$formaPago,$fecha);
                    
                    $brand = false;
                    for ($i = 0; $i <$cantidad; $i++) {

                        $fil = 'file'.$i;
                        $file_src      = $_FILES[$fil]['tmp_name'];
                        $nombre_archivo =  $_FILES[$fil]['name']; 
                        $type =  $_FILES[$fil]['type']; 
                        move_uploaded_file($file_src, "".__DIR__."/$nombre_directorio/$nombre_archivo"); 

                        $result_file = $file->uploadFile($con,$sqlUpload,$nombre_archivo,$orden,$type,$fecha);

                        if($result_file == 1){
                            $brand = true;
                        }else{
                            $brand = false;
                            break;
                        }
                        
                    }
                    if($resultado == 1 && $brand){
                        $con->commit();
                        header("HTTP/1.1 200 OK");
                        $response['status'] = 200;
                        $response['mensaje'] = "Se ha guardado el pago correctamente";
                        echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                        $con->close();
                    }else{
                        $con->rollback();
                        header("HTTP/1.1 400 OK");
                        $response['status'] = 400;
                        $response['mensaje'] = 'Ocurrio un error';
                        echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                    }
                    
                } else {
                    $con->rollback();
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
            $con->rollback();
            $response['mensaje'] = $e->getMessage();
            echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
        }

    }
}else{
    echo "DB FOUND CONNECTED";
}

?>