<?php
/*
json example
{
    "id_usuario":5228,
    "orden":"5228-7",
    "envio":"si",
    "paqueteria":"FEDEX",
    "cantidad":"360",
    "total_monto":75000,
    "estatus":"Envia su guia",
    "nombres":"Kenia",
    "apellidos":"Lara",  
    "correo":"kenialara@gmail.com",
    "telefono":"54785153",
    "ciudad":"Ciudad de Mexico",
    "colonia":"Casa azul",
    "estado":"Jalisco",
    "direccion":"Puerto madero 26",
    "codigoP":"45060",
    "num_orden":"7",
    "productos":[
        {"id":140,"codigo":"PE-10","precio":"60","cantidad":6},
        {"id":205,"codigo":"EL-72","precio":"150","cantidad":10},
        {"id":251,"codigo":"EL-78","precio":"320","cantidad":25},   
        {"id":403,"codigo":"PU-06","precio":"20","cantidad":40}
    ]
}
*/

// conexion con base de datos 
include '../conexion/conn.php';
require_once('../class/querys.php');    

$query = new Querys();

// declarar array para respuestas 
$response = array();
$errores  = array();
date_default_timezone_set('America/Mexico_City');
$fecha = date('Y-m-d H:i:s');

// insertamos cabeceras para permisos 

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");
header("Content-Type: JSON");
header('Content-Type: application/json;charset=utf-8'); 

if($con){
    
    $methodApi = $_SERVER['REQUEST_METHOD'];

    if($methodApi == 'POST'){
        // metodo post 
        //CONVERTIMOS A POST LOS DATOS RECIBIDOS EN  FORMATO JSON

        $con->autocommit(false);

        $_POST = json_decode(file_get_contents('php://input'),true);
        //asignamos datos a variables

        $id       = $_POST['id_usuario'];
        $orden    = $_POST['orden'];
        $envio    = $_POST['envio'];
        $idPa     = $_POST['paqueteria'];
        $cant     = $_POST['cantidad'];
        $totalP   = $_POST['total_monto'];
        $estat    = $_POST['estatus'];
        $nom      = $_POST['nombres'];
        $ape      = $_POST['apellidos'];
        $nombres  = $nom.' '.$ape;
        $email    = $_POST['correo'];
        $tlf      =  $_POST['telefono'];
        $ciud     = $_POST['ciudad'];
        $col      = $_POST['colonia'];
        $edo      = $_POST['estado'];
        $dir      = $_POST['direccion'];
        $codigo   = $_POST['codigoP'];
        $num_Orden = $_POST['num_orden'];

        $productos = $_POST['productos'];
        
        $flag = false;
        $i=0;

        foreach($productos as $item){
            $sql = 'INSERT INTO registro_usuario (id_usuario,id_producto,codigo,precio,cantidad,fecha,orden) VALUES (?,?,?,?,?,?,?)';
            $result = $query->insertRegistroUsuario($con,$sql,$item,$id,$orden,$fecha);
            if($result == 1){
                $flag = true;
            }else{
                $flag = false;
            }

            $i++;
        }

        //preparar sentencia sql
        $sql = 'CALL sp_procesar_compra(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)';
        $stmt = $con->prepare($sql);
        $stmt->bind_param('issssssssisssssssss',$id,$nombres,$orden,$envio,$fecha,$idPa,$cant,$totalP,$estat,$num_Orden,$nom,$ape,$email,$tlf,$ciud,$col,$edo,$dir,$codigo);
        $stmt->execute();
        $data = $stmt->store_result();
        while ($con->next_result());
        //$result = $stmt->get_result()->fetch_assoc();

        if($data && $flag){
            $sentencia = "UPDATE usuario SET orden='".$num_Orden."' WHERE id=".$id."";
            $resultado = mysqli_query($con,$sentencia);
            if($resultado > 0){
                $con->commit();
                header("HTTP/1.1 200 OK");
                $response['status'] = 200;
                $response['result'] = $result;
                $response['mensaje'] = 'Registro creado correctamente';
                echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                $con->close();
            }else{
                $con->rollback();
                header("HTTP/1.1 400");
                $response['status'] = 400;
                $response['mensaje'] = 'No se pudo Guardar el registro';
                echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT); 
                $con->close(); 
            }
        }else{
            $con->rollback();
            header("HTTP/1.1 400");
            $response['status'] = 400;
            $response['mensaje'] = 'No se pudo Guardar el registro';
            echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT); 
            $con->close(); 
        }
    }
//echo "Informacion".file_get_contents('php://input');

}else{
echo "DB FOUND CONNECTED";
}

?>