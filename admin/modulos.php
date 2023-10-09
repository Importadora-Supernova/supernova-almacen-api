<?php
// conexion con base de datos 
include '../conexion/conn.php';

// declarar array para respuestas 
$response = array();

// insertamos cabeceras para permisos 

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type,Authorization,Accept, Access-Control-Request-Method");
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

            $nombre  = $_POST['nombre_modulo'];
            $ruta    = $_POST['ruta'];
            $icono   = $_POST['icono'];
            $estatus = $_POST['estatus'];

            if($nombre == '' || $ruta == '' || $icono == '' || $estatus == ''){
                header("HTTP/1.1 400");
                $response['status'] = 400;
                $response['mensaje'] = 'Debes agregar todos los campos, existen campos vacios';
                echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT); 
            }else{
                //preparar sentencia 
                if(!($sentencia = $con->prepare("INSERT INTO app_modulos_bodega (nombre_modulo,ruta,icono,estatus_modulo) VALUES (?,?,?,?)"))){
                    echo "Falló la preparación: (" . $con->errno . ") " . $con->error;
                }

                //agregar parametros y tipo de datos
                if(!$sentencia->bind_param("ssss",$nombre,$ruta,$icono,$estatus)){
                    echo "Falló la vinculación de parámetros: (" . $sentencia->errno . ") " . $sentencia->error;
                }

                
                if (!$sentencia->execute()) {
                    echo "Falló la ejecución: (".$sentencia->errno.") " . $sentencia->error;
                    header("HTTP/1.1 400");
                    $response['status'] = 400;
                    $response['mensaje'] = 'No se pudo Guardar el registro';
                    echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                    $con->close();                
                }else{
                    header("HTTP/1.1 200 OK");
                    $response['status'] = 200;
                    $response['mensaje'] = 'Registro creado correctamente';
                    echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                    $con->close();
                } 
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
            }else if(isset($_GET['admin'])){
                $sql = 'SELECT *FROM app_modulos_bodega  WHERE departamento="Admin"';   
                $result = mysqli_query($con,$sql);
                $i=0;
                while($row = mysqli_fetch_assoc($result)){
                    $response[$i]['id'] = $row['id_modulo'];
                    $response[$i]['nombre_modulo'] = $row['nombre_modulo'];
                    $response[$i]['ruta'] = $row['ruta'];
                    $response[$i]['icono'] = $row['icono'];
                    $response[$i]['estatus_modulo'] = $row['estatus_modulo'] == "1" ? true : false;
                    $response[$i]['departamento'] = $row['departamento'];
                    $response[$i]['posicion'] = $row['posicion'];
                    $i++;
                }
                echo  json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
            }else if(isset($_GET['almacen'])){
                    $sql = 'SELECT *FROM app_modulos_bodega  WHERE departamento=""';   
                    $result = mysqli_query($con,$sql);
                    $i=0;
                    while($row = mysqli_fetch_assoc($result)){
                        $response[$i]['id'] = $row['id_modulo'];
                        $response[$i]['nombre_modulo'] = $row['nombre_modulo'];
                        $response[$i]['ruta'] = $row['ruta'];
                        $response[$i]['icono'] = $row['icono'];
                        $response[$i]['estatus_modulo'] = $row['estatus_modulo'] == "1" ? true : false;
                        $response[$i]['departamento'] = $row['departamento'];
                        $response[$i]['posicion'] = $row['posicion'];
                        $response[$i]['addView'] = false;  
                        $response[$i]['addEdit'] = false;   
                        $i++;
                    }
                    echo  json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                 }else{
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