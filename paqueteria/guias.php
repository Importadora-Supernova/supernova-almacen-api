<?php
// conexion con base de datos 
include '../conexion/conn.php';
require_once('../class/guias.class.php');
date_default_timezone_set('America/Mexico_City');


// declarar array para respuestas 
$response = array();

$guia = new Guias();

// insertamos cabeceras para permisos 

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept,Authorization, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");
header("Content-Type: JSON");
header('Content-Type: application/json;charset=utf-8');
$fecha = date('Y-m-d H:i:s');
if ($con) {
    $methodApi = $_SERVER['REQUEST_METHOD'];
    if ($methodApi == 'GET') {

        //metodos GET PARA ORDENES
        if(isset($_GET['listas'])){
            //validacion get para consulta de folios con guias listas 
            $sqlSelect = 'SELECT  f.id,f.nombres,f.orden,f.paqueteria,g.fecha_cargado,g.file_guia FROM folios f INNER JOIN guias g  ON f.orden = g.orden WHERE f.estatus LIKE "Guia enviada"';
            $response = $guia->getPedidosEstatus($con,$sqlSelect);
            echo  json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
            
        }else if(isset($_GET['bodega'])){
            //validacion get para consulta de folios listo para salida a paqueteria 
            $sql = 'SELECT f.id,f.nombres,f.orden,f.paqueteria,f.fecha_almacen,e.cajas FROM folios f INNER JOIN empaquetado e ON f.orden = e.orden WHERE f.estatus="Listo para salida" && f.envio="Si"';
            $response = $guia->getPedidosEstatus($con,$sql);
            echo  json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

        }else if(isset($_GET['entregas'])){
            //validacion get para consulta de folios listos para salida, y seran entregados en bodega 
            $sql = 'SELECT f.id,f.nombres,f.orden,f.fecha_almacen,e.cajas,e.bolsas FROM folios f INNER JOIN empaquetado e ON f.orden = e.orden WHERE f.estatus="Listo para salida" && f.envio="No"';
            $response = $guia->getPedidosEstatus($con,$sql);
            echo  json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

        }else if(isset($_GET['orden'])){
            //validacion para traer medidas de cajas
            $orden = $_GET['orden'];
            $sql = 'SELECT *FROM medidas_almacen WHERE orden=?';
            $response = $guia->getCajasOrden($con,$sql,$orden);
            echo  json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

        }else if(isset($_GET['otras'])){
            //traer los folios de otras páqueterias
            //$sql = 'SELECT f.id,f.fecha,f.nombres,f.orden,f.estatus,f.paqueteria,g.numero_rastreo FROM folios f LEFT JOIN guias g ON f.orden = g.orden WHERE f.paqueteria!="FEDEX" AND f.paqueteria!="DHL" AND f.paqueteria!="ESTAFETA" AND f.paqueteria!="ENVIA SU GUIA" AND f.estatus="Enviado a paqueteria" AND f.fecha_almacen>="2022-08-01"';
            $sql = 'SELECT *FROM folios  WHERE paqueteria!="FEDEX" AND paqueteria!="DHL" AND paqueteria!="ESTAFETA" AND paqueteria!="ENVIA SU GUIA" AND estatus="Enviado a paqueteria" AND fecha_almacen>="2022-08-01"';
            $response = $guia->getPedidosEstatus($con,$sql);
            echo  json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

        }
        
    }

    if($methodApi == 'POST'){
        //convertimos el json ha array post 
        $_POST = json_decode(file_get_contents('php://input'),true);
        //inicializamos la transaccion
        $con->autocommit(false);
        try
        {    
            //asignamos datos a variables
            $orden         = $_POST['orden'];
            $paqueteria    = $_POST['paqueteria'];
            $cajas         = $_POST['cajas'];

            //declaramos las sentencias SQL, para insertar guias,insertar medidas y actualizar el folio
            $sql        = 'INSERT INTO guias (orden,fecha,paqueteria) VALUES (?,?,?)';
            $sqlMedidas = 'INSERT INTO medidas_almacen (alto,largo,ancho,peso,orden,fecha) VALUES (?,?,?,?,?,?)';
            $sqlUpdate  = 'UPDATE folios SET estatus="Medidas enviadas" WHERE orden=?';

            //ejecutamos el insert de la guia y la actualizacion del folio
            $resultado    = $guia->insertGuia($con,$sql,$orden,$fecha,$paqueteria);
            $resultUpdate = $guia->updateFolioEstatus($con,$sqlUpdate,$orden);  

            $band = false;
            //creamos un ciclo de interaccion dependiendo el numero de cajas
            //para insertar las medidas de cada caja
            foreach($cajas as $row){
                $result = $guia->insertMedidas($con,$sqlMedidas,$orden,$fecha,$row);
                if($result == 1){
                    $band = true;
                }else{
                    $band = false;
                    break;
                }
            }
            //validamos que todas las operaciones esten correctas
            if($resultado == 1  && $resultUpdate == 1   && $band){
                //si todo exitoso ejecutamos el commit para dar exitosa la transaccion
                $con->commit();
                header("HTTP/1.1 200 OK");
                $response['status'] = 200;
                $response['mensaje'] = 'Medidas guardadas exitosamente';
                echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                $con->close();
            }else{
                //caso contrario ejecutamos roollback
                $con->rollback();
                header("HTTP/1.1 400");
                $response['status'] = 400;
                $response['mensaje'] = 'No se pudo registrar';
                echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT); 
                $con->close(); 
            }
        }catch(Exception $e)
        {
            $con->rollback();
            header("HTTP/1.1 400");
            $response['status'] = 400;
            $response['mensaje'] = $e->getMessage();
            echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT); 
            $con->close(); 
        } 
    }

    if($methodApi == 'PUT'){
        //metodo para actualizar la marca de una orden
        $_PUT = json_decode(file_get_contents('php://input'),true);
        $orden = $_PUT['orden'];
        
        $sql =  'UPDATE folios SET marca="1" WHERE orden=?';
        $resultado = $guia->updateMarcaGuia($con,$sql,$orden);
        
        if($resultado == 1){
            header("HTTP/1.1 200 OK");
            $response['status'] = 200;
            $response['mensaje'] = 'Registro actualizado correctamente';
            echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
            $con->close();
        }else{
            header("HTTP/1.1 400");
            $response['status'] = 400;
            $response['mensaje'] = 'No se pudo actualizar';
            echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT); 
            $con->close(); 
        }
    }


} else {
    echo "DB FOUND CONNECTED";
}
