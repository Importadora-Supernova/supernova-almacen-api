<?php
// conexion con base de datos 
include '../conexion/conn.php';
//incluir middleware


// declarar array para respuestas 
$response        = array();
$caracteristicas = array();
$descriptions    = array();

date_default_timezone_set('America/Mexico_City');
$fecha = date('Y-m-d H:i:s');

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
            $sql = 'INSERT INTO admin_producto_caracteristica (id_producto,nombre_caracteristica,fecha_created) VALUES ("'.$_POST['id_producto'].'","'.$_POST['nombre_caracteristica'].'","'.$fecha.'")';
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

                $sql = 'SELECT  *FROM admin_producto_caracteristica WHERE id_producto='.$_GET['id'].'';
                $result = mysqli_query($con,$sql);
                $i=0;

                while($row = mysqli_fetch_assoc($result)){
                    $caracteristicas[$i]['id_caracteristica'] = $row['id_caracteristica'];
                    $caracteristicas[$i]['id_producto'] = $row['id_producto'];
                    $caracteristicas[$i]['nombre_caracteristica'] = $row['nombre_caracteristica'];
                    $caracteristicas[$i]['fecha_created'] = $row['fecha_created'];
                    $caracteristicas[$i]['created'] = false;
                    $caracteristicas[$i]['addatributo'] = "";
                    $caracteristicas[$i]['addvalor'] = "";

                    $sqlDescription = 'SELECT *FROM admin_descripcion_caracteristica WHERE id_caract='.$row['id_caracteristica'].'';
                    $resultDes = mysqli_query($con,$sqlDescription);
                    $j=0;
                    while($fill = mysqli_fetch_assoc($resultDes)){
                        $descriptions[$j]['id_descripcion'] = $fill['id_descripcion'];
                        $descriptions[$j]['atributo'] = $fill['atributo'];
                        $descriptions[$j]['valor'] = $fill['valor'];
                        $j++;
                    }
                    $caracteristicas[$i]['descripciones'] = $descriptions;
                    $descriptions    = [];    
                    $i++;
                }

                $response['caracteristicas'] = $caracteristicas;
                
                echo json_encode($response,JSON_PRETTY_PRINT);
            } 
            break;
            case 'PUT':
            $_PUT = json_decode(file_get_contents('php://input'),true);
            $sql = 'UPDATE admin_producto_caracteristica SET nombre_caracteristica="'.$_PUT['nombre_caracteristica'].'"  WHERE id_caracteristica='.$_GET['id'].'';
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
            case 'DELETE':
                $id = $_GET['id'];
                $sqlDelete = 'DELETE FROM admin_producto_caracteristica WHERE id_caracteristica='.$id.'';
                $result = mysqli_query($con,$sqlDelete);
                if($result){
                    header("HTTP/1.1 200 OK");
                    $response['status'] = 200;
                    $response['mensaje'] = 'Registro eliminados correctamente';
                    echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                }else{
                    header("HTTP/1.1 400");
                    $response['status'] = 400;
                    $response['mensaje'] = 'No se pudo eliminar el registro';
                    echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                }
            }
    //echo "Informacion".file_get_contents('php://input');

}else{
    echo "DB FOUND CONNECTED";
}
