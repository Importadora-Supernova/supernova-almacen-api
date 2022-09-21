<?php
date_default_timezone_set('America/Mexico_City');
// conexion con base de datos 
include 'conexion/conn.php';

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
    
    //echo "Informacion".file_get_contents('php://input');

   $methodApi = $_SERVER['REQUEST_METHOD'];

   switch($methodApi){
       // metodo post 
       case 'POST':
                $_POST = json_decode(file_get_contents('php://input'),true);

                $con->autocommit(false);
                $sqlInsertPedido = 'INSERT INTO folio (orden,id_user,totales) VALUES ("'.$_POST['orden'].'",'.$_POST['id_user'].','.$_POST['totales'].')';
                $resInsertPedido = mysqli_query($con,$sqlInsertPedido);
                if($resInsertPedido){

                    $sqlSelectPedido = 'SELECT MAX(id_folio) as id_folio FROM folio';
                    $resPedido = mysqli_query($con,$sqlSelectPedido);

                    $fill = mysqli_fetch_assoc($resPedido);

                    if($fill){
                        $data = [];
                        $data = $_POST['pedido'];
                        $i=0;
                        $band = true;
                        while($i<count($data)){
                            $sql = 'INSERT INTO detalle_pedido(id_pedido,id_producto,cantidad) VALUES ('.$fill['id_folio'].','.$data[$i]['id_producto'].','.$data[$i]['cantidad'].')';
                            $res = mysqli_query($con,$sql);
                            if($res){
                                $i++;
                            }else{
                                echo 'no se pudo insertar';
                                $band = false;
                                $con->rollback();
                                break;
                            }
                            
                        }

                        if($band){
                            echo 'todo bien';
                            echo $i.'--'.count($data);
                            $con->commit();

                        }

                    }else{
                        $con->rollback();
                        echo 'no se pudo insertar 2';
                    }
                }else{
                    $con->rollback();
                    echo 'no se pudo insertar 3';
                }
               
                // $con->autocommit(false);

                // header("HTTP/1.1 200 OK");

                // $sqlUpdateOrigen = 'UPDATE almacen_producto SET cantidad=cantidad - '.$_POST['cantidad'].'  WHERE  id_almacen='.$_POST['id_almacen'].' AND id_producto='.$_POST['id_producto'].'';
                // $resUpdateOrigen = mysqli_query($con,$sqlUpdateOrigen);
                
                // $sqlSelectDestino = 'SELECT *FROM almacen_producto WHERE id_producto='.$_POST['id_producto'].' AND id_almacen='.$_POST['id_almacen_destino'].'';
                // $resSelectDestino = mysqli_query($con,$sqlSelectDestino);
                // $fill = mysqli_fetch_assoc($resSelectDestino);

                // $resUpdateDestino='';

                // if($fill){
                //      $sqlUpdateDestino = 'UPDATE almacen_producto SET cantidad=cantidad + '.$_POST['cantidad'].' WHERE id_almacen='.$_POST['id_almacen_destino'].'  AND id_producto='.$_POST['id_producto'].'';
                //      $resUpdateDestino = mysqli_query($con,$sqlUpdateDestino);
                // }else{
                //       $sqlUpdateDestino = 'INSERT INTO almacen_producto (id_almacen,id_producto,cantidad) VALUES ('.$_POST['id_almacen_destino'].','.$_POST['id_producto'].','.$_POST['cantidad'].')';
                //       $resUpdateDestino = mysqli_query($con,$sqlUpdateDestino);
                // }

                // $InsertHistorial = 'INSERT INTO traslados (id_almacen_origen,id_almacen_destino,id_producto,cantidad,id_usuario,fecha_created) VAlUES ('.$_POST['id_almacen'].','.$_POST['id_almacen_destino'].','.$_POST['id_producto'].','.$_POST['cantidad'].',1,"'.$_POST['fecha_created'].'")';
                // $resInsertHistorial = mysqli_query($con,$InsertHistorial);

                // if($resUpdateOrigen and $resUpdateDestino and $resInsertHistorial){
                //     $con->commit();
                //     header("HTTP/1.1 200 OK");
                //     $response['status'] = 200;
                //     $response['mensaje'] = 'Transacción ejecutada correctamente';
                //     echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                // }else{
                //     $con->rollback();
                //     header("HTTP/1.1 400");
                //     $response['status'] = 400;
                //     $response['mensaje'] = 'No se pudo ejecutar la transacción';
                //     echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                // }
       break;
       // metodo get 
       case 'GET':
        // para obtener un registro especifico
            if(isset($_GET['id'])){
                //
             } else{

             }
       break;
   }
}else{
    echo "DB FOUND CONNECTED";
}
?>