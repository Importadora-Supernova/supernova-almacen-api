<?php
// conexion con base de datos 
include '../conexion/conn.php';
//incluir middleware

date_default_timezone_set('America/Mexico_City');
$fecha = date('Y-m-d H:i:s');

// declarar array para respuestas 
$response = array();


// insertamos cabeceras para permisos 

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type,Authorization, Accept, Access-Control-Request-Method");
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
                //se convierten valores obtenidos por JSON A POST
                $_POST = json_decode(file_get_contents('php://input'),true);

                //inicio de transaccion 
                $con->autocommit(false);
                //declaramos array vacio
                $data = [];
                //le asignamos data de permisos (array de datos)
                $data = $_POST['permisos'];

                //iniciamos contador en cero
                $i=0;
                //declaramos bandera para filtrar algun error en la transaccion
                $band = true;
                // iniciamos ciclo while segun la cantidad de permisos a insertar
                while($i<count($data)){
                    /*incialmente consultamos si el registro del modulo segun el 
                    tipo de usuario existe*/

                    $select = 'SELECT *FROM app_modulos_tipo_usuario_ventas WHERE id_tipo='.$data[$i]['id_tipo'].' AND id_modulo='.$data[$i]['id_modulo'].'';
                    $res = mysqli_query($con,$select);
                    $fill = mysqli_fetch_assoc($res);
                    
                    // si existe realizamos un UPDATE en su regustro
                    if($fill){
                        $update = 'UPDATE app_modulos_tipo_usuario_ventas SET view='.$data[$i]['addView'].', edit='.$data[$i]['addEdit'].', fecha_updated="'.$fecha.'" WHERE id_tipo='.$data[$i]['id_tipo'].' AND id_modulo='.$data[$i]['id_modulo'].'';
                        $resUpdate = mysqli_query($con,$update);

                        //comprobamos que la ejecucion del update
                        //sea exitosa e incrementamos el contador
                        if($resUpdate){
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
                    }else{
                           // caso contrario si no existe  el registro realizamos un insert para el tipo de usuario
                            $sql = 'INSERT INTO app_modulos_tipo_usuario_ventas(id_tipo,id_modulo,view,edit,fecha_created,fecha_updated) VALUES ('.$data[$i]['id_tipo'].','.$data[$i]['id_modulo'].','.$data[$i]['addView'].','.$data[$i]['addEdit'].',"'.$fecha.'","")';
                            $res = mysqli_query($con,$sql);

                            //comprobamos que la insercion sea correcta
                            if($res){
                                //si es asi incrementamos el contador
                                $i++;
                            }else{
                                //si el insert no fue existoso
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
                }
                //comprobamos que el ciclo de la transaccion se ejecuto correcto
                // y ejecutamos el commit de la transaccion
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
                //consulta get de los modulos que tenga registrado un tipo de usuario
                if(isset($_GET['id'])){
                    //ejecutamos consulta
                    $sql = 'SELECT *FROM app_modulos_tipo_usuario_ventas WHERE id_tipo='.$_GET['id'].'';
                    $result = mysqli_query($con,$sql);
                    $i=0;
                    while($row = mysqli_fetch_assoc($result)){
                        $response[$i]['id_modulo'] = $row['id_modulo'];
                        $response[$i]['addView'] = $row['view'] == "1" ? true : false;
                        $response[$i]['addEdit'] = $row['edit'] == "1" ? true : false;
                        $i++;
                    }
                    echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);

                }else if(isset($_GET['type'])){
                    //ejecutamos consulta
                    $sql = 'SELECT * FROM modulos_tipo_usuario_ventas WHERE id_tipo_usuario='.$_GET['type'].'';
                    $result = mysqli_query($con,$sql);
                    $i=0;
                    while($row = mysqli_fetch_assoc($result)){
                        $response[$i]['nombre_modulo'] = $row['nombre_modulo'];
                        $response[$i]['ruta'] = $row['ruta'];
                        $response[$i]['icono'] = $row['icono'];
                        $response[$i]['estatus_modulo'] = $row['estatus_modulo'];
                        $response[$i]['view'] = $row['view'] == "1" ? true : false;
                        $response[$i]['edit'] = $row['edit'] == "1" ? true : false;
                        $i++;
                    }
                    echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);

                }else{
                    echo 'obteniendo get';
                }
             // para obtener todos los registro
            
                 //obtener por id
            break;
            case 'PUT':
              //put
            break;

        }
    //echo "Informacion".file_get_contents('php://input');

}else{
    echo "DB FOUND CONNECTED";
}
?>