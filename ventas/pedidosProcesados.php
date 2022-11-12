<?php
// conexion con base de datos 
include '../conexion/conn.php';
include '../middleware/midleware.php';
date_default_timezone_set('America/Mexico_City');
//incluir middleware


// declarar array para respuestas 
$response = array();

// insertamos cabeceras para permisos 

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type,Authorization, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");
header("Content-Type: JSON");
header('Content-Type: application/json;charset=utf-8'); 

$fecha = date('Y-m-d');

// validamos si hay conexion 
if($con){
        
        if($validate === 'validado'){
            $methodApi = $_SERVER['REQUEST_METHOD'];
            switch($methodApi){
                // metodo post 
                case 'POST':
                // post
                $_POST = json_decode(file_get_contents('php://input'),true);

                
                break;
                // metodo get 
                case 'GET':
                // para obtener un registro especifico
                    if(isset($_GET['fecha'])){
                    $sql = 'SELECT  *FROM folios WHERE estatus!="Sin procesar" AND pendiente!="Pendiente" AND fecha_procesado LIKE "'.$_GET['fecha'].'%"';
                    $result = mysqli_query($con,$sql);
                    $i=0;
                    while($row = mysqli_fetch_assoc($result)){      
                        $response[$i]['id_usuario'] =  $row['id_usuario'];
                        $response[$i]['nombres'] =  $row['nombres'];
                        $response[$i]['orden'] =  $row['orden'];
                        $response[$i]['paqueteria'] =  $row['paqueteria'];
                        $response[$i]['cantidad'] =  $row['cantidad'];
                        $response[$i]['total'] =  $row['total'];
                        $response[$i]['fecha'] =  $row['fecha_procesado'];
                        $response[$i]['estatus'] =  $row['estatus'];
                        $response[$i]['venta_paqueteria'] =  $row['venta_paqueteria'];
                        $response[$i]['saldo_pendiente'] = $row['saldo_pendiente'] == "" ? 0 :$row['saldo_pendiente']; 
                        $i++;          
                    }
                    header("HTTP/1.1 200 OK");
                    echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                    }else if(isset($_GET['orden'])){
                        $sql = 'SELECT  *FROM folios WHERE estatus!="Sin procesar" AND pendiente!="Pendiente" AND orden="'.$_GET['orden'].'"';
                        $result = mysqli_query($con,$sql);
                        $i=0;
                        while($row = mysqli_fetch_assoc($result)){      
                            $response[$i]['id_usuario'] =  $row['id_usuario'];
                            $response[$i]['nombres'] =  $row['nombres'];
                            $response[$i]['orden'] =  $row['orden'];
                            $response[$i]['paqueteria'] = $row['envio'] == 'No' ? 'Entrega en bodega' : $row['paqueteria'];
                            $response[$i]['paqueteria'] =  $row['paqueteria'];
                            $response[$i]['cantidad'] =  $row['cantidad'];
                            $response[$i]['total'] =  $row['total'];
                            $response[$i]['fecha'] =  $row['fecha_procesado'];
                            $response[$i]['estatus'] =  $row['estatus'];
                            $response[$i]['venta_paqueteria'] =  $row['venta_paqueteria'];
                            $response[$i]['saldo_pendiente'] = $row['saldo_pendiente'] == "" ? 0 :$row['saldo_pendiente']; 
                            $i++;          
                        }
                        header("HTTP/1.1 200 OK");
                        echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                    }else{
                        $sql = 'SELECT  *FROM folios WHERE estatus!="Sin procesar" AND pendiente!="Pendiente" AND fecha_procesado LIKE "'.$fecha.'%"';
                        $result = mysqli_query($con,$sql);
                        $i=0;
                        while($row = mysqli_fetch_assoc($result)){
                            $response[$i]['id_usuario'] =  $row['id_usuario'];
                            $response[$i]['nombres'] =  $row['nombres'];
                            $response[$i]['orden'] =  $row['orden'];
                            $response[$i]['paqueteria'] =  $row['paqueteria'];
                            $response[$i]['cantidad'] =  $row['cantidad'];
                            $response[$i]['total'] =  $row['total'];
                            $response[$i]['fecha'] =  $row['fecha_procesado'];
                            $response[$i]['estatus'] =  $row['estatus'];
                            $response[$i]['venta_paqueteria'] =  $row['venta_paqueteria'];    
                            $response[$i]['saldo_pendiente'] = $row['saldo_pendiente'] == "" ? 0 :$row['saldo_pendiente']; 
                            $i++;
                        }
                        header("HTTP/1.1 200 OK");
                        echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                    }
                break;
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