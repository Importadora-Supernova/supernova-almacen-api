<?php
// conexion con base de datos 
include '../conexion/conn.php';
date_default_timezone_set('America/Mexico_City');
//incluir middleware


// declarar array para respuestas 
$newArray = array();
$user = array();
$response = array();

// insertamos cabeceras para permisos 

header('Access-Control-Allow-Origin: *');
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
            
            $consulta = 'SELECT *FROM  usuario WHERE id="'.$_GET['id'].'"';
            $result = mysqli_query($con,$consulta);
            while($row = mysqli_fetch_assoc($result)){      
                $user['id_usuario'] =  $row['id'];
                $user['nombre'] =  $row['nombre'];
                $user['apellido'] =  $row['apellido'];
                $user['direccion'] =  $row['direccion'];
                $user['colonia'] =  $row['colonia'];
            }
            $consulta2 = 'SELECT *FROM  folios WHERE id_usuario="'.$_GET['id'].'"';
            $result2 = mysqli_query($con,$consulta2);
            $i=0;
            while($row = mysqli_fetch_assoc($result2)){      
                $response[$i]['orden'] =  $row['orden'];
                $response[$i]['paqueteria'] =  $row['paqueteria'];
                $response[$i]['estatus'] =  $row['estatus'];
                $i++;
            }

            $newArray['cliente'] = $user;
            $newArray['folios'] = $response;
            header("HTTP/1.1 200 OK");

            // $consulta = 'SELECT *FROM  usuario WHERE id="'.$_GET['id'].'"';
            // $result = mysqli_query($con,$consulta);
            // while($row = mysqli_fetch_assoc($result)){      
            //     $user['id_usuario'] =  $row['id'];
            //     $user['nombre'] =  $row['nombre'];
            //     $user['apellido'] =  $row['apellido'];
            //     $user['direccion'] =  $row['direccion'];
            //     $user['colonia'] =  $row['colonia'];
            // }
            // $consulta = 'SELECT p.id,p.nombre,p.codigo,p.descripcion,p.preciov,i.ruta,i.a FROM productos p LEFT JOIN img i ON p.id = i.id_producto';
            // $result = mysqli_query($con,$consulta);
            // $i=0;
            // $flag=0;
            // while($row = mysqli_fetch_assoc($result)){      
            //     if($flag != $row['id']){
            //         $newArray[$i]['id'] = $row['id'];
            //         $newArray[$i]['nombre'] = $row['nombre'];
            //         $newArray[$i]['codigo'] = $row['codigo'];
            //         $newArray[$i]['ruta'] = $row['ruta'];
            //         $newArray[$i]['preciov'] = $row['preciov'];
            //         $newArray[$i]['descripcion'] = $row['descripcion'];
            //         $newArray[$i]['a'] = $row['a'];
            //         $i++;
            //     }
            //     $flag = $row['id'];
                
            // }
            echo json_encode($newArray, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
        
        }


}else{
    echo "DB FOUND CONNECTED";
}
?>