<?php
// conexion con base de datos 
include '../conexion/conn.php';
// declarar array para respuestas 
$response = array();
// insertamos cabeceras para permisos 

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept,Authorization, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");
header("Content-Type: JSON");
header('Content-Type: application/json;charset=utf-8'); 

// validamos si hay conexion 
if($con){
    
        $methodApi = $_SERVER['REQUEST_METHOD'];

        
            if($methodApi == 'GET'){
                    $sql = 'SELECT * FROM `folios` WHERE estatus="Listo para salida" or estatus="Esperando por guia" or estatus="Medidas enviadas" or estatus="Guia enviada"';
                    $result = mysqli_query($con,$sql);
                    $i=0;
                    while($row = mysqli_fetch_assoc($result)){
                        $response[$i]  ['cliente'] = $row['nombres'];
                        $response[$i]['orden'] = $row['orden'];
                        $response[$i]['estatus'] = $row['estatus'];
                        $response[$i]['paqueteria'] = $row['paqueteria'];
                        $response[$i]['fecha'] = $row['fecha'];
                        $i++;
                    }

                    echo  json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                 }

    //echo "Informacion".file_get_contents('php://input');

}else{
    echo "DB FOUND CONNECTED";
}
?>