<?php
// conexion con base de datos 
include 'conexion/conn.php';
//incluir middleware


// declarar array para respuestas 
$products = array();
$response = array();

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

        if($methodApi == 'GET'){

             // para obtener un registro especifico
            $pagina = 1;
            $productosPorPagina = 20;
            if (isset($_GET["pagina"])) {
                $pagina = $_GET["pagina"];


                $limit = $productosPorPagina;
                $offset = ($pagina - 1) * $productosPorPagina;

                $sentencia = "SELECT count(*) AS conteo FROM productos p LEFT JOIN img i ON p.id = i.id_producto WHERE p.estatus = 1 AND i.posicion=1";
                $result = mysqli_query($con,$sentencia);

                while($row = mysqli_fetch_assoc($result)){
                    $conteo = $row['conteo'];
                }

                $paginas = ceil($conteo / $productosPorPagina);

                //SELECT p.id,p.nombre,p.codigo,p.preciou,p.visitas,i.a FROM productos p LEFT JOIN img i ON p.id = i.id_producto WHERE p.estatus = 1 AND i.posicion=1;

                $productos = mysqli_query($con,"SELECT p.id,p.nombre,p.codigo,p.preciou,p.visitas,i.a FROM productos p LEFT JOIN img i ON p.id = i.id_producto WHERE p.estatus = 1 AND i.posicion=1 LIMIT $limit OFFSET $offset");
                $i=0;
                while($row = mysqli_fetch_assoc($productos)){
                        $products[$i]['id'] = $row['id'];
                        $products[$i]['nombre'] = $row['nombre'];
                        $products[$i]['codigo'] = $row['codigo'];
                        $products[$i]['preciou'] = $row['preciou']; 
                        $products[$i]['visitas'] = $row['visitas']; 
                        $products[$i]['a'] = $row['a']; 
                        $i++;
                }
                $response['paginas'] = $paginas;
                $response['productos'] = $products;
                echo  json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);  


                // $sentencia = $base_de_datos->prepare("");
                // $sentencia->execute([$limit, $offset]);
                // $productos = $sentencia->fetchAll(PDO::FETCH_OBJ);
            }
        }
    //echo "Informacion".file_get_contents('php://input');

}else{
    echo "DB FOUND CONNECTED";
}
