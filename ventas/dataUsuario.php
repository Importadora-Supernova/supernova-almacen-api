<?php
// conexion con base de datos 
include '../conexion/conn.php';
include '../middleware/midleware.php';
date_default_timezone_set('America/Mexico_City');
//incluir middleware
$fecha_actual = date('Y-m-d H:i:s');


// declarar array para respuestas 
$response = array();

// insertamos cabeceras para permisos 

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type,Authorization, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");
header("Content-Type: JSON");
header('Content-Type: application/json;charset=utf-8'); 

// validamos si hay conexion 
if($con){
    if($validate === 'validado'){
        $methodApi = $_SERVER['REQUEST_METHOD'];

        if($methodApi == 'POST'){
             // metodo POST
            $_POST = json_decode(file_get_contents('php://input'),true);
            
            $sqlUpdate = 'UPDATE registro_usuario SET nombred="'.$_POST['nombre'].'",apellidod="'.$_POST['apellido'].'",direcciond="'.$_POST['direcciond'].'",coloniad="'.$_POST['coloniad'].'",ciudadd="'.$_POST['ciudadd'].'",estadod="'.$_POST['estadod'].'",codigopd="'.$_POST['codigopd'].'",telefonod="'.$_POST['telefonod'].'",rfc="'.$_POST['rfc'].'",paqueteria="'.$_POST['paqueteria'].'" WHERE orden="'.$_POST['orden'].'"';
            $result = mysqli_query($con,$sqlUpdate);

            $fullName = $_POST['nombre'].' '.$_POST['apellido'];
            $sqlUpdateFolio = 'UPDATE folios SET nombres="'.$fullName.'",paqueteria="'.$_POST['paqueteria'].'",id_usuario_edit='.$_POST['id_user'].',fecha_edit="'.$fecha_actual.'" WHERE orden="'.$_POST['orden'].'"';
            $resultFolio = mysqli_query($con,$sqlUpdateFolio);

            if($result && $resultFolio){
                header("HTTP/1.1 200 OK");
                $response['status'] = 200;
                $response['mensaje'] = 'Registro actualizado correctamente';
                echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
            }else{
                header("HTTP/1.1 400");
                $response['status'] = 400;
                $response['mensaje'] = 'No se pudo actualizar el registro';
                echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
            }
        }

        if($methodApi == 'PUT'){
            $_PUT = json_decode(file_get_contents('php://input'),true);
            $con->autocommit(false);

            $sqlUpdate = 'UPDATE registro_usuario SET nombred="'.$_PUT['nombre'].'",apellidod="'.$_PUT['apellido'].'",direcciond="'.$_PUT['direcciond'].'",coloniad="'.$_PUT['coloniad'].'",ciudadd="'.$_PUT['ciudadd'].'",estadod="'.$_PUT['estadod'].'",codigopd="'.$_PUT['codigopd'].'",telefonod="'.$_PUT['telefonod'].'",rfc="'.$_PUT['rfc'].'",paqueteria="'.$_PUT['paqueteria'].'",envio="'.$_PUT['envio'].'" WHERE orden="'.$_PUT['orden'].'"';
            $result = mysqli_query($con,$sqlUpdate);
            
            $fullName = $_PUT['nombre'].' '.$_PUT['apellido'];
            $sqlUpdateFolios = 'UPDATE folios SET nombres="'.$fullName.'",paqueteria="'.$_PUT['paqueteria'].'",total=total+'.$_PUT['suma'].',estatus="'.$_PUT['estatus'].'",cajas="'.$_PUT['cajas'].'",venta_paqueteria="'.$_PUT['monto'].'",saldo_pendiente="'.$_PUT['saldoFavor'].'",envio="'.$_PUT['envio'].'",id_usuario_edit='.$_PUT['id_user'].',fecha_edit="'.$fecha_actual.'" WHERE orden="'.$_PUT['orden'].'"';
            $resultFolios = mysqli_query($con,$sqlUpdateFolios);
            
            $suma = $_PUT['suma'];
            $methods = $_PUT['metodos'];
            $flag    = true;
            $k       =  0;
            
            if($suma > 0){

                 //insertar nuevos registros en pagos
                while($k<count($methods))
                {
                    $sqlInsertPago = 'INSERT INTO pagos (orden,fecha,monto,banco,metodos_pago) VALUES ("'.$_PUT['orden'].'","'.$fecha_actual.'",'.$methods[$k]['monto'].',"'.$methods[$k]['banco'].'","'.$methods[$k]['metodo'].'")';
                    $resultPago = mysqli_query($con,$sqlInsertPago);
                    if($resultPago){
                        $k++;
                    }else{
                        $flag = false;
                        break;
                    }
                }


                // $sqlPagos = 'INSERT INTO pagos (orden,fecha,monto,banco,metodos_pago) VALUES ("'.$_PUT['orden'].'","'.$fecha_actual.'",'.$_PUT['suma'].',"'.$_PUT['banco'].'","'.$_PUT['metodos'].'")';
                // $resultPagos = mysqli_query($con,$sqlPagos); 
                
                if($result && $resultFolios && $flag)
                {
                    $con->commit();
                    header("HTTP/1.1 200 OK");
                    $response['status'] = 200;
                    $response['mensaje'] = 'Registro actualizado correctamente';
                    echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                }else
                {
                    $con->rollback();
                    header("HTTP/1.1 400");
                    $response['status'] = 400;
                    $response['mensaje'] = 'No se pudo actualizar el registro';
                    echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                }
            
            }else{
                if($result && $resultFolios)
                {
                    $con->commit();
                    header("HTTP/1.1 200 OK");
                    $response['status'] = 200;
                    $response['mensaje'] = 'Registro actualizado correctamente';
                    echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                }else
                {
                    $con->rollback();
                    header("HTTP/1.1 400");
                    $response['status'] = 400;
                    $response['mensaje'] = 'No se pudo actualizar el registro';
                    echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                }
            }


        }
    }else{
        header("HTTP/1.1 201");
        $response['status'] = 401;
        $response['mensaje'] = 'Token '.$validate;
        echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
    }
    //echo "Informacion".file_get_contents('php://input');

}else{
    echo "DB FOUND CONNECTED";
}
?>