<?php
// conexion con base de datos 
include '../../conexion/conn.php';
include '../../headers.php';
require_once '../../class/categoriesSubcategories.class.php';
//import middleware

// declarar array para respuestas 
$response = array();
$categories = array();
$subcategories = array();
$categorySubcategory = new categoriesSubcategoies(); 

date_default_timezone_set('America/Mexico_City');
$fecha = date('Y-m-d H:i:s');

// insertamos cabeceras para permisos 

// validamos si hay conexion 
if($con){
        $methodApi = $_SERVER['REQUEST_METHOD'];

        if($methodApi == 'GET'){
            $result = $categorySubcategory->getAllCategories($con);
            $i=0;
            foreach($result as $row){
                $categories[$i]['id'] = $row['id_categoria'];
                $categories[$i]['nombre'] = $row['nombre_categoria'];
                $resultSubcategory = $categorySubcategory->getSubcategoriesCategoryId($con,$row['id_categoria']);
                $j=0;
                foreach($resultSubcategory as $fill){
                    $subcategories[$j]['id'] = $fill['id_subcategoria'];
                    $subcategories[$j]['nombre'] = $fill['nombre_subcategoria'];
                    $j++;
                }
                $categories[$i]['subcategorias'] = $subcategories;
                $i++;
            }
            echo  json_encode($categories,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT); 
        }

}else{
    echo "DB FOUND CONNECTED";
}