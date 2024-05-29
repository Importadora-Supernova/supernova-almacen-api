<?php
// conexion con base de datos 
include '../../conexion/conn.php';
require_once '../../class/products.class.php';
include '../../headers.php';
//import middleware

// declarar array para respuestas 
$response = array();
$product  = array();
$imagenes = array();
$atributos = array();

$producto = new Producto();

date_default_timezone_set('America/Mexico_City');
$fecha = date('Y-m-d H:i:s');

// insertamos cabeceras para permisos 

// validamos si hay conexion 
if($con){
    //if($token_access['token']){
        $methodApi = $_SERVER['REQUEST_METHOD'];

        if($methodApi == 'GET'){
            if(isset($_GET['id'])){
                $id = intval($_GET['id']);
                $id_user = $_GET['user'];
                $product = $producto->getProductId($con,$id,$id_user);
                $imagenes = $producto->getImagesProduct($con,$id);
                $atributos = $producto->getAtributesProductId($con,$id);

                $response['producto'] = $product;
                $response['imagenes'] = $imagenes;
                $response['atributos'] = $atributos;
                echo  json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT); 
            }else{
                $response = $producto->getProductsSearch($con);
                echo  json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT); 
            }
        }

        if($methodApi == 'POST'){
            $_POST = json_decode(file_get_contents('php://input'),true);
            $idCategory = $_POST['category'];
            $response = $producto->getProductsSubcategory($con,$idCategory);
            echo  json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT); 
        }


}else{
    echo "DB FOUND CONNECTED";
}