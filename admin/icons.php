<?php
// conexion con base de datos 
include '../conexion/conn.php';
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



// validamos si hay conexion 
if($con){
    
        
        $methodApi = $_SERVER['REQUEST_METHOD'];

        switch($methodApi){
            // metodo post 
            case 'POST':
                $_POST = json_decode(file_get_contents('php://input'),true);
                //echo "guardar informacion data: =>".json_encode($_POST);

                $con->autocommit(false);
                //declaramos array vacio
                $data = [];
                //le asignamos data de permisos (array de datos)
                $data = $_POST['iconos'];

                //iniciamos contador en cero
                $i=0;
                //declaramos bandera para filtrar algun error en la transaccion
                $band = true;
                // iniciamos ciclo while segun la cantidad de permisos a insertar
                while($i<count($data)){

                    $sql = 'INSERT INTO icons (nombre_icon) VALUES ("'.$data[$i]['icon'].'")';
                    $result = mysqli_query($con,$sql);
                    if($result){
                        $i++;
                    }else{
                        //si el update no fue existoso
                        // ejecutamos ROLLBACK de la transaccion
                        // bandera es igual a false 
                        header("HTTP/1.1 400");
                        $response['status'] = 400;
                        $response['mensaje'] = 'No se pudo guardar los registros';
                        echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                        $band = false;
                        $con->rollback();
                        break;
                    }
                }

                if($band){
                    header("HTTP/1.1 200 OK");
                    $response['status'] = 200;
                    $response['mensaje'] = 'Registros creados correctamente';
                    echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                    $con->commit();

                }else{
                    //caso contrario reacemos cualquier modificacion hecha por la transaccion
                    header("HTTP/1.1 400");
                    $response['status'] = 400;
                    $response['mensaje'] = 'Ocurrio un error';
                    echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                    $con->rollback();
                }  
                
            break;
            // metodo get 
            case 'GET':
                // para obtener un registro especifico
                    // es para obtener todos los registros 
                    $sql = 'SELECT *FROM icons';
                    $result = mysqli_query($con,$sql);
                    $i=0;
                    while($row = mysqli_fetch_assoc($result)){
                        $response[$i]['id'] = $row['id'];
                        $response[$i]['icon'] = $row['nombre_icon'];
                        $i++;
                    }
                echo  json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
        
            break;
          
        }
    //echo "Informacion".file_get_contents('php://input');

}else{
    echo "DB FOUND CONNECTED";
}
?>