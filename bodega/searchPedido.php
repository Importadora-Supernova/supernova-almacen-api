<?php
// conexion con base de datos 
include '../conexion/conn.php';

// declarar array para respuestas 
$response = array();

// insertamos cabeceras para permisos 

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");
header("Content-Type: JSON");
header('Content-Type: application/json;charset=utf-8'); 



// validamos si hay conexion 
if($con){
    
        $methodApi = $_SERVER['REQUEST_METHOD'];

        switch($methodApi){
            // metodo post 
            case 'POST':
             $_POST = json_decode(file_get_contents('php://input'),true);
             //
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
                     $i++;
                 }

                 echo  json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
              } else if(isset($_GET['pagados'])){
                    $sqlPagados = 'SELECT * FROM folios WHERE estatus="Pagado" or estatus="Pausado" or estatus="Resuelto"';
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
                        $i++;
                    }

                    echo  json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
            }else{
                $sql = 'SELECT f.nombres,f.orden,f.paqueteria,f.fecha,f.fecha_almacen,e.cajas,e.bolsas FROM folios f INNER JOIN empaquetado e ON f.orden = e.orden WHERE f.estatus = "Listo para salida" or f.estatus = "Esperando por guia" or f.estatus = "Medidas enviadas" or f.estatus = "Guia enviada"';
                $result = mysqli_query($con,$sql);
                $i=0;
                while($row = mysqli_fetch_assoc($result)){
                    $response[$i]  ['nombres'] = $row['nombres'];
                    $response[$i]['orden'] = $row['orden'];
                    $response[$i]['cajas'] = $row['cajas'];
                    $response[$i]['paqueteria'] = $row['paqueteria'];
                    $response[$i]['bolsas'] = $row['bolsas'];
                    $response[$i]['fecha'] = $row['fecha'];
                    $response[$i]['fecha_almacen'] = $row['fecha_almacen'];
                    $i++;
                }
                echo  json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
            }
            break;

        }
    //echo "Informacion".file_get_contents('php://input');

}else{
    echo "DB FOUND CONNECTED";
}
?>