<?php
// conexion con base de datos 
include '../conexion/conn.php';
date_default_timezone_set('America/Mexico_City');
// declarar array para respuestas 
$response = array();

// insertamos cabeceras para permisos 

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");
header("Content-Type: JSON");
header('Content-Type: application/json;charset=utf-8'); 
$fecha = date('Y-m-d H:i:s');
if($con){
    $methodApi = $_SERVER['REQUEST_METHOD'];
    if($methodApi == 'POST'){

        $_POST = json_decode(file_get_contents('php://input'),true);
        
        $con->autocommit(false);

        $sqlUpdate = 'UPDATE folios SET estatus="'.$_POST['estatus'].'",cajas="'.$_POST['cajas'].'",fecha_almacen="'.$fecha.'",empaquetado="'.$_POST['empaquetador'].'",medidas="'.$_POST['medidas'].'" WHERE orden="'.$_POST['orden'].'"';
        $resultUpdate = mysqli_query($con,$sqlUpdate);

        $sqlInsertEmpaquetado = 'INSERT INTO empaquetado (orden,id_empleado,cajas,bolsas) VALUES ("'.$_POST['orden'].'","'.$_POST['id_empleado'].'","'.$_POST['cajas'].'","'.$_POST['bolsas'].'")';
        $resultInsert = mysqli_query($con,$sqlInsertEmpaquetado);

        $band = true;
        $i=0;
        $cajas = $_POST['cajas'];

        while($i<$cajas){
            $caja = $i+1;
            $sqlInsert = 'INSERT INTO cajas_orden (orden,num_caja,revisado) VALUES ("'.$_POST['orden'].'",'.$caja.',0)';
            $result = mysqli_query($con,$sqlInsert);

            if($result){
                $i++;
            }else{
                $band = false;
                break;
            }
        }

        if($resultUpdate && $band && $resultInsert){
            $con->commit();
            header("HTTP/1.1 200 OK");
            $response['status'] = 200;
            $response['mensaje'] = 'El proceso de empaquetado se ejecuto exitosamente';
            echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
        }else{
            $con->rollback();
            header("HTTP/1.1 400");
            $response['status'] = 400;
            $response['mensaje'] = 'Ocurrio un error en el proceso intente nuevamente';
            echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
        }


    }
}else{
    echo "DB FOUND CONNECTED";
}


?>