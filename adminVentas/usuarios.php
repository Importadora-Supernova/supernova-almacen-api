<?php
// conexion con base de datos 
include '../conexion/conn.php';
//incluir middleware
include '../middleware/midleware.php';
// declarar array para respuestas 
$response = array();

// insertamos cabeceras para permisos 

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type,Authorization, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");
header("Content-Type: JSON");
header('Content-Type: application/json;charset=utf-8'); 

date_default_timezone_set('America/Mexico_City');
$fecha = date('Y-m-d H:i:s');


// validamos si hay conexion 
if($con){
    if($validate === 'validado'){
        $methodApi = $_SERVER['REQUEST_METHOD'];

        switch($methodApi){
            // metodo post 
            case 'POST':
            $_POST = json_decode(file_get_contents('php://input'),true);
            $pass = md5($_POST['pass_user']);
            $sqlInsert = 'INSERT INTO usuarios_ventas (email_user,pass_user,tipo_usuario,estado_user,fecha_created) VALUES ("'.$_POST['email_user'].'","'.$pass.'",'.$_POST['tipo_usuario'].','.$_POST['estado_user'].',"'.$fecha.'")';
            $result = mysqli_query($con,$sqlInsert);
            if($result){
                header("HTTP/1.1 200 OK");
                $response['status'] = 200;
                $response['mensaje'] = 'Usuario creado correctamente';
                echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
            }else{
                header("HTTP/1.1 400");
                $response['status'] = 400;
                $response['mensaje'] = 'No se pudo Guardar el usuario';
                echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
            }
             //
            break;
            // metodo get 
            case 'GET':
             // para obtener un registro especifico
            if(isset($_GET['id'])){ 
                $sql = 'SELECT u.id_user_ventas,u.email_user,u.estado_user,u.tipo_usuario,t.nombre_tipo_usuario,u.fecha_created  FROM usuarios_ventas u LEFT JOIN app_tipo_usuario_ventas T ON u.tipo_usuario = t.id_tipo_usuario WHERE u.id_user_ventas='.$_GET['id'].'';
                $result = mysqli_query($con,$sql);
                while($row = mysqli_fetch_assoc($result)){
                    $response['id'] = $row['id_user_ventas'];
                    $response['email_user'] = $row['email_user'];
                    $response['estado_user'] = $row['estado_user'] == "1" ? true : false;
                    $response['tipo_usuario'] = $row['tipo_usuario'];
                    $response['nombre_tipo_usuario'] = $row['nombre_tipo_usuario'];
                    $response['fecha_created'] = $row['fecha_created'];
                }

                echo  json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                } else if(isset($_GET['activos'])){
                    $sql = 'SELECT u.id_user_ventas,u.email_user,u.estado_user,u.tipo_usuario,t.nombre_tipo_usuario,u.fecha_created FROM usuarios_ventas u LEFT JOIN app_tipo_usuario_ventas t ON u.tipo_usuario = t.id_tipo_usuario WHERE u.estado_user =1';
                    $result = mysqli_query($con,$sql);
                    $i=0;
                    while($row = mysqli_fetch_assoc($result)){
                        $response[$i]['id'] = $row['id_user_ventas'];
                        $response[$i]['email_user'] = $row['email_user'];
                        $response[$i]['estado_user'] = $row['estado_user'] == "1" ? true : false;
                        $response[$i]['tipo_usuario'] = $row['tipo_usuario'];
                        $response[$i]['nombre_tipo_usuario'] = $row['nombre_tipo_usuario'];
                        $response[$i]['fecha_created'] = $row['fecha_created'];
                        $i++;
                    }
                    echo  json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);

                }else{
                    $sql = 'SELECT u.id_user_ventas,u.email_user,u.estado_user,u.tipo_usuario,u.fecha_created,t.nombre_tipo_usuario  FROM usuarios_ventas u LEFT JOIN app_tipo_usuario_ventas t ON u.tipo_usuario = t.id_tipo_usuario';
                    $result = mysqli_query($con,$sql);
                    $i=0;
                    while($row = mysqli_fetch_assoc($result)){
                        $response[$i]['id'] = $row['id_user_ventas'];
                        $response[$i]['email_user'] = $row['email_user'];
                        $response[$i]['estado_user'] = $row['estado_user'] == "1" ? true : false;
                        $response[$i]['tipo_usuario'] = $row['tipo_usuario'];
                        $response[$i]['nombre_tipo_usuario'] = $row['nombre_tipo_usuario'];
                        $response[$i]['fecha_created'] = $row['fecha_created'];
                        $i++;
                    }
                    echo  json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                    }
            break;
            case 'PUT':
                $_PUT = json_decode(file_get_contents('php://input'),true);
                $update = 'UPDATE usuarios_ventas SET email_user="'.$_PUT['email_user'].'", estado_user='.$_PUT['estado_user'].', tipo_usuario='.$_PUT['tipo_usuario'].' WHERE id_user_ventas='.$_GET['id'].'';
                $resUpdate = mysqli_query($con,$update);

                if($resUpdate){
                    header("HTTP/1.1 200 OK");
                    $response['status'] = 200;
                    $response['mensaje'] = 'Registro actulizado correctamente';
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
        header("HTTP/1.1 201");
        $response['status'] = 401;
        $response['mensaje'] = 'Token '.$validate;
        echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
    }
    //echo "Informacion".file_get_contents('php://input');

}else{
    echo "DB FOUND CONNECTED";
}
?>  