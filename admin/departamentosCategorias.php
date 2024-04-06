<?php
// conexion con base de datos 
include '../conexion/conn.php';
//incluir middleware


// declarar array para respuestas 
$response      = array();

date_default_timezone_set('America/Mexico_City');
$fecha = date('Y-m-d H:i:s');

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


            // metodo get 
            if($methodApi == 'GET'){
                $sql = 'SELECT  *FROM admin_departamentos';
                $result = mysqli_query($con,$sql);
                $i=0;
                while($row = mysqli_fetch_assoc($result)){
                    $departamentos[$i]['id_departamento'] = $row['id_departamento'];
                    $departamentos[$i]['nombre_departamento'] = $row['nombre_departamento'];

                    if( $row['estatus_departamento'] == "1"){
                        $departamentos[$i]['estatus_departamento'] = true;
                    }else{
                        $departamentos[$i]['estatus_departamento'] = false;
                    }
                    $departamentos[$i]['fecha_created'] = $row['fecha_created'];
                    $id =  intval($row['id_departamento']);

                    $sqlCategorias = 'SELECT *FROM admin_categorias WHERE id_depa='.$id.'';
                    $resultado =  mysqli_query($con,$sqlCategorias);
                    $j=0;
                    while($fill = mysqli_fetch_assoc($resultado)){
                        $categorias[$j]['id_categoria'] = $fill['id_categoria'];
                        $categorias[$j]['nombre_categoria'] = $fill['nombre_categoria'];
                        $j++;
                    }

                    $departamentos[$i]['categorias'] = $categorias;
                    $categorias = [];
                    $i++;
                }

                $response = $departamentos;
                echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
            }

                
    //echo "Informacion".file_get_contents('php://input');

}else{
    echo "DB FOUND CONNECTED";
}
