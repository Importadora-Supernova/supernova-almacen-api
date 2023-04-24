<?php
// conexion con base de datos 
include '../conexion/conn.php';
date_default_timezone_set('America/Mexico_City');
// declarar array para respuestas 
$response = array();

$datos = array();
$cajas = array();
$direccion = array();

// insertamos cabeceras para permisos 

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");
header("Content-Type: JSON");
header('Content-Type: application/json;charset=utf-8');
//$fecha = date('Y-m-d H:i:s');
if ($con) {
    $methodApi = $_SERVER['REQUEST_METHOD'];
    if ($methodApi == 'GET') {
        if(isset($_GET['guias'])){
            $sqlSelect = 'SELECT  *FROM folios   WHERE  estatus = "Medidas Enviadas"  ORDER BY id ASC';
            $result = mysqli_query($con, $sqlSelect);
            $i = 0;
            while ($row = mysqli_fetch_assoc($result)) {
                $datos[$i]['orden'] = $row['orden'];
                $datos[$i]['nombres'] = $row['nombres'];
                $datos[$i]['paqueteria'] = $row['paqueteria'];
                $datos[$i]['estatus'] = $row['estatus'];
                $datos[$i]['cajas'] = $row['cajas'];
                $fecha = '';
                $sqlCajas = 'SELECT *FROM medidas_almacen WHERE orden="'.$row['orden'].'"';
                $j = 0;
                $resultCajas = mysqli_query($con, $sqlCajas);
                while ($fila = mysqli_fetch_assoc($resultCajas)) {
                    $cajas[$j]['id'] = $fila['id'];
                    $cajas[$j]['peso'] = $fila['peso'];
                    $cajas[$j]['alto'] = $fila['alto'];
                    $cajas[$j]['largo'] = $fila['largo'];
                    $cajas[$j]['ancho'] = $fila['ancho'];
                    $cajas[$j]['largo'] = $fila['largo'];
                    $cajas[$j]['fecha'] = $fila['fecha'];

                    if($j == 0){
                        $fecha = $fila['fecha'];
                    }
                    $j++;
                }

                //consultar datos de envio
                $sqlDireccion = 'SELECT *FROM registro_usuario WHERE orden="'.$row['orden'].'" limit 1';
                $resultDireccion = mysqli_query($con, $sqlDireccion);
                while ($fill = mysqli_fetch_assoc($resultCajas)) {
                    $direccion['direccion'] = $fill['direcciond'];
                    $direccion['estado'] = $fill['estadod'];
                    $direccion['colonia'] = $fill['coloniad'];
                    $direccion['ciudad'] = $fill['ciudadd'];
                    $direccion['codigop'] = $fill['codigopd'];

                } 

                $datos[$i]['fecha'] = $fecha;
                $datos[$i]['medidas'] = $cajas;
                $datos[$i]['direccion'] = $direccion;
                $response = $datos;
                $i++;
            }
           
            echo  json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        }else{
            $sqlSelect = 'SELECT f.orden,f.nombres,f.paqueteria,f.estatus,e.cajas,e.bolsas FROM folios f INNER JOIN empaquetado e ON f.orden = e.orden WHERE f.estatus="Esperando por guia" AND f.envio!="No" AND (f.paqueteria = "Fedex" || f.paqueteria = "DHL" || f.paqueteria = "Estafeta") ORDER BY f.id ASC';
            $result = mysqli_query($con, $sqlSelect);
            $i = 0;
            while ($row = mysqli_fetch_assoc($result)) {
                $response[$i]['orden'] = $row['orden'];
                $response[$i]['nombres'] = $row['nombres'];
                $response[$i]['paqueteria'] = $row['paqueteria'];
                $response[$i]['estatus'] = $row['estatus'];
                $response[$i]['cajas'] = $row['cajas'];
                $response[$i]['bolsas'] = $row['bolsas'];
                $i++;
            }
            echo  json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        }
        
    }


} else {
    echo "DB FOUND CONNECTED";
}
