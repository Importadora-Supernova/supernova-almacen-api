<?php
// conexion con base de datos 
include '../conexion/conn.php';
date_default_timezone_set('America/Mexico_City');
//incluir middleware


// declarar array para respuestas 
$response = array();

// insertamos cabeceras para permisos 

header('Access-Control-Allow-Origin:*');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");
header("Content-Type: JSON");
header('Content-Type: application/json;charset=utf-8'); 

$fecha = date('Y-m-d');

// validamos si hay conexion 
if($con){
        $methodApi = $_SERVER['REQUEST_METHOD'];


            if($methodApi == 'GET'){
                $sqlBanco = 'SELECT SUM(monto) as total_banco FROM pagos WHERE fecha LIKE "'.$_GET['fecha'].'%" AND banco="'.$_GET['banco'].'"';
                $result = mysqli_query($con,$sqlBanco);
                $fillBanco = mysqli_fetch_assoc($result);
                $total = $fillBanco['total_banco'];

                $total = $total == null ? 0 : $total;
                $response['total_banco'] = $total;

                header("HTTP/1.1 200 OK");
                echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
            }


            if($methodApi  == 'POST'){

                $_POST = json_decode(file_get_contents('php://input'),true);
                // $select = 'SELECT *FROM usuarios_almacen WHERE id_user_almacen=1';
                // $resultado = mysqli_query($con,$select);
                // $fila = mysqli_fetch_assoc($resultado);
                // $response['usuario'] = $fila;

                $sqlMontoTotal = 'SELECT SUM(monto) as total FROM pagos WHERE fecha LIKE "'.$_POST['fecha'].'%" ';
                $result = mysqli_query($con,$sqlMontoTotal);
                $fillMontoTotal = mysqli_fetch_assoc($result);
                $total = $fillMontoTotal['total'];

                $sqlOtro = 'SELECT SUM(monto) as total_otro FROM pagos WHERE fecha LIKE "'.$_POST['fecha'].'%" AND banco!="lemussa-bbva" AND banco!="website-bbva" AND banco!="isn-hsbc" AND banco!="isn-bbva" AND banco!="efectivo"';
                $resultOtro = mysqli_query($con,$sqlOtro);
                $fillOtro = mysqli_fetch_assoc($resultOtro);
                $totalOtro = $fillOtro['total_otro'];

                $sqlEfectivo = 'SELECT SUM(monto) as total_efectivo FROM pagos WHERE banco="efectivo" AND fecha LIKE "'.$_POST['fecha'].'%"';
                $resultEfectivo = mysqli_query($con,$sqlEfectivo);
                $fillEfectivo = mysqli_fetch_assoc($resultEfectivo);
                $totalEfectivo = $fillEfectivo['total_efectivo'];

                $sqlExtras = 'SELECT SUM(venta_paqueteria) as total_paqueteria, SUM(iva) as total_iva,SUM(seguro) as seguro, SUM(saldo_pendiente) as total_saldo_pendiente FROM folios WHERE  fecha_pago LIKE "'.$_POST['fecha'].'%"';
                $resultExtra = mysqli_query($con,$sqlExtras);
                $fillExtras = mysqli_fetch_assoc($resultExtra);

                $sqlTotalVenta =  'SELECT SUM(cantidad*precio) as total_venta FROM `registro_usuario` WHERE fecha_procesado LIKE "'.$_POST['fecha'].'%" AND estatus="Pagado"';
                $resultVenta = mysqli_query($con,$sqlTotalVenta);
                $fillVenta = mysqli_fetch_assoc($resultVenta);

                $sqlCosto = 'SELECT SUM(p.precio_costo*r.cantidad) as total_costo FROM productos p INNER JOIN registro_usuario r ON p.id = r.id_producto WHERE r.estatus="Pagado" AND r.fecha_procesado LIKE "'.$_POST['fecha'].'%"';
                $resultCosto = mysqli_query($con,$sqlCosto);
                $fillCosto = mysqli_fetch_assoc($resultCosto);

                $totalPaqueteria = $fillExtras['total_paqueteria'];
                $totalIva = $fillExtras['total_iva'];
                $totalSeguro = $fillExtras['seguro'];
                $totalSaldoPendiente = $fillExtras['total_saldo_pendiente'];
                $totalVenta = $fillVenta['total_venta'];
                $totalCosto = $fillCosto['total_costo'];
                

                $total = $total == null ? 0 : $total;
                $totalOtro = $totalOtro == null ? 0 : $totalOtro;
                $totalEfectivo = $totalEfectivo == null ? 0 : $totalEfectivo;
                $totalPaqueteria = $totalPaqueteria == null ? 0 : $totalPaqueteria;
                $totalIva = $totalIva == null ? 0 : $totalIva;
                $totalSeguro = $totalSeguro == null ? 0 : $totalSeguro;
                $totalSaldoPendiente = $totalSaldoPendiente == null ? 0 : $totalSaldoPendiente;
                $bancos = $total - ($totalOtro + $totalEfectivo);
                $totalExtras = $totalPaqueteria + $totalIva + $totalSeguro;
                $totalVenta = $totalVenta == null ? 0 : $totalVenta;
                $totalCosto = $totalCosto == null ? 0 : $totalCosto;

                $ganancia = $totalVenta - $totalCosto;


                $response['total'] = $total;
                $response['total_otro'] = round($totalOtro,2);
                $response['total_efectivo'] = round($totalEfectivo,2);
                $response['total_bancos'] = round($bancos,2);
                $response['total_paqueteria'] = round($totalPaqueteria,2);
                $response['total_iva'] = round($totalIva,2);
                $response['total_seguro'] = round($totalSeguro,2);
                $response['total_saldo_pendiente'] = round($totalSaldoPendiente,2);
                $response['total_extras'] = round($totalExtras,2);
                $response['total_venta'] = round($totalVenta,2);
                $response['ganancia'] = round($ganancia,21);

                header("HTTP/1.1 200 OK");
                echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
            }  
    //echo "Informacion".file_get_contents('php://input');

}else{
    echo "DB FOUND CONNECTED";
}
?>