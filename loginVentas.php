<?php
date_default_timezone_set('America/Mexico_City');
// conexion con base de datos 
include 'conexion/conn.php';

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
    
    //echo "Informacion".file_get_contents('php://input');
$methodApi = $_SERVER['REQUEST_METHOD'];
$hora = date('H')+8;
$fecha_actual = date('Y-m-d H:i:s');
$fecha_expire = date('Y-m-d '.$hora.':i:s');

    function generarToken(){

        $cadena = "ABCDEFJHIJKMNOPQRSTUVWXYZ0123456789abcdefjhijkmnopqrstuvwxyz";
        $token = "";
        for($i=0;$i<40;$i++){
            /** revisar rand */
            $numero = random_int(0,50);
            $token .= $cadena[$numero];
        }
        return $token;
    }

   switch($methodApi){
       // metodo post 
       //enviar data
       case 'POST':

        $_POST = json_decode(file_get_contents('php://input'),true);

        $pass = md5($_POST['password']);

        $sql = 'SELECT id_user_ventas,email_user,pass_user,expire_token,tipo_usuario,estado_user FROM usuarios_ventas WHERE email_user="'.$_POST['username'].'" AND pass_user="'.$pass.'"';
        $result = mysqli_query($con,$sql);
        $row = mysqli_fetch_assoc($result);

        if($row){
            if($row['estado_user'] == "0"){
                header("HTTP/1.1 402 Unauthorized");
                $response['mensaje'] = 'Este usuario esta inactivo';
                echo  json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
            }else{
                if($row['email_user'] == $_POST['username'] and $row['pass_user'] == $pass){
                    $token = generarToken();
    
                    $sqlUpdate = 'UPDATE usuarios_ventas SET token="'.$token.'",expire_token="'.$fecha_expire.'" WHERE id_user_ventas='.$row['id_user_ventas'].'';
                    $result = mysqli_query($con,$sqlUpdate);
    
                    if($result){
                        $token_caja_chica =  date('ndyzw');
                        $token_ventas = date('wzydn');
                        $sqlToken = 'UPDATE tokens_acceso SET token_caja_chica="'.$token_caja_chica.'",token_venta="'.$token_ventas.'",fecha_actualizacion="'.$fecha_actual.'" WHERE id=1';
                        $resultado = mysqli_query($con,$sqlToken);
    
                        $insertAcceso = 'INSERT INTO accesos (id_usuario,fecha_acceso,app_acceso) VALUES ('.$row['id_user_ventas'].',"'.$fecha_actual.'","ventas")';
                        $resultAcceso = mysqli_query($con,$insertAcceso);
                        
                        header("HTTP/1.1 200 OK");
                        $response['token'] = $token;
                        $response['mensaje'] = 'Usuario logueado correctamente';
                        $response['user'] = $row['email_user'];
                        $response['id'] = $row['id_user_ventas'];
                        $response['expire_token'] = $row['expire_token'];
                        $response['tipo_usuario'] = $row['tipo_usuario'];
                        echo  json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                    }else{
                        header("HTTP/1.1 400");
                        $response['mensaje'] = 'Ocurrio un error intente de nuevo';
                        echo  json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                    }
                }else{
                    header("HTTP/1.1 400");
                    $response['mensaje'] = 'Ocurrio un error inesperado';
                    echo  json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                }
            }
        }else{
            header("HTTP/1.1 401 Unauthorized");
            $response['mensaje'] = 'Usuario o Password incorrectos';
            echo  json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
        }
       break;
       // metodo get 
       case 'GET':
        // para obtener un registro especifico
        if(isset($_GET['id'])){
            $sql = 'SELECT *FROM estados  where id_status="'.$_GET['id'].'"';
            $result = mysqli_query($con,$sql);
            $i=0;
            while($row = mysqli_fetch_assoc($result)){
                $response['id'] = $row['id_status'];
                $response['nombre_status'] = $row['nombre_status'];
                $response['fecha_created'] = $row['fecha_created'];
                $i++;
            }
            echo json_encode($response,JSON_PRETTY_PRINT);
         } else{

             // es para obtener todos los registros 
        //     $sql = 'select *from estados';
        //     $result = mysqli_query($con,$sql);
        //     $i=0;
        //     while($row = mysqli_fetch_assoc($result)){
        //         $response[$i]['id'] = $row['id_status'];
        //         $response[$i]['nombre_status'] = $row['nombre_status'];
        //         $response[$i]['fecha_created'] = $row['fecha_created'];
        //         $i++;
        //     }
        //    echo  json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
         }
       break;
       case 'PUT':
        $_PUT = json_decode(file_get_contents('php://input'),true);
        $sql = 'UPDATE estados SET nombre_status="'.$_PUT['nombre_status'].'"  WHERE id_status='.$_GET['id'].'';
        $result = mysqli_query($con,$sql);
        if($result)
                echo 'registro actualizado correctamente';
            else
                echo 'no se pudo actualizar';
       break;
       case 'DELETE':
            $sql = 'DELETE  from estados where id_status='.$_GET['id'].'';
            $result = mysqli_query($con,$sql);
            if($result)
                echo "registro eliminado satisfactoriamente";
            else
                echo "no se pudo eliminar el registro";
       break;
   }

}else{
    echo "DB FOUND CONNECTED";
}
?>