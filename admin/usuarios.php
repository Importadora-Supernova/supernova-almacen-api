<?php
// conexion con base de datos 
include '../conexion/conn.php';
date_default_timezone_set('America/Mexico_City');
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

$fecha_actual = date('Y-m-d H:i:s');

// validamos si hay conexion 
if($con){
    
        $methodApi = $_SERVER['REQUEST_METHOD'];

        switch($methodApi){
            // metodo post 
            case 'POST':
             // post
             $_POST = json_decode(file_get_contents('php://input'),true);
             $pass = md5($_POST['pass_user']);

             $con->autocommit(false);

             $sqlInsert = 'INSERT INTO usuarios_almacen (email_user,pass_user,type_user,token,expire_token,estado_user,fecha_created) VALUES
             ("'.$_POST['email_user'].'","'.$pass.'","'.$_POST['type_user'].'","","",1,"'.$fecha_actual.'")';

             $result = mysqli_query($con,$sqlInsert);

             if($result){

                $sqlSelectUsuario = 'SELECT MAX(id_user_almacen) as id_user  FROM usuarios_almacen';
                $resUsuario = mysqli_query($con,$sqlSelectUsuario);

                $fill = mysqli_fetch_assoc($resUsuario);

                if($fill){
                   
                    echo $variable;
                    $insertPermisos = 'INSERT INTO permisos_user (id_user,almacen_general,productos_almacenes,almacenes,traslados,almacen_garantia) VALUES
                    ('.$fill['id_user'].',"0","0","0","0","0")';

                    $resultPermisos = mysqli_query($con,$insertPermisos);

                    if($resultPermisos){
                        $con->commit();
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
                    
                }else{
                    $con->rollback();
                    header("HTTP/1.1 400");
                    $response['status'] = 400;
                    $response['mensaje'] = 'No se pudo Guardar el registro';
                    echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                }
            }else{
                $con->rollback();
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
                 $sql = 'SELECT  *FROM permisos_user WHERE id_user='.$_GET['id'].'';
                 $result = mysqli_query($con,$sql);
                 $i=0;
                 while($row = mysqli_fetch_assoc($result)){
                   
                 }
                 echo json_encode($response,JSON_PRETTY_PRINT);
              } else{
                    $sql = 'SELECT  *FROM usuarios_almacen ';
                    $result = mysqli_query($con,$sql);
                    $i=0;
                    while($row = mysqli_fetch_assoc($result)){
                        $response[$i]['id'] =  $row['id_user_almacen'];
                        $response[$i]['email_user'] =  $row['email_user'];
                        $response[$i]['type_user'] =  $row['type_user'];
                        $response[$i]['estado_user'] =  $row['estado_user'];
                        $i++;
                    }
                    echo json_encode($response,JSON_PRETTY_PRINT);
                }
            break;
            case 'PUT':
             $_PUT = json_decode(file_get_contents('php://input'),true);
             $sql = 'UPDATE almacenes SET nombre_almacen="'.$_PUT['nombre_almacen'].'", tipo="'.$PUT['tipo'].'", status="'.$PUT['status'].'"  WHERE id='.$_GET['id'].'';
             $result = mysqli_query($con,$sql);
             if($result)
                     echo 'registro actualizado correctamente';
                 else
                     echo 'no se pudo actualizar';
            break;
        }
    //echo "Informacion".file_get_contents('php://input');

}else{
    echo "DB FOUND CONNECTED";
}
?>