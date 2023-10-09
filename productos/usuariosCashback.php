<?php
// conexion con base de datos 
include '../conexion/conn.php';
// declarar array para respuestas 
$response = array();
$producto = array();

// insertamos cabeceras para permisos 

header('Access-Control-Allow-Origin:*');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");
header("Content-Type: JSON");
header('Content-Type: application/json;charset=utf-8'); 



// validamos si hay conexion 
if($con){

    $methodApi = $_SERVER['REQUEST_METHOD'];

        if($methodApi == 'GET'){
                if(isset($_GET['id'])){
                    $id =  intval($_GET['id']);
                    $sql = 'SELECT nombre,apellido,correo,direccion,colonia,ciudad,estado,telefono FROM usuario WHERE id=?';
                    $stmt = $con->prepare($sql);
                    $stmt->bind_param('i',$id);
                    $stmt->execute();
                    $result = $stmt->get_result(); 
                    $response = $result->fetch_assoc(); 
                    echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                }else{
                    $sql = 'SELECT u.id,u.nombre,u.apellido,u.orden as orden_user,u.vendedora,c.email,c.type,c.cashback,c.orden,c.compra_user FROM usuario u INNER JOIN usuarios_cashback c ON u.id = c.id_usuario ';
                    $stmt = $con->prepare($sql);
                    $stmt->execute();
                    $result   = $stmt->get_result();
                    $response = $result->fetch_all(MYSQLI_ASSOC);
                    echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                }
        }

        if($methodApi == 'POST'){

            $con->autocommit(false);

            try{
                $_POST = json_decode(file_get_contents('php://input'),true);
                $id       = $_POST['id'];
                $email    = $_POST['correo'];
                $cashback = $_POST['cashback'];
                $orden    = $_POST['orden'];
    
                $sql = 'SELECT pass FROM usuario WHERE  id=?';
                $stmt = $con->prepare($sql);
                $stmt->bind_param('i',$id);
                $stmt->execute();
                $result = $stmt->get_result();
                $data = $result->fetch_assoc(); 

                $sqlInsert = 'INSERT INTO usuarios_cashback (id_usuario,email,password,type,cashback,orden) VALUES ('.$id.',"'.$email.'","'.$data['pass'].'",2,'.$cashback.',"'.$orden.'")';

                $result = mysqli_query($con,$sqlInsert);

                if($result == 1){
                    $con->commit();
                    header("HTTP/1.1 200 OK");
                    $response['status'] = 200;
                    $response['mensaje'] = 'Se registro correctamente';
                    echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                }else{
                    $con->rollback();
                    header("HTTP/1.1 400");
                    $response['status'] = 400;
                    $response['mensaje'] = 'No se pudo crear el registro, intentalo de nuevo';
                    echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                }
            }catch(Exception $e){
                $con->rollback();
                header("HTTP/1.1 400");
                $response['status'] = 400;
                $response['mensaje'] = $e->getMessage();
                echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
            }
            
        }

        if($methodApi == 'DELETE'){
            $sql = 'DELETE FROM productos_cashback WHERE id_producto='.$_GET['id'].'';
            $result = mysqli_query($con,$sql);

            if($result || $result == 1){
                header("HTTP/1.1 200 OK");
                $response['status'] = 200;
                $response['mensaje'] = 'Se elimino correctamente';
                echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
            }
        }
  
}else{
    echo "DB FOUND CONNECTED";
}
?>