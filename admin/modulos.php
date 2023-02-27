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

             $sqlInsert = 'INSERT INTO app_modulos_bodega (nombre_modulo,ruta,icono,estatus_modulo) VALUES ("'.$_POST['nombre_modulo'].'","'.$_POST['ruta'].'","'.$_POST['icono'].'","'.$_POST['estatus'].'")';
             $result = mysqli_query($con,$sqlInsert);

             if($result){
                header("HTTP/1.1 200");
                $response['mensaje'] = 'EL modulo se registro correctamente';
                echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
             }else{
                header("HTTP/1.1 400");
                $response['mensaje'] = 'Ocurrio un error,No se podo completar la accion';
                echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
             }
             //
            break;
            // metodo get 
            case 'GET':
             // para obtener un registro especifico
             if(isset($_GET['id'])){ 
                 $sql = 'SELECT *FROM app_modulos_bodega  WHERE id_modulo='.$_GET['id'].'';
                 $result = mysqli_query($con,$sql);
                 $i=0;
                 while($row = mysqli_fetch_assoc($result)){
                    $response['id'] = $row['id_modulo'];
                    $response['nombre_modulo'] = $row['nombre_modulo'];
                    $response['ruta'] = $row['ruta'];
                    $response['icono'] = $row['icono'];
                    $response['estatus_modulo'] = $row['estatus_modulo'] == "1" ? true : false;
                    $response['posicion'] = $row['posicion'];
                     $i++;
                 }

                 echo  json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
              } else {
                    $sqlPagados = 'SELECT * FROM app_modulos_bodega';
                    $resultPagados = mysqli_query($con,$sqlPagados);
                    $i=0;
                    while($row = mysqli_fetch_assoc($resultPagados)){
                        $response[$i]['id'] = $row['id_modulo'];
                        $response[$i]['nombre_modulo'] = $row['nombre_modulo'];
                        $response[$i]['ruta'] = $row['ruta'];
                        $response[$i]['icono'] = $row['icono'];
                        $response[$i]['estatus_modulo'] = $row['estatus_modulo'] == "1" ? true : false;
                        $response[$i]['posicion'] = $row['posicion'];
                        $response[$i]['addView'] = false;  
                        $response[$i]['addEdit'] = false;   
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