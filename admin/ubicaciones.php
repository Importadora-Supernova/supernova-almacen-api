<?php
// conexion con base de datos 
include '../conexion/conn.php';
require_once('../class/ubicaciones.class.php');
date_default_timezone_set('America/Mexico_City');
$fecha = date('Y-m-d H:i:s');

// declarar array para respuestas 
$response = array();

//instanciamos la clase de ubicaciones
$ubicacion = new Ubicaciones();

// insertamos cabeceras para permisos 

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept,Authorization, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");
header("Content-Type: JSON");
header('Content-Type: application/json;charset=utf-8');

if($con){
    
    $methodApi = $_SERVER['REQUEST_METHOD'];


    if($methodApi == 'GET'){
        if(isset($_GET['cp'])){

            $codigo = $_GET['cp'];
            $sql = 'SELECT e.nombre as estado,m.nombre as municipio,c.nombre as colonia FROM municipios m INNER JOIN colonias c ON m.id = c.municipio INNER JOIN estados e ON m.estado = e.id WHERE c.codigo_postal=? LIMIT 1;';
            $response = $ubicacion->getMunicipioEstado($con,$sql,$codigo);
            echo  json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

        }else if(isset($_GET['colonia'])){

            $codigo = $_GET['colonia'];
            $sql = 'SELECT *FROM colonias WHERE codigo_postal=?';
            $response = $ubicacion->getColoniasCodigoPostal($con,$sql,$codigo);
            echo  json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
            
        }
            
 
    }
}else{
    echo "DB FOUND CONNECTED";
}


?>