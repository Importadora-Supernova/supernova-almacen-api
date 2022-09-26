<?php
// conexion con base de datos 
include 'conexion/conn.php';


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
               
            break;
            // metodo get 
            case 'GET':
                // para obtener un registro especifico
                if(isset($_GET['id'])){
                    $sql = 'SELECT *FROM  historial_registro_productos WHERE id_almacen="'.$_GET['id'].'" ORDER BY fecha_created DESC';
                    $result = mysqli_query($con,$sql);
                    $i=0;
                    while($row = mysqli_fetch_assoc($result)){
                        $response[$i]['id_producto'] = $row['id_producto'];
                        $response[$i]['codigo'] = $row['codigo'];
                        $response[$i]['nombre'] = $row['nombre'];
                        $response[$i]['nombre_almacen'] = $row['nombre_almacen'];
                        $response[$i]['cantidad'] = $row['cantidad'];
                        $response[$i]['fecha_created'] = $row['fecha_created'];
                        $i++;
                    }
                    echo json_encode($response,JSON_PRETTY_PRINT);
                } else{
                    // es para obtener todos los registros 
                    $sql = 'select *from historial_registro_productos';
                    $result = mysqli_query($con,$sql);
                    $i=0;
                    while($row = mysqli_fetch_assoc($result)){
                        $response[$i]['id_producto'] = $row['id_producto'];
                        $response[$i]['codigo'] = $row['codigo'];
                        $response[$i]['nombre'] = $row['nombre'];
                        $response[$i]['nombre_almacen'] = $row['nombre_almacen'];
                        $response[$i]['cantidad'] = $row['cantidad'];
                        $response[$i]['fecha_created'] = $row['fecha_created'];
                        $i++;
                    }
                echo  json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                }
            break;
        }

    //echo "Informacion".file_get_contents('php://input');

}else{
    echo "DB FOUND CONNECTED";
}
?>