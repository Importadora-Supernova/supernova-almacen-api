<?php
// conexion con base de datos 
include '../conexion/conn.php';
require_once '../class/querys.php';

date_default_timezone_set('America/Mexico_City');
//incluir middleware
$fecha_actual = date('Y-m-d H:i:s');


// declarar array para respuestas 
$response = array();

// insertamos cabeceras para permisos 

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");
header("Content-Type: JSON");
header('Content-Type: application/json;charset=utf-8'); 

$query = new Querys();

// validamos si hay conexion 
if($con){
        $methodApi = $_SERVER['REQUEST_METHOD'];

        if($methodApi == 'GET'){
            $id = $_GET['id'];
            $sql = 'SELECT *FROM view_caracteristicas_subcategorias WHERE id_subcategoria=?';
            $result = $query->getQueryIdArray($con,$sql,$id);
            $i=0;
            $sentencia ='SELECT *FROM view_atributos_subcategoria WHERE id_subcategoria = ? and id_caracteristica = ?';

            // echo json_encode($result,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
            // $con->close();
        }
        //si el metodo de consulta es POST
        if($methodApi == 'POST'){
            //Guardamos el array de los datos input//post en la variable $_POST
            $_POST = json_decode(file_get_contents('php://input'),true);
            //Inicializamos la transaccion
            $con->autocommit(false);

            try{
                //definimos el array de atributos enviado en la peticion
                //id de subcategoria que envia la peticio y un flag band
                //para validar la transaccion
                $atributos = $_POST['atributos'];
                $id = $_POST['id_subcategoria'];
                $band = false;

                //ciclo para ejecutar segun la cantidad de atributos enviados
                foreach($atributos as $data){
                    //consultamos primeramente si el atributo ya existe para esa subcategoria
                    $consulta = 'SELECT id_admin_atributos_subcategorias as id,COUNT(*) AS count FROM admin_atributos_subcategorias WHERE id_subcategoria='.$id.' AND id_atributo='.$data['id'].'';
                    $result = mysqli_query($con,$consulta);
                    $row = mysqli_fetch_assoc($result);
                    $count = $row['count'];
                    //almacenamos el id del registro si existe
                    $id_delete = $row['id'];

                    //validamos si se esta desactivando el atributo con la propiedad selected
                    $selected = $data['selected'];
                    //declaramos la sentencia delete para borrar el registro
                    $sql_delete = 'DELETE FROM admin_atributos_subcategorias WHERE id_admin_atributos_subcategorias=?';
                    //validamos si el registro existe
                    if($count > 0){
                        //si existe validamos si se esta desactivando
                        if(!$selected){
                            //procedemos a eliminar si el selected es false si se ha deseleccionado un registro ya existente
                            $result_delete = $query->deleteRegister($con,$sql_delete,$id_delete);
                            if($result_delete == 1){
                                $band = true;
                            }
                        }else{
                            //sino solo ejecutamos un cambio en el flag
                            $band = true;
                        }
                    }else{
                        //si el registro no existe procedemos a ejecutar la insercion del registro
                        $sql = "INSERT INTO admin_atributos_subcategorias (id_subcategoria,id_atributo,id_caracteristica,fecha_created) VALUES(?,?,?,?)";
                        $ok = $query->insertData($con,$sql,$id,$data,$fecha_actual);
                        //si el registro fue existoso cambiamos el flag
                        if($ok == 1){
                            $band = true;
                        }else{
                            $band = false;
                            break;
                        }
                    }

                } 
                //fin del ciclo
                //validamos que el flag este en true y todo se haya ejecutado corretamente
                //para ejecutar el commit y enviar respuesta exitosa
                if($band){
                    $con->commit();
                    header("HTTP/1.1 200 OK");
                    $response['status'] = 200;
                    $response['mensaje'] = 'Registros creados correctamente';
                    echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                    $con->close();
                }else{
                    //si ocurrio un error en una de las transacciones ejecutamos un rollback
                    $con->rollback();
                    header("HTTP/1.1 400");
                    $response['status'] = 400;
                    $response['mensaje'] = 'No se pudo Guardar los registros';
                    echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);    
                    $con->close(); 
                }
            }catch( Exception $e){
                //si ocurrio un error en el proceso ejecutamos roolback
                $con->rollback();
                header("HTTP/1.1 400");
                $response['status'] = 400;
                $response['mensaje'] = 'No se pudo Guardar los registros';
                echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);    
                $con->close(); 
            }
            
        
        }

        if($methodApi == 'PUT'){
            $_PUT = json_decode(file_get_contents('php://input'),true);
        

        }
    //echo "Informacion".file_get_contents('php://input');

}else{
    echo "DB FOUND CONNECTED";
}
?>