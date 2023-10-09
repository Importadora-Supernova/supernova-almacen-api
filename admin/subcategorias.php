<?php
// conexion con base de datos 
include '../conexion/conn.php';
//incluir middleware
include '../middleware/validarToken.php';

// declarar array para respuestas 
$response = array();

date_default_timezone_set('America/Mexico_City');
$fecha = date('Y-m-d H:i:s');

// insertamos cabeceras para permisos 

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type,Authorization, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");
header("Content-Type: JSON");
header('Content-Type: application/json;charset=utf-8'); 

// validamos si hay conexion 
if($con){
    if($token_access['token']){
        $methodApi = $_SERVER['REQUEST_METHOD'];

        switch($methodApi){
            // metodo post 
            case 'POST':
            $_POST = json_decode(file_get_contents('php://input'),true);
             //echo "guardar informacion data: =>".json_encode($_POST);
            $id_cate = $_POST['id_categoria'];
            $nombre  = $_POST['nombre_subcategoria'];
            $estatus = $_POST['estatus_subcategoria'];

            if(is_null($id_cate) || $nombre == '' || $estatus == ''){
                header("HTTP/1.1 400");
                $response['status'] = 400;
                $response['mensaje'] = 'Debes agregar todos los campos, existen campos vacios';
                echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT); 
            }else{
                //preparando sentencia
                if(!($sentencia = $con->prepare("INSERT INTO  admin_subcategorias (id_categoria,nombre_subcategoria,estatus_subcategoria,fecha_created) VALUES (?,?,?,?)"))){
                    echo "Falló la preparación: (" . $con->errno . ") " . $con->error;
                }

                if(!$sentencia->bind_param("isss", $id_cate,$nombre,$estatus,$fecha)){
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
                    // es para obtener todos los registros 
                    $sql = 'SELECT 
                    d.nombre_departamento as departamento,
                    c.nombre_categoria as categoria,
                    s.nombre_subcategoria as subcategoria,
                    d.id_departamento,
                    c.id_categoria,
                    s.id_subcategoria,
                    s.estatus_subcategoria,
                    s.fecha_created
                    FROM admin_categorias c INNER JOIN admin_departamentos d
                    ON c.id_depa = d.id_departamento INNER JOIN admin_subcategorias s ON c.id_categoria = s.id_categoria WHERE s.id_categoria='.$_GET['id'].'';
                    $result = mysqli_query($con,$sql);
                    $i=0;
                    while($row = mysqli_fetch_assoc($result)){
                        $response[$i]['id_departamento'] = $row['id_departamento'];
                        $response[$i]['id_categoria'] = $row['id_categoria'];
                        $response[$i]['id_subcategoria'] = $row['id_subcategoria'];
                        $response[$i]['departamento'] = $row['departamento']; 
                        $response[$i]['categoria'] = $row['categoria']; 
                        $response[$i]['subcategoria'] = $row['subcategoria']; 
                        if( $row['estatus_subcategoria'] == "1"){
                            $response[$i]['estatus_subcategoria'] = true;
                        }else{
                            $response[$i]['estatus_subcategoria'] = false;
                        }
                        $response[$i]['fecha_created'] = $row['fecha_created'];
                        $i++;
                    }
                    echo  json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);  
            } else{
                  // es para obtener todos los registros 
                $sql = 'SELECT 
                d.nombre_departamento as departamento,
                c.nombre_categoria as categoria,
                s.nombre_subcategoria as subcategoria,
                d.id_departamento,
                c.id_categoria,
                s.id_subcategoria,
                s.estatus_subcategoria,
                s.fecha_created
                FROM admin_categorias c INNER JOIN admin_departamentos d
                ON c.id_depa = d.id_departamento INNER JOIN admin_subcategorias s ON c.id_categoria = s.id_categoria';
                $result = mysqli_query($con,$sql);
                $i=0;
                while($row = mysqli_fetch_assoc($result)){
                    $response[$i]['id_departamento'] = $row['id_departamento'];
                    $response[$i]['id_categoria'] = $row['id_categoria'];
                    $response[$i]['id_subcategoria'] = $row['id_subcategoria'];
                    $response[$i]['departamento'] = $row['departamento']; 
                    $response[$i]['categoria'] = $row['categoria']; 
                    $response[$i]['subcategoria'] = $row['subcategoria']; 
                    if( $row['estatus_subcategoria'] == "1"){
                        $response[$i]['estatus_subcategoria'] = true;
                    }else{
                        $response[$i]['estatus_subcategoria'] = false;
                    }
                    $response[$i]['fecha_created'] = $row['fecha_created'];
                    $i++;
                }
                echo  json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);  
            }
            break;
            case 'PUT':
            $_PUT = json_decode(file_get_contents('php://input'),true);
            $sql = 'UPDATE admin_subcategorias SET id_categoria='.$_PUT['id_categoria'].',nombre_subcategoria="'.$_PUT['nombre_subcategoria'].'",estatus_subcategoria="'.$_PUT['estatus_subcategoria'].'"  WHERE id_subcategoria='.$_GET['id'].'';
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
    }else{
        echo $token_access['validate'];
    }
    //echo "Informacion".file_get_contents('php://input');

}else{
    echo "DB FOUND CONNECTED";
}
