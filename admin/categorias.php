<?php
// conexion con base de datos 
include '../conexion/conn.php';
//incluir middleware


// declarar array para respuestas 
$response = array();

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
            //declarando variables 
            //pasando datos del post convertido a partir del json
            $id_depa = $_POST['id_depa'];
            $nombre  = $_POST['nombre_categoria'];
            $estatus = $_POST['estatus_categoria'];

            if(is_null($id_depa) || $nombre == '' || $estatus == ''){
                header("HTTP/1.1 400");
                $response['status'] = 400;
                $response['mensaje'] = 'Debes agregar todos los campos, existen campos vacios';
                echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT); 
            }else{
                //preparando sentencia
                if(!($sentencia = $con->prepare("INSERT INTO admin_categorias (id_depa,nombre_categoria,estatus_categoria,fecha_created) VALUES(?,?,?,?)"))){
                    echo "Falló la preparación: (" . $con->errno . ") " . $con->error;
                }
                //pasando parametros a la sentencia
                if(!$sentencia->bind_param("isss", $id_depa,$nombre,$estatus,$fecha)){
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

            break;
            // metodo get 
            case 'GET':
             // para obtener un registro especifico
            if(isset($_GET['id'])){
                $sql = 'SELECT  c.id_categoria,c.nombre_categoria,c.estatus_categoria,c.id_depa,c.fecha_created,d.nombre_departamento FROM admin_categorias c INNER JOIN admin_departamentos d ON c.id_depa = d.id_departamento WHERE c.id_depa="'.$_GET['id'].'"';
                $result = mysqli_query($con,$sql);
                $i=0;
                while($row = mysqli_fetch_assoc($result)){
                    $response[$i]['id_categoria'] = $row['id_categoria'];
                    $response[$i]['nombre_categoria'] = $row['nombre_categoria'];
                    $response[$i]['departamento'] = $row['nombre_departamento'];
                    $response[$i]['id_depa'] = $row['id_depa'];
                    if( $row['estatus_categoria'] == "1"){
                        $response[$i]['estatus_categoria'] = true;
                    }else{
                        $response[$i]['estatus_categoria'] = false;
                    }
                    $response[$i]['fecha_created'] = $row['fecha_created'];
                    $i++;
                }
                echo json_encode($response,JSON_PRETTY_PRINT);
            } else{
                  // es para obtener todos los registros 
                $sql = 'SELECT  c.id_categoria,c.nombre_categoria,c.estatus_categoria,c.id_depa,c.fecha_created,d.nombre_departamento FROM admin_categorias c INNER JOIN admin_departamentos d ON c.id_depa = d.id_departamento';
                $result = mysqli_query($con,$sql);
                $i=0;
                while($row = mysqli_fetch_assoc($result)){
                    $response[$i]['id_categoria'] = $row['id_categoria'];
                    $response[$i]['nombre_categoria'] = $row['nombre_categoria'];
                    $response[$i]['departamento'] = $row['nombre_departamento'];
                    $response[$i]['id_depa'] = $row['id_depa'];
                    if( $row['estatus_categoria'] == "1"){
                        $response[$i]['estatus_categoria'] = true;
                    }else{
                        $response[$i]['estatus_categoria'] = false;
                    }
                    $response[$i]['fecha_created'] = $row['fecha_created'];
                    $i++;
                }
                echo  json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);  
            }
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

        }
    //echo "Informacion".file_get_contents('php://input');

}else{
    echo "DB FOUND CONNECTED";
}
