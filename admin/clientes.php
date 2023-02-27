<?php
// conexion con base de datos 
include '../conexion/conn.php';

// declarar array para respuestas 
$response = array();

// insertamos cabeceras para permisos 

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type,Authorization, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");
header("Content-Type: JSON");
header('Content-Type: application/json;charset=utf-8'); 

date_default_timezone_set('America/Mexico_City');
$fecha = date('Y-m-d H:i:s');


// validamos si hay conexion 
if($con){
    
        $methodApi = $_SERVER['REQUEST_METHOD'];

        switch($methodApi){
            // metodo get 
            case 'GET':
                
                $sql = 'SELECT u.id,u.nombre,u.apellido,u.direccion,u.correo,f.orden,COUNT(*) AS total_pedidos FROM usuario u INNER JOIN folios f ON u.id = f.id_usuario GROUP BY f.id_usuario ORDER BY total_pedidos DESC';
                $resultSql = mysqli_query($con,$sql);
                $i=0;
                while($row = mysqli_fetch_assoc($resultSql)){
                    $response[$i]['id'] = $row['id'];
                    $response[$i]['fullName'] = $row['nombre'].' '.$row['apellido'];
                    $response[$i]['direccion'] = $row['direccion'];
                    $response[$i]['correo'] = $row['correo'];
                    $response[$i]['orden'] = $row['orden'];
                    $response[$i]['total_pedidos'] = $row['total_pedidos'];
                    $i++;
                }
                echo  json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT); 
            break;
            case 'POST':
                $_POST = json_decode(file_get_contents('php://input'),true);
                // declarar array para respuestas 
                $arrayCliente         = array();
                $arrayProductoCliente = array();
                $arraySinProcesar     = array();
                $arrayOrdenesCliente  = array();

                $sqlProductosCliente = 'SELECT codigo,SUM(cantidad) as cantidad,orden FROM registro_usuario WHERE id_usuario="'.$_POST['id_usuario'].'" and fecha_procesado!="" GROUP BY codigo order by cantidad desc';
                $resultProductos     = mysqli_query($con,$sqlProductosCliente);
                $i=0;
                while($row = mysqli_fetch_assoc($resultProductos)){
                    $arrayProductoCliente[$i]['codigo']    = $row['codigo'];
                    $arrayProductoCliente[$i]['cantidad']  = $row['cantidad'];
                    $arrayProductoCliente[$i]['orden']     = $row['orden'];
                    $i++;
                }

                $sqlSinProcesar = 'SELECT u.id,u.nombre,u.apellido,u.direccion,u.correo,u.telefono,u.colonia,u.ciudad,u.estado,u.codigop,u.rfc,COUNT(*) AS total_pedidos_sin_procesar FROM usuario u INNER JOIN folios f ON u.id = f.id_usuario WHERE f.fecha_procesado = "" AND u.id = "'.$_POST['id_usuario'].'" GROUP BY f.id_usuario';
                $resultSinProcesar = mysqli_query($con,$sqlSinProcesar);
                while($row = mysqli_fetch_assoc($resultSinProcesar)){
                    $arraySinProcesar['id']                             = $row['id'];
                    $arraySinProcesar['nombre']                         = $row['nombre'];
                    $arraySinProcesar['apellido']                       = $row['apellido'];
                    $arraySinProcesar['direccion']                      = $row['direccion'];
                    $arraySinProcesar['correo']                         = $row['correo'];
                    $arraySinProcesar['telefono']                       = $row['telefono'];
                    $arraySinProcesar['colonia']                        = $row['colonia'];
                    $arraySinProcesar['ciudad']                         = $row['ciudad'];
                    $arraySinProcesar['estado']                         = $row['estado'];
                    $arraySinProcesar['codigop']                        = $row['codigop'];
                    $arraySinProcesar['rfc']                            = $row['rfc'];
                    $arraySinProcesar['total_pedidos_sin_procesar']     = $row['total_pedidos_sin_procesar'];
                }

                $sqlOrdenesCliente = 'SELECT f.id,f.orden,f.nombres,f.paqueteria,f.cantidad,f.total,f.fecha_procesado,f.fecha FROM usuario u RIGHT JOIN folios f ON u.id = f.id_usuario WHERE u.id = "'.$_POST['id_usuario'].'" ORDER BY f.id DESC';
                $resultOrdenes     = mysqli_query($con,$sqlOrdenesCliente);
                $j=0;
                while($row = mysqli_fetch_assoc($resultOrdenes)){
                    $arrayOrdenesCliente[$j]['id']    = $row['id'];
                    $arrayOrdenesCliente[$j]['orden']  = $row['orden'];
                    $arrayOrdenesCliente[$j]['nombres']     = $row['nombres'];
                    $arrayOrdenesCliente[$j]['paqueteria']     = $row['paqueteria'];
                    $arrayOrdenesCliente[$j]['cantidad']     = $row['cantidad'];
                    $arrayOrdenesCliente[$j]['total']     = $row['total'];
                    $arrayOrdenesCliente[$j]['fecha_procesado']     = $row['fecha_procesado'];
                    $arrayOrdenesCliente[$j]['fecha']     = $row['fecha'];
                    $arrayOrdenesCliente[$j]['procesado']     = $row['fecha_procesado'] == '' ? false : true;
                    $j++;
                }
                $arrayCliente['sin_procesar'] = $arraySinProcesar;
                $arrayCliente['productos_clientes'] = $arrayProductoCliente;
                $arrayCliente['ordenes'] = $arrayOrdenesCliente;
                header("HTTP/1.1 200 OK");

                echo  json_encode($arrayCliente,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT); 

                //SELECT codigo,SUM(cantidad) as cantidad,orden FROM registro_usuario WHERE id_usuario='2145' and fecha_procesado!='' GROUP BY codigo order by cantidad asc;

                //SELECT u.id,u.colonia,u.ciudad,u.estado,u.codigop,u.rfc,COUNT(*) AS total_pedidos_sin_procesar FROM usuario u INNER JOIN folios f ON u.id = f.id_usuario WHERE f.fecha_procesado = "" AND u.id = "'.$_POST['id_usuario'].'" GROUP BY f.id_usuario;

                //SELECT f.id,f.orden,f.nombres,f.paqueteria,f.cantidad,f.total,f.fecha_procesado,f.fecha FROM usuario u RIGHT JOIN folios f ON u.id = f.id_usuario WHERE u.id = "'.$_POST['is_usuario'].'" ORDER BY f.id DESC;
            default:
            break;
    }
    //echo "Informacion".file_get_contents('php://input');
}else{
    echo "DB FOUND CONNECTED";
}
?>  