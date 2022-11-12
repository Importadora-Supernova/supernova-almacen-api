<?php
// conexion con base de datos 
include '../conexion/conn.php';
//incluir middleware


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
             //echo "guardar informacion data: =>".json_encode($_POST);
            $sql = 'INSERT INTO admin_paqueterias (nombre_paqueteria,descripcion,color,estatus_paqueteria) VALUES ("'.$_POST['nombre_paqueteria'].'","'.$_POST['descripcion'].'","'.$_POST['color'].'","'.$_POST['estatus'].'")';
            $result = mysqli_query($con,$sql);
            if($result){
                header("HTTP/1.1 200 OK");
                $response['status'] = 200;
                $response['mensaje'] = 'Registro creado correctamente';
                echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
            }else{
                header("HTTP/1.1 400");
                $response['status'] = 400;
                $response['mensaje'] = 'No se pudo Guardar el registro';
                echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
            }
            break;
            // metodo get 
            case 'GET':
             // para obtener un registro especifico
            if(isset($_GET['id'])){
                 $sql = 'SELECT  *FROM admin_paqueterias WHERE id_paqueteria='.$_GET['id'].'';
                $result = mysqli_query($con,$sql);
                $i=0;
                while($row = mysqli_fetch_assoc($result)){
                    $response['id_paqueteria'] = $row['id_paqueteria'];
                    $response['nombre_paqueteria'] = $row['nombre_paqueteria'];
                    $response['estatus'] = $row['estatus_paqueteria'] == "1" ? true : false;
                    $response['descripcion'] = $row['descripcion'];
                    $response['color'] = $row['color'];
                    $i++;
                }
                echo json_encode($response,JSON_PRETTY_PRINT);
            } else{
                  // es para obtener todos los registros 
                $sql = 'SELECT *FROM admin_paqueterias';
                $result = mysqli_query($con,$sql);
                $i=0;
                while($row = mysqli_fetch_assoc($result)){
                    $response[$i]['id_paqueteria'] = $row['id_paqueteria'];
                    $response[$i]['nombre_paqueteria'] = $row['nombre_paqueteria'];
                    $response[$i]['estatus'] = $row['estatus_paqueteria'] == "1" ? true : false;
                    $response[$i]['descripcion'] = $row['descripcion'];
                    $response[$i]['color'] = $row['color'];
                    $i++;
                }
                // $variable = md5('    ');
                // echo $variable;
                echo  json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);  
            }
            break;
            case 'PUT':
            $_PUT = json_decode(file_get_contents('php://input'),true);
            $sql = 'UPDATE admin_paqueterias SET nombre_paqueteria="'.$_PUT['nombre_paqueteria'].'",descripcion="'.$_PUT['descripcion'].'",color="'.$_PUT['color'].'", estatus_paqueteria="'.$_PUT['estatus'].'"   WHERE id_paqueteria='.$_GET['id'].'';
            $result = mysqli_query($con,$sql);

            if($result){
                header("HTTP/1.1 200 OK");
                $response['status'] = 200;
                $response['mensaje'] = 'Registro actualizado correctamente';
                echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
            }else{
                header("HTTP/1.1 400");     
                $response['status'] = 400;
                $response['mensaje'] = 'No se pudo actualizar el registro';
                echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
            }

            break;

        }
    //echo "Informacion".file_get_contents('php://input');

}else{
    echo "DB FOUND CONNECTED";
}
?>