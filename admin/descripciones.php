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
            $sql = 'INSERT INTO admin_descripcion_caracteristica (id_caract,atributo,valor,fecha_created) VALUES ("'.$_POST['id_caract'].'","'.$_POST['atributo'].'","'.$_POST['valor'].'","'.$fecha.'")';
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

            break;
            case 'PUT':
            $_PUT = json_decode(file_get_contents('php://input'),true);
            $sql = 'UPDATE admin_categorias SET id_depa='.$_PUT['id_depa'].',nombre_categoria="'.$_PUT['nombre_categoria'].'",estatus_categoria="'.$_PUT['estatus_categoria'].'"  WHERE id_categoria='.$_GET['id'].'';
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
                $sqlDelete = 'DELETE FROM admin_descripcion_caracteristica WHERE id_descripcion='.$id.'';
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
