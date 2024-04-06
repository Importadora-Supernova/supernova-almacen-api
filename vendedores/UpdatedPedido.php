<?php
// conexion con base de datos 
include '../conexion/conn.php';
//incluir middleware
//include '../middleware/validarToken.php';


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

        if($methodApi == 'POST'){
            $_POST = json_decode(file_get_contents('php://input'),true);

            $nombres       = $_POST['nombred'];
            $apellidos     = $_POST['apellidod'];
            $paqueteria    = $_POST['paqueteria'];
            $rfc           = $_POST['rfc'];
            $codigo_postal = $_POST['codigopd'];
            $colonia       = $_POST['coloniad'];
            $ciudad        = $_POST['ciudadd'];
            $estado        = $_POST['estadod'];
            $direccion     = $_POST['direcciond'];
            $telefono      = $_POST['telefonod'];
            $orden         = $_POST['orden'];
            $nombre_completo = $nombres.' '.$apellidos;

            $sqlUpdated = 'UPDATE registro_usuario SET paqueteria=?,rfc=?,nombred=?,apellidod=?,direcciond=?,coloniad=?,ciudadd=?,estadod=?,codigopd=?,telefonod=? WHERE orden=?';
            $stmt = $con->prepare($sqlUpdated);
            $stmt->bind_param('sssssssssss',$paqueteria,$rfc,$nombres,$apellidos,$direccion,$colonia,$ciudad,$estado,$codigo_postal,$telefono,$orden);
            $result = $stmt->execute();

            $sqlFolio = 'UPDATE folios SET nombres=?,paqueteria=? WHERE orden=?';
            $stmtFolio = $con->prepare($sqlFolio);
            $stmtFolio->bind_param('sss',$nombre_completo,$paqueteria,$orden);
            $result_Updated_folio = $stmtFolio->execute();
            
            if($result == 1 && $result_Updated_folio ==1){
                header("HTTP/1.1 200 OK");
                $response['status'] = 200;
                $response['mensaje'] = "Se ha actualizado correctamente";
                echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
            }else{
                header("HTTP/1.1 400");
                $response['status'] = 400;
                $response['mensaje'] = "Ha ocurrido un error";
                echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
            }
        }
    //echo "Informacion".file_get_contents('php://input');

}else{
    echo "DB FOUND CONNECTED";
}
?>