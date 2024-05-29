<?php

// conexion con base de datos 

// declarar array para respuestas 


date_default_timezone_set('America/Mexico_City');
$fecha = date('Y-m-d H:i:s');

// insertamos cabeceras para permisos 

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept,Authorization, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");
header("Content-Type: JSON");
header('Content-Type: application/json;charset=utf-8'); 

$uploads_dir = 'filestest/';
$nombre = $_REQUEST['nombre'];
foreach ($_FILES['files']['tmp_name'] as $key => $tmp_name) {
    $file_name = $_FILES['files']['name'][$key];
    $file_tmp = $_FILES['files']['tmp_name'][$key];
    move_uploaded_file($file_tmp, "$uploads_dir/$file_name");
}

echo json_encode("Archivos subidos exitosamente".$nombre."");

?>