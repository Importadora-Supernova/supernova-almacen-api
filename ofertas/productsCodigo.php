<?php
// insertamos cabeceras para permisos 
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept,Authorization, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");
header('Content-Type: application/json;charset=utf-8'); 

// conexion con base de datos 
include '../conexion/conn.php';
require('../class/ofertasPackage.class.php');

//incluir middleware
//include '../middleware/validarToken.php';

date_default_timezone_set('America/Mexico_City');
$fecha = date('Y-m-d H:i:s');

// declarar array para respuestas 
$response = array();


//instanciar clase
$oferta = new OfertasPackages();


// validamos si hay conexion 
if($con){
    // if($token_access['token']){
        $methodApi = $_SERVER['REQUEST_METHOD'];

        //method get
        if($methodApi == 'GET'){
            if(isset($_GET['codigo'])){

                $products = $oferta->getProductosCodigo($con,$_GET['codigo']);
                echo json_encode($products,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
            }else{
                
                $packages = $oferta->getPackages($con);
                echo json_encode($packages,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
            }
        }
        //method post
        if($methodApi == 'POST'){
            
            try{
                $_POST = json_decode(file_get_contents('php://input'),true);

                $process = $oferta->createPackageOffers($con,$_POST);

                if($process){
                    header("HTTP/1.1 200");
                    $response['status'] = 200;
                    $response['mensaje'] = 'Se creo paquete de ofertas correctamente';
                    echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                }else{
                    header("HTTP/1.1 400");
                    $response['status'] = 400;
                    $response['mensaje'] = 'Ocurrio un error, intentalo nuevamente';
                    echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                }

            }catch(Exception $e){
                header("HTTP/1.1 400");
                $response['status'] = 400;
                $response['mensaje'] = $e->getMessage();
                echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
            }
                        
        }

    // }else{
    //     header("HTTP/1.1 401");
    //     $response['status'] = 401;
    //     $response['mensaje'] = $token_access['validate'];
    //     echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
    // }
}else{
    echo "DB FOUND CONNECTED";
}