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

        $sqlUpdate = 'UPDATE folios SET medidas="Listo" WHERE orden="'.$_POST['orden'].'"';
        $resultUpdate = mysqli_query($con,$sqlUpdate);

        $band = true;
        $i=0;
        $cajas = [];
        $cajas = $_POST['cajas'];

        while($i<count($cajas)){
            $sqlInsert = 'INSERT INTO medidas_almacen (alto,largo,ancho,peso,orden,fecha) VALUES ('.$cajas[$i]['alto'].','.$cajas[$i]['largo'].','.$cajas[$i]['ancho'].','.$cajas[$i]['peso'].',"'.$_POST['orden'].'","'.$fecha.'")';
            $result = mysqli_query($con,$sqlInsert);
            if($result){
                $i++;
            }else{
                $band = false;
                break;
            }
        }

        if($band && $resultUpdate){
            $con->commit();
            header("HTTP/1.1 200 OK");
            $response['status'] = 200;
            $response['mensaje'] = 'El proceso se ejecuto exitosamente';
            echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
        }else{
            $con->rollback();
            header("HTTP/1.1 400");
            $response['status'] = 400;
            $response['mensaje'] = 'Ocurrio un error en el proceso intente nuevamente';
            echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
        }
    }

    if($methodApi == 'GET'){
        $sql = 'SELECT *FROM view_pedidos_medidas';
        $result = mysqli_query($con,$sql);

        $i=0;
        while($row = mysqli_fetch_assoc($result)){
            $response[$i]['orden'] =  $row['orden'];
            $response[$i]['nombres'] =  $row['nombres'];
            $response[$i]['paqueteria'] =  $row['paqueteria'];
            $response[$i]['fecha_almacen'] =  $row['fecha_almacen'];
            $response[$i]['cajas'] =  $row['cajas'];
            $response[$i]['bolsas'] = $row['bolsas'];
            $response[$i]['direccion'] =  $row['direcciond'];
            $response[$i]['ciudad'] =  $row['ciudadd'];
            $response[$i]['estado'] =  $row['estadod'];
            $response[$i]['codigop'] =  $row['codigopd'];
            $response[$i]['telefono'] =  $row['telefonod'];
            $i++;
        }
        echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
    }
}else{
    echo "DB FOUND CONNECTED";
}


?>