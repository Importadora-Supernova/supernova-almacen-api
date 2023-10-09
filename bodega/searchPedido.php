<?php
// conexion con base de datos 
include '../conexion/conn.php';
date_default_timezone_set('America/Mexico_City');

// declarar array para respuestas 
$response = array();

//incluir middleware
include '../middleware/validarToken.php';

// insertamos cabeceras para permisos 

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept,Authorization, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");
header("Content-Type: JSON");
header('Content-Type: application/json;charset=utf-8'); 

$fecha = date('Y-m-d H:i:s');

// validamos si hay conexion 
if($con){
    if($token_access['token']){
        function validarFecha($date, $format = 'Y-m-d H:i:s')
        {
            $d = DateTime::createFromFormat($format, $date);
            return $d && $d->format($format) == $date;
        }
    
        $methodApi = $_SERVER['REQUEST_METHOD'];

        switch($methodApi){
            // metodo post 
            case 'POST':
             $_POST = json_decode(file_get_contents('php://input'),true);
             $con->autocommit(false);
             // actualizamos orden a pausado
             $sqlUpdate = 'UPDATE folios SET estatus="Pausado" WHERE orden="'.$_POST['orden'].'"';
             $result = mysqli_query($con,$sqlUpdate);

             $sqlInsert = 'INSERT INTO notificaciones (id_empleado,orden,motivo,descripcion,accion,notificacion,fecha_pausado) VALUES ("3168","'.$_POST['orden'].'","'.$_POST['motivo'].'","'.$_POST['descripcion'].'","'.$_POST['accion'].'","si","'.$fecha.'")';
             $resultInsert = mysqli_query($con,$sqlInsert);

             if($result && $resultInsert){
                $con->commit();
                header("HTTP/1.1 200");
                $response['mensaje'] = 'La orden fue pausada correctamente';
                echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
             }else{
                $con->rollback();
                header("HTTP/1.1 400");
                $response['mensaje'] = 'Ocurrio un error,No se podo completar la accion';
                echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
             }
            break;
            // metodo get 
            case 'GET':
             // para obtener un registro especifico
             if(isset($_GET['orden'])){ 
                 $sql = 'SELECT *FROM folios  WHERE orden='.$_GET['orden'].'';
                 $result = mysqli_query($con,$sql);
                 $i=0;
                 while($row = mysqli_fetch_assoc($result)){
                     $response[$i]  ['cliente'] = $row['nombres'];
                     $response[$i]['orden'] = $row['orden'];
                     $response[$i]['estatus'] = $row['estatus'];
                     $response[$i]['cajas'] = $row['cajas'];
                     $response[$i]['paqueteria'] = $row['paqueteria'];
                     $response[$i]['fecha'] = $row['fecha'];
                     $response[$i]['fecha_procesado'] = $row['fecha_procesado'];
                     $response[$i]['fecha_almacen'] = $row['fecha_almacen'];
                     $response[$i]['fecha_salida'] = $row['fecha_salida'];
                     $response[$i]['fecha_entrega'] = $row['fecha_entrega'];
                     $i++;
                 }

                 echo  json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
              } else if(isset($_GET['pagados'])){
                    $sqlPagados = 'SELECT * FROM folios WHERE estatus="Pagado" or estatus="Pausado" or estatus="Resuelto" order by fecha_entrega ';
                    $resultPagados = mysqli_query($con,$sqlPagados);
                    $i=0;
                    while($row = mysqli_fetch_assoc($resultPagados)){
                        $response[$i]  ['cliente'] = $row['nombres'];
                        $response[$i]['orden'] = $row['orden'];
                        $response[$i]['estatus'] = $row['estatus'];
                        $response[$i]['cajas'] = $row['cajas'];
                        $response[$i]['paqueteria'] = $row['paqueteria'];
                        $response[$i]['efectivo'] = $row['efectivo'];
                        $response[$i]['nota'] = $row['nota'];
                        $response[$i]['fecha'] = $row['fecha'];
                        $response[$i]['impresion'] = $row['impresion'];
                        $response[$i]['marca_tiempo'] = $row['marca_tiempo'];
                        $response[$i]['fecha_procesado'] = $row['fecha_procesado'];
                        $response[$i]['fecha_almacen'] = $row['fecha_almacen'];
                        $response[$i]['fecha_salida'] = $row['fecha_salida'];
                        $response[$i]['fecha_entrega'] = $row['fecha_entrega'];
                        $response[$i]['entrega'] = validarFecha($row['fecha_entrega']);
                        $i++;
                    }

                    echo  json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
            }else{
                // $sql = 'SELECT f.nombres,f.orden,f.paqueteria,f.fecha,f.fecha_almacen,e.cajas,e.bolsas FROM folios f INNER JOIN empaquetado e ON f.orden = e.orden WHERE f.estatus = "Listo para salida" or f.estatus = "Esperando por guia" or f.estatus = "Medidas enviadas" or f.estatus = "Guia enviada"';
                $sql = 'SELECT *FROM folios WHERE estatus = "Listo para salida" or estatus = "Esperando por guia" or estatus = "Medidas enviadas" or estatus = "Guia enviada"';
                $result = mysqli_query($con,$sql);
                $i=0;
                while($row = mysqli_fetch_assoc($result)){
                    $response[$i]  ['nombres'] = $row['nombres'];
                    $response[$i]['orden'] = $row['orden'];
                    $response[$i]['cajas'] = $row['cajas'];
                    $response[$i]['paqueteria'] = $row['paqueteria'];
                    // $response[$i]['bolsas'] = $row['bolsas'];
                    $response[$i]['fecha'] = $row['fecha'];
                    $response[$i]['fecha_almacen'] = $row['fecha_almacen'];
                    $i++;
                }
                echo  json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
            }
            break;

        }
    }else{
        echo $token_access['validate'];
    }
    //echo "Informacion".file_get_contents('php://input');

}else{
    echo "DB FOUND CONNECTED";
}
?>