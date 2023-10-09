<?php
// conexion con base de datos 
include '../conexion/conn.php';
include '../class/querys.php';
//import middleware
include '../middleware/validarToken.php';



// declarar array para respuestas 
$response = array();
$fecha = date('Y-m-d H:i:s');

// insertamos cabeceras para permisos 

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type,Authorization, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");
header("Content-Type: JSON");
header('Content-Type: application/json;charset=utf-8'); 


$query = new Querys();

// validamos si hay conexion 
if($con){
    if($token_access['token']){
        $methodApi = $_SERVER['REQUEST_METHOD'];

        switch($methodApi){
            // metodo post 
            case 'POST':
                $_POST = json_decode(file_get_contents('php://input'),true);

                $nombre      = $_POST['nombre_proveedor'];
                $estatus     = $_POST['estatus'];

                if($nombre == '' || $estatus == ''){
                    header("HTTP/1.1 400");
                    $response['status'] = 400;
                    $response['mensaje'] = 'Debes agregar todos los campos, existen campos vacios';
                    echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT); 
                }else{
                    //preparamos sentencia
                    $sql = "INSERT INTO admin_proveedores (nombre_proveedor,estatus,fecha_created) VALUES (?,?,?)";
                    $result = $query->insertProveedor($con,$sql,$_POST,$fecha);
                    if($result){
                        header("HTTP/1.1 200");
                        $response['status'] = 200;
                        $response['mensaje'] = 'El registro fue insertado correctamente';
                        echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT); 
                    }
                }

            break;
            // metodo get 
            case 'GET':
             // para obtener un registro especifico
                if(isset($_GET['id']))
                {
                    $id = $_GET['id'];
                    $sql = 'SELECT  *FROM admin_proveedores WHERE id_proveedor=?';
                    $result = $query->getQueryId($con,$sql,$id);
                    $response['id_proveedor']     = $result['id_proveedor'];
                    $response['nombre_proveedor'] = $result['nombre_proveedor'];
                    $response['estatus']          = $result['estatus'] === 1 ? true : false;
                    echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                    $con->close(); 

                }else if(isset($_GET['activos']))
                {
                    $result = $query->getQuery($con,'SELECT *FROM admin_proveedores WHERE estatus=1');
                    $i=0;

                    foreach($result as $row){
                        $response[$i]['id_proveedor'] = $row['id_proveedor'];
                        $response[$i]['nombre_proveedor'] = $row['nombre_proveedor'];
                        $response[$i]['estatus'] = $row['estatus'] === 1 ? true : false;
                        $response[$i]['porcentaje_flete'] = 0;
                        $response[$i]['precio_yuan'] =  0;
                        $response[$i]['precio_costo'] =  0;
                        $response[$i]['selected'] = false;
                        $i++;
                    }
                    echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                    $con->close(); 

                }else
                {
                    $result = $query->getQuery($con,'SELECT *FROM admin_proveedores');
                    $i=0;

                    foreach($result as $row){
                        $response[$i]['id_proveedor'] = $row['id_proveedor'];
                        $response[$i]['nombre_proveedor'] = $row['nombre_proveedor'];
                        $response[$i]['estatus'] = $row['estatus'] === 1 ? true : false;
                        $response[$i]['selected'] = false;
                        $i++;
                    }

                    echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                    //$con->close(); 
                }
            break;
            case 'PUT':
                $_PUT = json_decode(file_get_contents('php://input'),true);

                $id = $_GET['id'];
                $sql = "UPDATE admin_proveedores SET nombre_proveedor=?,estatus=? WHERE id_proveedor=?";
                $result = $query->updateProveedor($con,$sql,$_PUT,$id);
                if($result){
                    header("HTTP/1.1 200");
                    $response['status'] = 200;
                    $response['mensaje'] = 'El registro fue actualizado correctamente';
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
?>