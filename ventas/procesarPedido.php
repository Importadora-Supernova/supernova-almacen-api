<?php
//error_reporting(0);
// conexion con base de datos 
include '../conexion/conn.php';
include '../middleware/midleware.php';
date_default_timezone_set('America/Mexico_City');
$fecha_actual = date('Y-m-d H:i:s');

// declarar array para respuestas 
$response = array();
// insertamos cabeceras para permisos 
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Authorization, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");
header("Content-Type: JSON");
header('Content-Type: application/json;charset=utf-8'); 
// validamos si hay conexion 
if($con){
    if($validate === 'validado'){
        //declaramos array para almacenes
        $almacenes = array();
        //consultamos los almacenes disponibles
        $sqlAlmacenes = 'SELECT id_almacen FROM almacenes';
        $result = mysqli_query($con,$sqlAlmacenes);
        $h=0;
        while($row = mysqli_fetch_assoc($result)){
            $almacenes[$h] = $row['id_almacen'];
            $h++;
        }
        //funcion que permita consultar que cantidad  existe por producto de la 
        //orden en cada almacen,retorna cantidad y si no existe retorna 0
        function consultarProductoP1($con,$almacen,$producto){
            $consulta = 'SELECT * FROM almacen_producto WHERE id_almacen="'.$almacen.'" AND id_producto="'.$producto.'"';
            $result = mysqli_query($con,$consulta);
            $row = mysqli_fetch_assoc($result);
            $cantidad = 0;
            if($row){
                $cantidad = $row['cantidad'];
            }
            return $cantidad;
        }   
        //funcion que permite actualizar las tablas de productos y almacen , y crear
        // un nuevo registro en almacen descuento
        //esta sujeto a una transaccion si no es exitosa retorna false caso contrario true
        function actualizarTablas($con,$orden,$almacen,$cantidad,$producto){
            $flag = false;
            $sql = 'INSERT INTO almacen_descuentos (orden,id_almacen,id_producto,cantidad) VALUES ("'.$orden.'",'.$almacen.','.$producto.','.$cantidad.')';
            $result = mysqli_query($con,$sql);
            $sqlUpdate = 'UPDATE almacen_producto SET cantidad=cantidad-'.$cantidad.' WHERE id_almacen='.$almacen.' AND id_producto='.$producto.'';
            $resultUpdate = mysqli_query($con,$sqlUpdate);
            $sqlUpdate2 = 'UPDATE productos SET almacen=almacen-'.$cantidad.' WHERE id='.$producto.'';
            $result2  = mysqli_query($con,$sqlUpdate2);
            if($result && $resultUpdate && $result2){
                $flag = true;
            }else{
                $flag = false;
            }
            return $flag;
        }
        //verificamos que el metodo sea POST
        $methodApi = $_SERVER['REQUEST_METHOD'];
        if($methodApi == 'POST'){
            $_POST = json_decode(file_get_contents('php://input'),true);
            //ASIGNAMOS DATOS datos de pedido y orden en variables, bandera y array  para la cantidad de productos disponibles
            $data     =  $_POST['datos'];
            $methods  =  $_POST['metodos'];
            $orden    =  $_POST['orden'];
            $band     =  true;
            $flag     =  true;
            $i        =  0;
            $k        =  0;
            $cuentas  =  [];

            $sqltoken = 'SELECT token_venta  FROM tokens_acceso WHERE id=1';
            $restoken = mysqli_query($con,$sqltoken);
            $fill = mysqli_fetch_assoc($restoken);

            if($fill['token_venta'] == $_POST['token']){

                $con->autocommit(false);
                while($i<count($data))
                {
                    $acumulador=0;
                    for($j=0;$j<count($almacenes);$j++)
                    {
                        $consult = consultarProductoP1($con,$almacenes[$j],$data[$i]['id_producto']); 
                        if($consult >= $data[$i]['cantidad'])
                        {
                            $producto = actualizarTablas($con,$orden,$almacenes[$j],$data[$i]['cantidad'],$data[$i]['id_producto']);
                            if($producto == false){
                                $band = false;
                            }
                            //echo 'descontar del almacem '.$almacenes[$j];
                            //echo "\n";
                            break;
                        }
                        $cuentas += [ "almacen".$j."" => intval($consult) ];
                    }
                    if($j == 5)
                    {
                        $cantidades=0;
                        $resto = $data[$i]['cantidad'];
                        for($k=0;$k<count($cuentas);$k++)
                        {
                            
                            if($cuentas['almacen'.$k.''] > 0){
                            // echo $cuentas['almacen'.$k.''].'en el almacen'.$almacenes[$k];
                                //echo "\n";
                                $cantidades += $cuentas['almacen'.$k.''];
                                if($cantidades < $data[$i]['cantidad']){
                                    $producto = actualizarTablas($con,$orden,$almacenes[$k],$cuentas['almacen'.$k.''],$data[$i]['id_producto']);
                                    if($producto == false){
                                        $band = false;
                                    }
                                    //echo 'se va a descontar '.$cuentas['almacen'.$k.''].'en el almacen'.$almacenes[$k];
                                    //echo "\n";
                                }else{
                                    if($cuentas['almacen'.$k.''] > $resto){
                                        $producto = actualizarTablas($con,$orden,$almacenes[$k],$resto,$data[$i]['id_producto']);
                                        if($producto == false){
                                            $band = false;
                                        }
                                    // echo ' descontamos el resto'.$resto;
                                        break;
                                    // echo "\n";
                                    }
                                }
                                $resto = $resto -  $cuentas['almacen'.$k.''];
                            }
                        }
                    }

                    if(!$band){
                        $con->rollback();
                    }else{
                        $i++;
                    }
                }

                //actualizar registro usuario 
                $sqlRegistro_usuario = 'UPDATE registro_usuario SET estatus="Pagado", fecha_procesado="'.$fecha_actual.'" WHERE orden="'.$orden.'"';
                $resulRegistro_usuario = mysqli_query($con,$sqlRegistro_usuario);

                //actualizar folios
                $sqlFolios = 'UPDATE folios SET estatus="Pagado",fecha_procesado="'.$fecha_actual.'",fecha_pago="'.$fecha_actual.'",venta_paqueteria="'.$_POST['venta_paqueteria'].'",saldo_pendiente="'.$_POST['saldo_pendiente'].'",vendedora="'.$_POST['vendedora'].'",iva="'.$_POST['iva'].'",seguro="'.$_POST['seguro'].'",fecha_entrega="'.$_POST['fecha_entrega'].'",monto="'.$_POST['monto'].'",cajas="'.$_POST['cajas'].'",nota="'.$_POST['nota'].'",efectivo="'.$_POST['efectivo'].'" WHERE orden="'.$orden.'"';
                $resultFolios = mysqli_query($con,$sqlFolios);

                //insertar nuevos registros en pagos
                while($k<count($methods))
                {
                    $sqlInsertPago = 'INSERT INTO pagos (orden,fecha,monto,banco,metodos_pago) VALUES ("'.$orden.'","'.$fecha_actual.'",'.$methods[$k]['monto'].',"'.$methods[$k]['banco'].'","'.$methods[$k]['metodo'].'")';
                    $resultPago = mysqli_query($con,$sqlInsertPago);

                    if($resultPago){
                        $k++;
                    }else{
                        $flag = false;
                        break;
                    }
                }

                if($band && $resulRegistro_usuario && $resultFolios && $flag){
                    $con->commit();
                    header("HTTP/1.1 200 OK");
                    $response['status'] = 200;
                    $response['mensaje'] = 'El proceso de pago se ejecuto exitosamente';
                    echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                }else{
                    $con->rollback();
                    header("HTTP/1.1 400");
                    $response['status'] = 400;
                    $response['mensaje'] = 'Ocurrio un error en el proceso intente nuevamente';
                    echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                }
            }else{
                header("HTTP/1.1 401");
                $response['mensaje'] = 'El token es incorrecto';
                echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
            }

            
        }
    }else{
        header("HTTP/1.1 201");
        $response['status'] = 401;
        $response['mensaje'] = 'Token '.$validate;
        echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
    }
}else{
    echo "DB FOUND CONNECTED";
}
?>